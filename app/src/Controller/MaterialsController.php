<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Datasource\ConnectionManager;//トランザクション
use Cake\Core\Exception\Exception;//トランザクション
use Cake\Core\Configure;//トランザクション
use Cake\Auth\DefaultPasswordHasher;//パスワードの更新時のハッシュ化

class MaterialsController extends AppController
{

      public function initialize()
    {
     parent::initialize();
     $this->Materials = TableRegistry::get('Materials');
     $this->MaterialSuppliers = TableRegistry::get('MaterialSuppliers');
     $this->MaterialTypes = TableRegistry::get('MaterialTypes');
     $this->Factories = TableRegistry::get('Factories');
     $this->Tanis = TableRegistry::get('Tanis');
     
     $this->Menus = TableRegistry::get('Menus');//以下ログイン権限チェック
     $this->Groups = TableRegistry::get('Groups');

     $session = $this->request->getSession();
     $datasession = $session->read();

     if(!isset($datasession['Auth']['User'])){//そもそもログインしていない場合

       return $this->redirect($this->Auth->logout());

     }

     if($datasession['Auth']['User']['super_user'] == 0){//スーパーユーザーではない場合(スーパーユーザーの場合はそのままで大丈夫)

       $Groups = $this->Groups->find()->contain(["Menus"])
       ->where(['Groups.group_name_id' => $datasession['Auth']['User']['group_name_id'], 'Menus.id' => 37, 'Groups.delete_flag' => 0])
       ->toArray();

       if(!isset($Groups[0])){//権限がない人がログインした状態でurlをベタ打ちしてアクセスしてきた場合

         return $this->redirect($this->Auth->logout());

       }

     }

    }

    public function index($id = null)
    {
      $this->paginate = [
          'limit' => 13,
          'contain' => ['MaterialSuppliers', 'MaterialTypes', 'Factories'],
          'order' => ['Materials.created_at' => 'desc']
        ];
      $materials = $this->paginate($this->Materials->find()->where(['Materials.delete_flag' => 0]));
      $this->set(compact('materials'));
    }

    public function editpreform()
    {
      $materials = $this->Materials->newEntity();
      $this->set('materials', $materials);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $data = $this->request->getData();
        $mess = "";
        $this->set('mess',$mess);
      }
      /*
      echo "<pre>";
      print_r($data);
      echo "</pre>";
*/
      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $MaterialSupplier_name_list = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterialSuppliers_name_list = array();
      for($j=0; $j<count($MaterialSupplier_name_list); $j++){
        array_push($arrMaterialSuppliers_name_list,$MaterialSupplier_name_list[$j]["name"]);
      }
      $this->set('arrMaterialSuppliers_name_list', $arrMaterialSuppliers_name_list);

      $Materials_name_list = $this->Materials->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterials_name_list = array();
      for($j=0; $j<count($Materials_name_list); $j++){
        array_push($arrMaterials_name_list,$Materials_name_list[$j]["name"]);
      }
      $arrMaterials_name_list = array_unique($arrMaterials_name_list);
      $arrMaterials_name_list = array_values($arrMaterials_name_list);
      $this->set('arrMaterials_name_list', $arrMaterials_name_list);
      
