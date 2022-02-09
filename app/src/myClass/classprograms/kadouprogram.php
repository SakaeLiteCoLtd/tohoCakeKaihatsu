<?php
namespace App\myClass\classprograms;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
use Cake\Auth\DefaultPasswordHasher;

class kadouprogram extends AppController
{
     public function initialize()
    {
        parent::initialize();
        $this->Users = TableRegistry::get('Users');
        $this->ShotdataBases = TableRegistry::get('ShotdataBases');
        $this->RelayLogs = TableRegistry::get('RelayLogs');
    }

    public function shotdatacsv($machine_sta_fin)
  	{
      $arrmachine_sta_fin = explode("_",$machine_sta_fin);
      $machine_num = $arrmachine_sta_fin[0];
      $date_sta = $arrmachine_sta_fin[1];
      $date_fin = $arrmachine_sta_fin[2];
  
      $ShotdataBases = $this->ShotdataBases->find()
      ->where(['machine_num' => $machine_num, 'datetime >=' => $date_sta,
       'datetime <' => $date_fin, 'ShotdataBases.delete_flag' => 0])
       ->order(["ShotdataBases.datetime"=>"ASC"])->toArray();
  
       $arrShotdataBasescsv = array();
       for($i=0; $i<count($ShotdataBases); $i++){

        $arrShotdataBasescsv[] = [
          "datetime" => $ShotdataBases[$i]["datetime"]->format("Y-m-d H:i:s"),
          "stop_time" => $ShotdataBases[$i]["stop_time"],
          "temp_outside" => $ShotdataBases[$i]["temp_outside"],
          "temp_inside" => $ShotdataBases[$i]["temp_inside"],
          "temp_water" => $ShotdataBases[$i]["temp_water"],
          "analog1_ch1" => $ShotdataBases[$i]["analog1_ch1"],
          "analog1_ch2" => $ShotdataBases[$i]["analog1_ch2"],
          "analog1_ch3" => $ShotdataBases[$i]["analog1_ch3"],
          "analog1_ch4" => $ShotdataBases[$i]["analog1_ch4"],
          "valid_data_num" => $ShotdataBases[$i]["valid_data_num"],
          "existence_stop" => $ShotdataBases[$i]["existence_stop"],
          "place_stop" => $ShotdataBases[$i]["place_stop"],
          "existence_out_limit" => $ShotdataBases[$i]["existence_out_limit"],
          "place_out_limit" => $ShotdataBases[$i]["place_out_limit"],
          "existence_change_standard_value" => $ShotdataBases[$i]["existence_change_standard_value"],
          "value1_mode" => $ShotdataBases[$i]["value1_mode"],
          "value1_mean" => $ShotdataBases[$i]["value1_mean"],
          "value1_max" => $ShotdataBases[$i]["value1_max"],
          "value1_min" => $ShotdataBases[$i]["value1_min"],
          "value1_std" => $ShotdataBases[$i]["value1_std"],
          "value2_mode" => $ShotdataBases[$i]["value2_mode"],
          "value2_mean" => $ShotdataBases[$i]["value2_mean"],
          "value2_max" => $ShotdataBases[$i]["value2_max"],
          "value2_min" => $ShotdataBases[$i]["value2_min"],
          "value2_std" => $ShotdataBases[$i]["value2_std"],
        ];

       }

        if(count($arrShotdataBasescsv) < 1){//空の場合はhtmlが出力されるためダミーの配列を作成
          $arrShotdataBasescsv[] = [
            "" => "",
          ];
        }
       //CSV形式で情報をファイルに出力のための準備
          $csvFileName = '/tmp/' . time() . rand() . '.csv';
          $res = fopen($csvFileName, 'w');
          if ($res === FALSE) {
            throw new Exception('ファイルの書き込みに失敗しました。');
          }

          foreach($arrShotdataBasescsv as $dataInfo) {
            // 文字コード変換。エクセルで開けるようにする
            mb_convert_variables('SJIS', 'UTF-8', $dataInfo);
            fputcsv($res, $dataInfo);
          }
          fclose($res);

          header('Content-Type: application/octet-stream');

          $filename = "ショットデータ".substr($date_sta, 0, 10)."_".$machine_num."号ライン.csv";
          header("Content-Disposition: attachment; filename=${filename}"); 
          header('Content-Transfer-Encoding: binary');
          header('Content-Length: ' . filesize($csvFileName));
          readfile($csvFileName);

          exit;//exitをいれておかないとhtmlのソースを含んだCSVファイルになってしまう
  	}

    public function yobidashirelaylogs($machine_sta_fin)
  	{
      $arrmachine_sta_fin = explode("_",$machine_sta_fin);
      $machine_num = $arrmachine_sta_fin[0];
      $date_sta = $arrmachine_sta_fin[1];
      $date_fin = $arrmachine_sta_fin[2];

      $RelayLogs = $this->RelayLogs->find()->contain(["MachineRelays"])
      ->where(['machine_num' => $machine_num, 'datetime >=' => $date_sta,
       'datetime <' => $date_fin, 'RelayLogs.delete_flag' => 0])
       ->order(["RelayLogs.datetime"=>"ASC"])->toArray();
  
       $arrRelayLogs = array();
       for($i=0; $i<count($RelayLogs); $i++){
  
        if($RelayLogs[$i]["status"] == 1){
          $status = "ON";
        }else{
          $status = "OFF";
        }
  
        $arrRelayLogs[] = [
          "datetime" => $RelayLogs[$i]["datetime"]->format("Y-m-d H:i"),
          "name" => $RelayLogs[$i]["machine_relay"]["name"],
          "status" => $status
        ];
  
       }

       return $arrRelayLogs;

    }

}

?>
