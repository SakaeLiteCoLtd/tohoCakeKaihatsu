<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
 use App\myClass\classprograms\htmlkensahyouprogram;//myClassフォルダに配置したクラスを使用
 $htmlkensahyougenryouheader = new htmlkensahyouprogram();
?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>データ測定</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/addformpre' /><font size='4' color=black>新規登録</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'addconditionconfirm']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('product_condition_parent_id', array('type'=>'hidden', 'value'=>$product_condition_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>
 <?php
    //   echo $htmlgenryouheader;
  ?>


<table class="white">

<tr>
  <td colspan='3'>バンク：<?= h($mode) ?></td>
<td style='width:98'>測定箇所</td>

<?php for($i=1; $i<=11; $i++): ?>
  <?php if (${"size_name".$i} == "長さ"): ?>
    <td style='width:78'>切断長</td>
  <?php else : ?>
    <td style='width:78'><?= h(${"size_name".$i}) ?></td>
  <?php endif; ?>
<?php endfor;?>

<td width="56" rowspan='3'>外観</td>
<td width="70" rowspan='3'>重量<br>（目安）</td>
<td width="35" rowspan='5' style='font-size: 10pt'>合<br>否<br>判<br>定</td>
<td width="35" rowspan='5' style='font-size: 10pt'>工<br>程<br>異<br>常</td>

</tr>
<tr>

<td width="36" rowspan='8' style='font-size: 10pt'>No.</td>
<td width="130" rowspan='7'>時間</td>
<td width="60" rowspan='6'>規格<br>長さ<br>(mm)</td>

<td>規格</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td><?= h(${"size".$i}) ?></td>
  <?php endfor;?>
</tr>
<tr>
<td>公差上限</td>

<?php for($i=1; $i<=11; $i++): ?>
  <?php if (${"input_type".$i} == "int" && strlen(${"upper_limit".$i}) > 0 && substr(${"upper_limit".$i}, 0, 1) != "+" && substr(${"upper_limit".$i}, 0, 1) != "-"): ?>
  <td><?= h("+".${"upper_limit".$i}) ?></td>
  <?php else : ?>
    <td><?= h(${"upper_limit".$i}) ?></td>
    <?php endif; ?>
<?php endfor;?>

</tr>
<tr>
<td>公差下限</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td><?= h(${"lower_limit".$i}) ?></td>
  <?php endfor;?>

  <td width="56" style="font-size: 10pt">〇・✕</td>
      <td width="60">g / 本</td>

</tr>
<tr>
<td>検査器具</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
  <?php endfor;?>

  <td width="56" style='font-size: 10pt'>目視</td>
  <td style='width:60; border-top-style:none; font-size: 9pt'>デジタル秤</td>

</tr>

</table>


 <?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>

  <?php if ($j == 1): ?>

    <br>
    <table align="left">
  <tbody>
    <tr style="background-color: #FFFFCC">
    <td style="border:none; background-color:#E6FFFF">　　　　　　　　　　</td>
    <td width="100"><strong><?= __($linename) ?>号ライン</strong></td>
    </tr>
  </tbody>
</table>
<br><br>

    <?php else : ?>
      <?php endif; ?>

 <table>
   <tr class="parents">
     <td width="150">成形機</td>
     <td width="484">原料名</td>
     <td width="190">配合比</td>
     <td width="130">乾燥温度</td>
     <td width="130">乾燥時間</td>
     <td width="130">乾燥条件<br>チェック</td>
     <td width="200">再生配合比</td>
 </tr>

 <?php
    for($i=1; $i<=${"tuikagenryou".$j}; $i++){

      echo "<tr class='children'>\n";

         if($i==1){
           echo "<td rowspan=${"tuikagenryou".$j}>\n";
           echo ${"cylinder_name".$j};
           echo "</td>\n";
         }

         echo "<td>\n";
         echo ${"material_hyouji".$j.$i};
         echo "</td>\n";
         echo "<td>\n";
         echo ${"mixing_ratio".$j.$i};
         echo "</td>\n";

         if($i==1){

         echo "<td rowspan=${"tuikagenryou".$j}>\n";
         echo ${"dry_temp".$j.$i}." ℃";
         echo "</td>\n";
         echo "<td rowspan=${"tuikagenryou".$j}>\n";
         echo ${"dry_hour".$j.$i}." h以上";
         echo "</td>\n";
         if(${"dry_hour".$j.$i} > 0){
           echo "<td rowspan=${"tuikagenryou".$j} style='font-size: 10pt'>\n";
           echo "確認チェック";
           echo "<br>\n";
           echo "<input type='checkbox' name=check".$j." required>\n";
           echo "</td>\n";
         }else{
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo "</td>\n";
          }

            echo "<td rowspan=${"tuikagenryou".$j}>\n";
            echo ${"recycled_mixing_ratio".$j.$i};
            echo "</td>\n";
             }
 
         echo "</tr>\n";

       }
  ?>
 </table>

 <?php endfor;?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>