      if(isset($data["materialSupplier"])){//顧客絞り込みをしたとき
  
        $Material_name_list = $this->Materials->find()
        ->contain(['MaterialSuppliers'])
        ->where(['MaterialSuppliers.name' => $data["materialSuppliername"], 'Materials.delete_flag' => 0])
        ->toArray();
  
         if(count($Material_name_list) < 1){//顧客名にミスがある場合
  
           $mess = "入力された仕入先の仕入品は登録されていません。確認してください。";
           $this->set('mess',$mess);
  
           $Material_name_list = $this->Materials->find()
           ->where(['delete_flag' => 0])
           ->toArray();
 
           $Material_name_list = array();
           for($j=0; $j<count($Material_name_list); $j++){
             array_push($Material_name_list,$Material_name_list[$j]["name"]);
           }
           $this->set('arrMaterials_name_list', $arrMaterials_name_list);
  
         }else{
  
          $customer_check = 1;
          $this->set('customer_check', $customer_check);
    
           $arrMaterials_name_list = array();
           for($j=0; $j<count($Material_name_list); $j++){
             array_push($arrMaterials_name_list,$Material_name_list[$j]["name"]);
           }
           $arrMaterials_name_list = array_unique($arrMaterials_name_list);
           $arrMaterials_name_list = array_values($arrMaterials_name_list);
           $this->set('arrMaterials_name_list', $arrMaterials_name_list);
   
         }
  
       }elseif(isset($data["next"])){//「次へ」ボタンを押したとき

         if(strlen($data["namematerial"]) > 0){//product_nameの入力がある
  
          $name = $data["namematerial"];
    
          $Materials = $this->Materials->find()
          ->where(['factory_id' => $data["factory_id"], 'name' => $name, 'delete_flag' => 0])
          ->toArray();
  
           if(isset($Materials[0])){
  
             return $this->redirect(['action' => 'detail',
             's' => ['factory_id' => $data["factory_id"], 'name' => $data["namematerial"]]]);
  
           }else{
  
             $mess = "入力された仕入品は登録されていません。工場名・仕入品名を確認してください。";
             $this->set('mess',$mess);
  
              $Material_name_list = $this->Materials->find()
              ->where(['delete_flag' => 0])->toArray();
    
             $arrMaterials_name_list = array();
             for($j=0; $j<count($Material_name_list); $j++){
               array_push($arrMaterials_name_list,$Material_name_list[$j]["name"]);
             }
             $this->set('arrMaterials_name_list', $arrMaterials_name_list);
  
           }
  
         }else{//product_nameの入力がない
  
           $mess = "仕入品名が入力されていません。";
           $this->set('mess',$mess);
  
           $Material_name_list = $this->Materials->find()
           ->where(['delete_flag' => 0])->toArray();
 
           $arrMaterials_name_list = array();
           for($j=0; $j<count($Material_name_list); $j++){
             array_push($arrMaterials_name_list,$Material_name_list[$j]["name"]);
           }
           $this->set('arrMaterials_name_list', $arrMaterials_name_list);
  
         }
  
       }
       if(!isset($_SESSION)){
        session_start();
      }
      header('Expires:-1');
      header('Cache-Control:');
      header('Pragma:');
  
