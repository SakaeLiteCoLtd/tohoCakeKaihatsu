<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();

 use Cake\ORM\TableRegistry;//独立したテーブルを扱う
 $this->Products = TableRegistry::get('Products');
 $this->Linenames = TableRegistry::get('Linenames');

?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>データ測定</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subtouroku.gif',array('width'=>'145','height'=>'50','url'=>array('action'=>'addformpre')));?></td>
    <td style='border: none'>　　</td>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subyobidashi.gif',array('width'=>'145','height'=>'50','url'=>array('action'=>'kensakumenu')));?></td>
  </tr>
</table>

<br><br>
 <div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
<br>

<br>
  <div align="center"><strong><font color="blue"><?php echo "未完了検査表一覧（本日分以外）";?></font></strong></div>
<br>
<table class="white">
      <tbody class='sample non-sample'>
        <tr>
        <td>ライン</td>
        <td style='width:200'>管理No.</td>
        <td>　製品名　</td>
        <td style='width:100'>検査日</td>
        </tr>

          <?php for($i=0; $i<count($arrInspectionDataResultParentnotfin); $i++): ?>

            <?php
            $ProductDatas = $this->Products->find()
            ->where(['product_code' => $arrInspectionDataResultParentnotfin[$i]["product_code"]])->toArray();
            $LinenameDatas = $this->Linenames->find()
            ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $arrInspectionDataResultParentnotfin[$i]["machine_num"]])->toArray();
            ?>

            <tr>
            <td><?= h($LinenameDatas[0]["name"]);?></td>
              <td><?= h($arrInspectionDataResultParentnotfin[$i]["product_code"]) ? $this->Html->link($arrInspectionDataResultParentnotfin[$i]["product_code"],
               ['controller' => 'Kensahyousokuteidatas', 'action' => 'kensatyuichiran', 's' => "1_".$arrInspectionDataResultParentnotfin[$i]["machine_num"]."_".$arrInspectionDataResultParentnotfin[$i]["product_code"]]) : '' ?></td>
              <td><?= h("　".$arrInspectionDataResultParentnotfin[$i]["name"]."　");?></td>
              <td style="font-size: 10pt"><?= h($arrInspectionDataResultParentnotfin[$i]["datetime"]);?></td>
            </tr>
          <?php endfor;?>

      </tbody>
    </table>

    <br><br>

<div align="center"><strong><font color="blue"><?php echo "本日測定中検査表一覧";?></font></strong></div>
<br>
<table class="white">
      <tbody class='sample non-sample'>
        <tr>
        <td>ライン</td>
        <td style='width:200'>管理No.</td>
          <td>　製品名　</td>
        </tr>

          <?php for($i=0; $i<count($arrInspectionDataResultParents); $i++): ?>

            <?php
      $ProductDatas = $this->Products->find()
      ->where(['product_code' => $arrInspectionDataResultParents[$i]["product_code"]])->toArray();
      $LinenameDatas = $this->Linenames->find()
      ->where(['delete_flag' => 0, 'factory_id' => $ProductDatas[0]["factory_id"], 'machine_num' => $arrInspectionDataResultParents[$i]["machine_num"]])->toArray();
            ?>

            <tr>
            <td><?= h($LinenameDatas[0]["name"]);?></td>
              <td><?= h($arrInspectionDataResultParents[$i]["product_code"]) ? $this->Html->link($arrInspectionDataResultParents[$i]["product_code"], ['action' => 'kensatyuichiran', 's' => "0_".$arrInspectionDataResultParents[$i]["machine_num"]."_".$arrInspectionDataResultParents[$i]["product_code"]]) : '' ?></td>
              <td><?= h("　".$arrInspectionDataResultParents[$i]["name"]."　");?></td>
            </tr>
          <?php endfor;?>

      </tbody>
    </table>

<br>