<table>
  <tr class="parents">
  <td style='width:81'>成形機</td>
  <td width="100">温度条件</td>
  <td style='width:70'>C １</td>
  <td style='width:70'>C ２</td>
  <td style='width:70'>C ３</td>
  <td style='width:70'>C ４</td>
  <td style='width:70'>A D</td>
  <td style='width:70'>D １</td>
  <td style='width:70'>D ２</td>
  <td style='width:200' colspan="2">押出回転(rpm)/負荷(A)</td>
  <td style='width:100'>引取速度<br>（m/min）</td>
  <td style='width:200' colspan="2">ｽｸﾘｰﾝﾒｯｼｭ : 枚数</td>
  <td style='width:200'>ｽｸﾘｭｳ</td>
</tr>

<?php
   for($i=1; $i<=3; $i++){

     echo "<tr class='children'>\n";

        if($i==1){
          echo "<td rowspan=3>\n";
          echo "${"cylinder_name".$j}\n";
          echo "</td>\n";
        }

        if($i==1){
          echo "<td style='width:50px'>\n";
          echo "基 準 値\n";
          echo "</td>\n";
        }elseif($i==2){
          echo "<td style='width:50px'>\n";
          echo "記    録\n";
          echo "</td>\n";
        }elseif($i==3){
          echo "<td style='width:50px'>\n";
          echo "許容範囲\n";
          echo "</td>\n";
        }

        if($i == 1){
          echo "<td>\n";
          echo "${"temp_1".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_2".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_3".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_4".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_5".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_6".$j}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"temp_7".$j}\n";
          echo "</td>\n";
          echo "<td style='border-right-style:none; text-align:right'>\n";
          echo "${"extrude_roatation".$j}(rpm)\n";
          echo "</td>\n";
          echo "<td style='border-left-style:none; text-align:left'>\n";
          echo "/ ${"extrusion_load".$j}(A)\n";
          echo "</td>\n";
        }elseif($i == 2){
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_1".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_2".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_3".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_4".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_5".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_6".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='tel' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_temp_7".$j.">\n";
          echo "</td>\n";
          echo "<td style='border-right-style:none'>\n";
          echo "<input type='tel' style='width:70px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_extrude_roatation".$j.">(rpm)\n";
          echo "</td>\n";
          echo "<td style='border-left-style:none'>\n";
          echo "/ <input type='tel' style='width:70px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=inspection_extrusion_load".$j.">(A)\n";
          echo "</td>\n";
        }else{
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td colspan=2>\n";
          echo "± 5.0\n";
          echo "</td>\n";
        }

        if($j==1){
            if($i==1){
              echo "<td>\n";
              echo "$pickup_speed\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_1".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_1".$j}\n";
              echo "</td>\n";
              echo "<td rowspan=3>\n";
              echo "${"screw".$j}\n";
              echo "</td>\n";
            }elseif($i==2){
              echo "<td>\n";
              echo "<input type='tel' style='width:70px' required name=inspection_pickup_speed>\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_2".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_2".$j}\n";
              echo "</td>\n";
/*
              echo "<td>\n";
              echo "${"screw_2".$j}\n";
              echo "</td>\n";
*/
            }else{
              echo "<td>\n";
              echo "± 1.0\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_3".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_3".$j}\n";
              echo "</td>\n";
/*
              echo "<td>\n";
              echo "${"screw_3".$j}\n";
              echo "</td>\n";
*/
            }
        }else{
          if($i==1){
            echo "<td style='border-bottom-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_1".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_1".$j}\n";
            echo "</td>\n";
            echo "<td rowspan=3>\n";
            echo "${"screw".$j}\n";
            echo "</td>\n";
          }elseif($i==2){
            echo "<td style='border-bottom-style:none; border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_2".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_2".$j}\n";
            echo "</td>\n";
/*
            echo "<td>\n";
            echo "${"screw_2".$j}\n";
            echo "</td>\n";
*/
          }else{
            echo "<td style='border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
/*
            echo "<td>\n";
            echo "${"screw_3".$j}\n";
            echo "</td>\n";
*/
          }
        }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br><br><br>
