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

<script>

function showClock1() {

var nowTime = new Date(); //  現在日時を得る
var nowHour = nowTime.getHours(); // 時を抜き出す
var nowMin  = nowTime.getMinutes(); // 分を抜き出す

var msg = nowHour + ":" + nowMin;
document.getElementById("RealtimeClockArea").innerHTML = msg;

}
setInterval('showClock1()',1000);

</script>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ登録</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/addlogin' /><font size='4' color=black>新規登録</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('product_conditon_parent_id', array('type'=>'hidden', 'value'=>$product_conditon_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('inspection_data_conditon_parent_id', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id, 'label'=>false)) ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<table class="white">

  <tr>
    <td width="50" rowspan='7'>No.</td>
  </tr>
  <tr>
    <td width="100" rowspan='6'>時間</td>
  </tr>

<tr>
  <td style='width:130'>測定箇所</td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td style='width:80'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="72" rowspan='5'>長さ</td>
  <td width="80" rowspan='3'>外観</td>
  <td width="80" rowspan='3'>重量<br>（目安）</td>
  <td width="72" rowspan='5'>合否<br>判定</td>

</tr>
<tr>
  <td>規格</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td><?= h(${"size".$i}) ?></td>
    <?php endfor;?>
</tr>
<tr>
  <td>上限</td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td><?= h(${"upper_limit".$i}) ?></td>
  <?php endfor;?>

</tr>
<tr>
  <td>下限</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td><?= h(${"lower_limit".$i}) ?></td>
    <?php endfor;?>

        <td width="80">良 ・ 不</td>
        <td width="80">g / 本</td>

</tr>
<tr>
  <td>検査機</td>

    <?php for($i=1; $i<=10; $i++): ?>
      <td><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="80">目視</td>
    <td width="80" style="font-size: 10pt">デジタル秤</td>

</tr>

</table>

<?php for($k=1; $k<=$gyou; $k++): ?>

  <?php
     $j = $gyou + 1 - $k;
     if($gyou - $k == 0){
       ${"lot_number".$j} = "S";
     }else{
       ${"lot_number".$j} = $gyou - $k;
     }
  ?>

  <?php if ($j == $gyou): ?>

    <table class="form">

  <td style='width:50; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
  <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
  <td id="RealtimeClockArea" style='width:100; border-top-style:none'>
  <?= $this->Form->control('datetime'.$j, array('type'=>'time', 'label'=>false, 'id'=>"RealtimeClockArea")) ?>
  </td>
  <td style='width:130; border-top-style:none'><font size='1.8'><?= h("社員コード：") ?></font><?= $this->Form->control('user_code'.$j, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9A-Za-z-]+$', 'title'=>'半角英数字で入力して下さい。', 'required' => 'true')) ?></td>

  <?php for($i=1; $i<=10; $i++): ?>
    <td style='width:80; border-top-style:none'><?= $this->Form->control('result_size'.$j.$i, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。')) ?></td>
  <?php endfor;?>

  <td style='width:72; border-top-style:none'><?= $this->Form->control('product_id'.$j, ['options' => $arrLength, 'label'=>false]) ?></td>
  <td style='width:80; border-top-style:none'><?= $this->Form->control('gaikan'.$j, ['options' => $arrGaikan, 'label'=>false]) ?></td>
  <td style='width:80; border-top-style:none'><?= $this->Form->control('weight'.$j, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'required' => 'true')) ?></td>
  <td style='width:72; border-top-style:none'><?= $this->Form->control('gouhi'.$j, ['options' => $arrGouhi, 'label'=>false]) ?></td>

</table>