      print_r(" ");//フォームの再読み込みの防止

    }

    public function detail($id = null)
    {
      $materials = $this->Materials->newEntity();
      $this->set('materials', $materials);

      $data = $this->request->getData();
      if(isset($data["edit"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialdata'] = array();
        $_SESSION['materialdata'] = $id;

        return $this->redirect(['action' => 'editform']);

      }elseif(isset($data["delete"])){

        $id = $data["id"];

        if(!isset($_SESSION)){
          session_start();
        }
        $_SESSION['materialdata'] = array();
        $_SESSION['materialdata'] = $id;

        return $this->redirect(['action' => 'deleteconfirm']);

      }elseif(isset($this->request->query('s')["name"])){
  
        $data = $this->request->query('s');
        $Materials = $this->Materials->find()->where(['name' => $data['name'], 'factory_id' => $data['factory_id'], 'delete_flag' => 0])->toArray();
        $id = $Materials[0]["id"];

      }
      $this->set('id', $id);

      $material = $this->Materials->get($id, [
        'contain' => ["Factories", "MaterialTypes", "MaterialSuppliers"]
      ]);
      $this->set(compact('material'));

      $Materials = $this->Materials->find()
      ->where(['id' => $id])->toArray();
      $status_kensahyou = $Materials[0]["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);

    }

    public function view($id = null)
    {
        $material = $this->Materials->get($id, [
            'contain' => ['MaterialTypes', 'PriceMaterials', 'ProductMaterials']
        ]);

        $this->set('material', $material);
    }

    public function addform()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }
      
      $Tanis = $this->Tanis->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrTanni = array();
      for($j=0; $j<count($Tanis); $j++){
        $arrTanni[$Tanis[$j]["name"]] = $Tanis[$j]["name"];
      }
      $this->set('arrTanni', $arrTanni);

      $this->set('arrTanni', $arrTanni);

      $arrStatusKensahyou = ["0" => "表示", "1" => "非表示"];
      $this->set('arrStatusKensahyou', $arrStatusKensahyou);

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['delete_flag' => 0])->toArray();

      $arrMaterialTypes = array();
      foreach ($MaterialTypes as $value) {
        $arrMaterialTypes[] = array($value->id=>$value->type);
      }
      $this->set('arrMaterialTypes', $arrMaterialTypes);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();

      $Factories = $this->Factories->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrFactories = array();
      foreach ($Factories as $value) {
        $arrFactories[] = array($value->id=>$value->name);
      }
      $this->set('arrFactories', $arrFactories);

      $MaterialSuppliers_name_list = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterialSuppliers_name_list = array();
      for($j=0; $j<count($MaterialSuppliers_name_list); $j++){
        array_push($arrMaterialSuppliers_name_list,$MaterialSuppliers_name_list[$j]["name"]);
      }
      $arrMaterialSuppliers_name_list = array_unique($arrMaterialSuppliers_name_list);
      $arrMaterialSuppliers_name_list = array_values($arrMaterialSuppliers_name_list);
      $this->set('arrMaterialSuppliers_name_list', $arrMaterialSuppliers_name_list);

    }

    public function addcomfirm()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['name' => $data['material_supplier_name']])->toArray();
      if(isset($MaterialSuppliers[0])){

        $material_supplier_id = $MaterialSuppliers[0]['id'];
        $this->set('material_supplier_id', $material_supplier_id);
        $supplier_name = $MaterialSuppliers[0]['name'];
        $this->set('supplier_name', $supplier_name);

        }else{

        return $this->redirect(['action' => 'addform',
        's' => ['mess' => "入力された仕入先名は存在しません。"]]);

      }

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $status_kensahyou = $data["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);

    }

    public function adddo()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $status_kensahyou = $data["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $data['material_supplier_id']])->toArray();
      $material_supplier_code = $MaterialSuppliers[0]['material_supplier_code'];
      $supplier_name = $MaterialSuppliers[0]['name'];
      $this->set('supplier_name', $supplier_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $staff_id = $datasession['Auth']['User']['staff_id'];

      if($data['factory_id'] == 1){
        $code_factory = "D";
      }elseif($data['factory_id'] == 2){
        $code_factory = "I";
      }elseif($data['factory_id'] == 3){
        $code_factory = "B";
      }elseif($data['factory_id'] == 4){
        $code_factory = "K";
      }else{
        $code_factory = "H";
      }

      //最初の8桁が同じものがある
      //typeチェック
      $Materialstypecheck = $this->Materials->find()
      ->where(['material_code like' => "S".$material_supplier_code.$code_factory.'%', 'material_type_id' => $data["type_id"]])
      ->order(["material_code"=>"DESC"])->toArray();

      if(isset($Materialstypecheck[0])){//typeが同じものがある・・・それと同じ３桁を取り、最後の２桁で連番

        $material_code_renban = substr($Materialstypecheck[0]["material_code"], -5, 3);

        if(substr($Materialstypecheck[0]["material_code"], -2, 2) == 99){

          return $this->redirect(['action' => 'addform',
          's' => ['mess' => "※仕入品コードが取得できません。別の仕入品種類で登録しなおしてください。"]]);
  
        }else{
          $material_code_renban2 = substr($Materialstypecheck[0]["material_code"], -2, 2) + 1;
        }

        $material_code_renban2 = sprintf('%02d', $material_code_renban2);//0埋め

      }else{//typeが同じものがない

        $material_code_renban = $type_name;
  
        $Materialsnow2 = $this->Materials->find()
        ->where(['material_code like' => "S".$material_supplier_code.$code_factory.$material_code_renban.'%'])
        ->order(["material_code"=>"DESC"])->toArray();
  
        $material_code_renban2 = "01";
  
      }

      $material_code = "S".$material_supplier_code.$code_factory.$material_code_renban.$material_code_renban2;
      $this->set('material_code', $material_code);

      $arrtourokumaterial = array();
      $arrtourokumaterial = [
        'factory_id' => $data["factory_id"],
        'material_code' => $material_code,
        'name' => $data["name"],
        'material_supplier_id' => $data["material_supplier_id"],
        'material_type_id' => $data["type_id"],
        'tanni' => $data["tanni"],
        'status_kensahyou' => $data["status_kensahyou"],
        'is_active' => 0,
        'delete_flag' => 0,
        'created_at' => date("Y-m-d H:i:s"),
        'created_staff' => $staff_id
      ];

      //新しいデータを登録
      $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $arrtourokumaterial);
      $connection = ConnectionManager::get('default');//トランザクション1
      // トランザクション開始2
      $connection->begin();//トランザクション3
      try {//トランザクション4
        if ($this->Materials->save($Materials)) {

          $connection->commit();// コミット5
          $mes = "以下のように登録されました。";
          $this->set('mes',$mes);

        } else {

          $mes = "※登録されませんでした";
          $this->set('mes',$mes);
          $this->Flash->error(__('The data could not be saved. Please, try again.'));
          throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

        }

      } catch (Exception $e) {//トランザクション7
      //ロールバック8
         $connection->rollback();//トランザクション9
      }//トランザクション10

    }

    public function add()
    {
        $material = $this->Materials->newEntity();
        if ($this->request->is('post')) {
            $material = $this->Materials->patchEntity($material, $this->request->getData());
            if ($this->Materials->save($material)) {
                $this->Flash->success(__('The material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material could not be saved. Please, try again.'));
        }
        $MaterialTypes = $this->Materials->MaterialTypes->find('list', ['limit' => 200]);
        $this->set(compact('material', 'MaterialTypes'));
    }

    public function editform($id = null)
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialdata'];
      $this->set('id', $id);

      $Tanis = $this->Tanis->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrTanni = array();
      for($j=0; $j<count($Tanis); $j++){
        $arrTanni[$Tanis[$j]["name"]] = $Tanis[$j]["name"];
      }
      $this->set('arrTanni', $arrTanni);

      $arrStatusKensahyou = ["0" => "表示", "1" => "非表示"];
      $this->set('arrStatusKensahyou', $arrStatusKensahyou);

      $Materials = $this->Materials->find()
      ->where(['id' => $id])->toArray();
      $this->set('name', $Materials[0]["name"]);
      $this->set('material_code', $Materials[0]["material_code"]);
      $this->set('material_type_id', $Materials[0]["material_type_id"]);
      $this->set('material_supplier_id', $Materials[0]["material_supplier_id"]);
      $this->set('status_kensahyou', $Materials[0]["status_kensahyou"]);

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $Materials[0]["material_type_id"]])->toArray();
      $material_type = $MaterialTypes[0]["type"];
      $this->set('material_type', $material_type);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $Materials[0]["material_supplier_id"]])->toArray();
      $this->set('supplier_name', $MaterialSuppliers[0]["name"]);

      $Factories = $this->Factories->find()
      ->where(['id' => $Materials[0]['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_id', $Materials[0]['factory_id']);
      $this->set('factory_name', $factory_name);

    }

    public function editconfirm()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['material_type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $data['material_supplier_id']])->toArray();
      $supplier_name = $MaterialSuppliers[0]['name'];
      $this->set('supplier_name', $supplier_name);

      $status_kensahyou = $data["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);

    }

    public function editdo()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $status_kensahyou = $data["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);

      $MaterialTypes = $this->MaterialTypes->find()
      ->where(['id' => $data['material_type_id']])->toArray();
      $type_name = $MaterialTypes[0]['type'];
      $this->set('type_name', $type_name);

      $Factories = $this->Factories->find()
      ->where(['id' => $data['factory_id']])->toArray();
      $factory_name = $Factories[0]['name'];
      $this->set('factory_name', $factory_name);

      $MaterialSuppliers = $this->MaterialSuppliers->find()
      ->where(['id' => $data['material_supplier_id']])->toArray();
      $supplier_name = $MaterialSuppliers[0]['name'];
      $this->set('supplier_name', $supplier_name);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $Materialsmoto = $this->Materials->find()
      ->where(['id' => $data['id']])->toArray();

        $material_code = $data["material_code"];
        $this->set('material_code', $material_code);

        $arrMaterialmoto = array();
      $arrMaterialmoto = [
        'factory_id' => $Materialsmoto[0]["factory_id"],
        'material_code' => $Materialsmoto[0]["material_code"],
        'name' => $Materialsmoto[0]["name"],
        'material_supplier_id' => $Materialsmoto[0]["material_supplier_id"],
        'material_type_id' => $Materialsmoto[0]["material_type_id"],
        'tanni' => $Materialsmoto[0]["tanni"],
        'status_kensahyou' => $Materialsmoto[0]["status_kensahyou"],
        'is_active' => 1,
        'delete_flag' => 1,
        'created_at' => $Materialsmoto[0]["created_at"]->format("Y-m-d H:i:s"),
        'created_staff' => $Materialsmoto[0]["created_staff"],
        'updated_at' => date("Y-m-d H:i:s"),
        'updated_staff' => $staff_id
      ];

      $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $data);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
        if ($this->Materials->updateAll(
          ['factory_id' => $data["factory_id"],
           'name' => $data["name"],
           'tanni' => $data["tanni"],
           'status_kensahyou' => $data["status_kensahyou"],
           'updated_at' => date('Y-m-d H:i:s'),
           'updated_staff' => $staff_id],
          ['id'  => $data['id']])){

            $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $arrMaterialmoto);
            $this->Materials->save($Materials);

         $mes = "※下記のように更新されました";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※更新されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

    public function edit($id = null)
    {
        $material = $this->Materials->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $material = $this->Materials->patchEntity($material, $this->request->getData());
            if ($this->Materials->save($material)) {
                $this->Flash->success(__('The material has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The material could not be saved. Please, try again.'));
        }
        $MaterialTypes = $this->Materials->MaterialTypes->find('list', ['limit' => 200]);
        $this->set(compact('material', 'MaterialTypes'));
    }

    public function deleteconfirm($id = null)
    {
      $session = $this->request->getSession();
      $_SESSION = $session->read();

      $id = $_SESSION['materialdata'];

        $material = $this->Materials->get($id, [
          'contain' => ['MaterialTypes', 'MaterialSuppliers', 'Factories']
        ]);
        $this->set(compact('material'));

        $Materials = $this->Materials->find()
        ->where(['id' => $id])->toArray();
        $status_kensahyou = $Materials[0]["status_kensahyou"];
        $this->set('status_kensahyou', $status_kensahyou);
  
    }

    public function deletedo()
    {
      $session = $this->request->getSession();
      $datasession = $session->read();

      $data = $this->request->getData();

      $material = $this->Materials->get($data["id"], [
        'contain' => ['MaterialTypes', 'MaterialSuppliers', 'Factories']
      ]);
      $this->set(compact('material'));

      $Materials = $this->Materials->find()
      ->where(['id' => $data["id"]])->toArray();
      $status_kensahyou = $Materials[0]["status_kensahyou"];
      $this->set('status_kensahyou', $status_kensahyou);

      $staff_id = $datasession['Auth']['User']['staff_id'];

      $arrdeletematerial = array();
      $arrdeletematerial = [
        'id' => $data["id"]
      ];

      $Materials = $this->Materials->patchEntity($this->Materials->newEntity(), $arrdeletematerial);
      $connection = ConnectionManager::get('default');//トランザクション1
       // トランザクション開始2
       $connection->begin();//トランザクション3
       try {//トランザクション4
         if ($this->Materials->updateAll(
           [ 'delete_flag' => 1,
             'updated_at' => date('Y-m-d H:i:s'),
             'updated_staff' => $staff_id],
           ['id'  => $arrdeletematerial['id']]
         )){

         $mes = "※以下のデータが削除されました。";
         $this->set('mes',$mes);
         $connection->commit();// コミット5

       } else {

         $mes = "※削除されませんでした";
         $this->set('mes',$mes);
         $this->Flash->error(__('The data could not be saved. Please, try again.'));
         throw new Exception(Configure::read("M.ERROR.INVALID"));//失敗6

       }

     } catch (Exception $e) {//トランザクション7
     //ロールバック8
       $connection->rollback();//トランザクション9
     }//トランザクション10

    }

    public function kensakupreform()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $Data=$this->request->query('s');
      if(isset($Data["mess"])){
        $mess = $Data["mess"];
        $this->set('mess',$mess);
      }else{
        $mess = "";
        $this->set('mess',$mess);
      }

      $Materials_name_list = $this->Materials->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterials_name_list = array();
      for($j=0; $j<count($Materials_name_list); $j++){
        array_push($arrMaterials_name_list,$Materials_name_list[$j]["name"]);
      }
      $arrMaterials_name_list = array_unique($arrMaterials_name_list);
      $arrMaterials_name_list = array_values($arrMaterials_name_list);
      $this->set('arrMaterials_name_list', $arrMaterials_name_list);

      $MaterialSuppliers_name_list = $this->MaterialSuppliers->find()
      ->where(['delete_flag' => 0])->toArray();
      $arrMaterialSuppliers_name_list = array();
      for($j=0; $j<count($MaterialSuppliers_name_list); $j++){
        array_push($arrMaterialSuppliers_name_list,$MaterialSuppliers_name_list[$j]["name"]);
      }
      $arrMaterialSuppliers_name_list = array_unique($arrMaterialSuppliers_name_list);
      $arrMaterialSuppliers_name_list = array_values($arrMaterialSuppliers_name_list);
      $this->set('arrMaterialSuppliers_name_list', $arrMaterialSuppliers_name_list);

      $arrSorts = ["0" => "コード昇順", "1" => "コード降順", "2" => "登録日最新順"];
      $this->set('arrSorts', $arrSorts);

    }

    public function kensakuichiran()
    {
      $material = $this->Materials->newEntity();
      $this->set('material', $material);

      $data = $this->request->getData();

      if(strlen($data['material_supplier_name']) > 0 && strlen($data['material_name']) > 0){

        $MaterialSuppliers = $this->MaterialSuppliers->find()
        ->where(['name' => $data['material_supplier_name']])->toArray();
        if(isset($MaterialSuppliers[0])){
  
          $material_supplier_id = $MaterialSuppliers[0]['id'];
          $this->set('material_supplier_id', $material_supplier_id);
          $supplier_name = $MaterialSuppliers[0]['name'];
          $this->set('supplier_name', $supplier_name);
  
          $Materials = $this->Materials->find()->contain(["Factories" ,"MaterialSuppliers"])
          ->where(['Materials.name like' => "%".$data["material_name"]."%"
          , 'MaterialSuppliers.name' => $supplier_name, 'Materials.delete_flag' => 0])->toArray();
  
          }else{
  
          return $this->redirect(['action' => 'kensakupreform',
          's' => ['mess' => "入力された仕入先名は存在しません。"]]);
  
        }
  
      }elseif(strlen($data['material_name']) > 0){

        $Materials = $this->Materials->find()->contain(["Factories" ,"MaterialSuppliers"])
        ->where(['Materials.name like' => "%".$data["material_name"]."%", 'Materials.delete_flag' => 0])->toArray();
  
      }elseif(strlen($data['material_supplier_name']) > 0){

        $MaterialSuppliers = $this->MaterialSuppliers->find()
        ->where(['name' => $data['material_supplier_name']])->toArray();
        if(isset($MaterialSuppliers[0])){
  
          $material_supplier_id = $MaterialSuppliers[0]['id'];
          $this->set('material_supplier_id', $material_supplier_id);
          $supplier_name = $MaterialSuppliers[0]['name'];
          $this->set('supplier_name', $supplier_name);
  
          $Materials = $this->Materials->find()->contain(["Factories" ,"MaterialSuppliers"])
          ->where(['MaterialSuppliers.name' => $supplier_name, 'Materials.delete_flag' => 0])->toArray();
    
          }else{
  
          return $this->redirect(['action' => 'kensakupreform',
          's' => ['mess' => "入力された仕入先名は存在しません。"]]);
  
        }

      }else{

        return $this->redirect(['action' => 'kensakupreform',
        's' => ['mess' => "仕入品名または仕入先名を入力してください"]]);

      }

      if($data["sort"] == 0){

        foreach($Materials as $key => $value)
        {
          $sort_keys[$key] = $value['material_code'];
        }
       
        array_multisort($sort_keys, SORT_ASC, $Materials);
      
      }elseif($data["sort"] == 1){

        foreach($Materials as $key => $value)
        {
          $sort_keys[$key] = $value['material_code'];
        }
       
        array_multisort($sort_keys, SORT_DESC, $Materials);

      }else{

        array_multisort(array_map("strtotime", array_column($Materials, "created_at" )), SORT_DESC, $Materials);

      }

      $this->set('Materials', $Materials);

    }

}