<?php else : ?>

  <table class="white">

    <td style='width:50; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
    <td style='width:100; border-top-style:none'><?= h(${"datetime".$j}) ?></td></td>
    <td style='width:130; border-top-style:none'><?= h(${"user_code".$j}) ?></td>

    <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('datetime'.$j, array('type'=>'hidden', 'value'=>${"datetime".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

    <?php for($i=1; $i<=10; $i++): ?>
      <?php
      if(${"result_size".$j.$i} <= (int)${"size".$i} + (int)${"upper_limit".$i}
      && ${"result_size".$j.$i} >= (int)${"size".$i} + (int)${"lower_limit".$i}){
        echo '<td style="width:80; border-top-style:none">';
        echo ${"result_size".$j.$i} ;
        echo '</td>';
      } else {
        echo '<td style="width:80; border-top-style:none"><font color="red">';
        echo ${"result_size".$j.$i};
        echo '</td>';
      }
      ?>
      <?= $this->Form->control('result_size'.$j.$i, array('type'=>'hidden', 'value'=>${"result_size".$j.$i}, 'label'=>false)) ?>

    <?php endfor;?>

    <?php
    if(${"gaikan".$j} == 1){
      ${"gaikanhyouji".$j} = "不";
    }else{
      ${"gaikanhyouji".$j} = "良";
    }

    if(${"gouhi".$j} == 1){
      ${"gouhihyouji".$j} = "否";
    }else{
      ${"gouhihyouji".$j} = "合";
    }
    ?>

    <td style='width:72; border-top-style:none'><?= h(${"lengthhyouji".$j}) ?></td>
    <td style='width:80; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
    <td style='width:80; border-top-style:none'><?= h(${"weight".$j}) ?></td>
    <td style='width:72; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

    <?= $this->Form->control('product_id'.$j, array('type'=>'hidden', 'value'=>${"product_id".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"gaikan".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"weight".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"gouhi".$j}, 'label'=>false)) ?>

  </table>

<?php endif; ?>


<?php endfor;?>

<?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>

 <table class="top">
   <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
   <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('登録確認'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
<table>
  <tr><td style="border:none"><strong style="font-size: 13pt"><?= __('成形条件') ?></strong></td></tr>
</table>

<?php
      echo $htmlgenryouheader;
 ?>

 <br>

 <?php for($j=1; $j<=$countseikeiki; $j++): ?>

   <?= $this->Form->control('inspection_extrude_roatation'.$j, array('type'=>'hidden', 'value'=>${"inspection_extrude_roatation".$j}, 'label'=>false)) ?>
   <?= $this->Form->control('inspection_extrusion_load'.$j, array('type'=>'hidden', 'value'=>${"inspection_extrusion_load".$j}, 'label'=>false)) ?>
   <?= $this->Form->control('inspection_pickup_speed', array('type'=>'hidden', 'value'=>$inspection_pickup_speed, 'label'=>false)) ?>

   <?php for($n=1; $n<=7; $n++): ?>

     <?= $this->Form->control('inspection_temp_'.$n.$j, array('type'=>'hidden', 'value'=>${"inspection_temp_".$n.$j}, 'label'=>false)) ?>

   <?php endfor;?>

 <table>
   <tr class="parents">
   <td style='width:106'>成形機</td>
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
           echo "${"inspection_temp_1".$j}\n";
           echo "</td>\n";
           echo "<td>\n";
           echo "${"inspection_temp_2".$j}\n";
           echo "</td>\n";
           echo "<td>\n";
           echo "${"inspection_temp_3".$j}\n";
           echo "</td>\n";
           echo "<td>\n";
           echo "${"inspection_temp_4".$j}\n";
           echo "</td>\n";
           echo "<td>\n";
           echo "${"inspection_temp_5".$j}\n";
           echo "</td>\n";
           echo "<td>\n";
           echo "${"inspection_temp_6".$j}\n";
           echo "</td>\n";
           echo "<td>\n";
           echo "${"inspection_temp_7".$j}\n";
           echo "</td>\n";
           echo "<td style='border-right-style:none; text-align:right'>\n";
           echo "${"inspection_extrude_roatation".$j}(rpm)\n";
           echo "</td>\n";
           echo "<td style='border-left-style:none; text-align:left'>\n";
           echo "/ ${"inspection_extrusion_load".$j}(A)\n";
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
               echo "<td>\n";
               echo "${"screw_1".$j}\n";
               echo "</td>\n";
             }elseif($i==2){
               echo "<td>\n";
               echo "$inspection_pickup_speed\n";
               echo "</td>\n";
               echo "<td>\n";
               echo "${"screw_mesh_2".$j}\n";
               echo "</td>\n";
               echo "<td>\n";
               echo "${"screw_number_2".$j}\n";
               echo "</td>\n";
               echo "<td>\n";
               echo "${"screw_2".$j}\n";
               echo "</td>\n";
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
               echo "<td>\n";
               echo "${"screw_3".$j}\n";
               echo "</td>\n";
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
             echo "<td>\n";
             echo "${"screw_1".$j}\n";
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
             echo "<td>\n";
             echo "${"screw_2".$j}\n";
             echo "</td>\n";
           }else{
             echo "<td style='border-top-style:none;'>\n";
             echo "</td>\n";
             echo "<td>\n";
             echo "${"screw_mesh_3".$j}\n";
             echo "</td>\n";
             echo "<td>\n";
             echo "${"screw_number_3".$j}\n";
             echo "</td>\n";
             echo "<td>\n";
             echo "${"screw_3".$j}\n";
             echo "</td>\n";
           }
         }

         echo "</tr>\n";

       }
  ?>
 </table>

 <?php endfor;?>
<br><br>
