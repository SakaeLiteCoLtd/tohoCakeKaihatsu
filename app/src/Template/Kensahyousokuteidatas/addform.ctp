<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
use Cake\ORM\TableRegistry;//独立したテーブルを扱う
$this->Products = TableRegistry::get('Products');
$this->Users = TableRegistry::get('Users');
$this->Staffs = TableRegistry::get('Staffs');
?>

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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

function showClock1() {

var nowTime = new Date(); //  現在日時を得る
var nowHour = nowTime.getHours(); // 時を抜き出す
var nowMin  = nowTime.getMinutes(); // 分を抜き出す
if(nowHour < 10) { nowHour = "0" + nowHour; }
if(nowMin < 10) { nowMin = "0" + nowMin; }

var msg = nowHour + ":" + nowMin;
document.getElementById("RealtimeClockArea").innerHTML = msg;

}
setInterval('showClock1()',1000);

</script>

<?php

$j = 1;
$num_length = json_encode($num_length);//jsに配列を受け渡すために変換
$count_length = json_encode($count_length);//jsに配列を受け渡すために変換
$nagasa = json_encode($nagasa);//jsに配列を受け渡すために変換
$haihun = json_encode($haihun);//jsに配列を受け渡すために変換

for($i=0; $i<$count_length; $i++){
  ${"Length_product_id".$i} = json_encode(${"arrLength_size".$i}['product_id']);//jsに配列を受け渡すために変換
  ${"Length_size".$i} = json_encode(${"arrLength_size".$i}['size']);//jsに配列を受け渡すために変換
  ${"Length_upper".$i} = json_encode(${"arrLength_size".$i}['upper']);//jsに配列を受け渡すために変換
  ${"Length_lower".$i} = json_encode(${"arrLength_size".$i}['lower']);//jsに配列を受け渡すために変換
  ${"Length_measuring_instrument".$i} = json_encode(${"arrLength_size".$i}['length_measuring_instrument']);//jsに配列を受け渡すために変換
}

?>

<?php for($i=0; $i<=11; $i++): ?>

<script>

    $(document).ready(function() {
      $("#auto1").focusout(function() {
        var inputNumber = $("#auto1").val();
            
        if (inputNumber == <?php echo ${"Length_product_id".$i}; ?>) {

          var size_length = <?php echo ${"Length_size".$i}; ?>;
          var upper_length = <?php echo ${"Length_upper".$i}; ?>;
          var lower_length = <?php echo ${"Length_lower".$i}; ?>;
          var length_measuring_instrument = <?php echo ${"Length_measuring_instrument".$i}; ?>;
          var nagasa = <?php echo $nagasa; ?>;
          var haihun = <?php echo $haihun; ?>;

          $("#size<?php echo $num_length; ?>").text(size_length);
          $("#upper<?php echo $num_length; ?>").text(upper_length);
          $("#lower<?php echo $num_length; ?>").text(lower_length);
          $("#measuring_instrument<?php echo $num_length; ?>").text(length_measuring_instrument);

        }

    })

});

</script>

<?php endfor;?>

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

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('product_condition_parent_id', array('type'=>'hidden', 'value'=>$product_condition_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('inspection_data_conditon_parent_id', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id, 'label'=>false)) ?>
<?= $this->Form->control('inspection_data_conditon_parent_id_moto', array('type'=>'hidden', 'value'=>$inspection_data_conditon_parent_id_moto, 'label'=>false)) ?>
<?= $this->Form->control('count_seikeijouken', array('type'=>'hidden', 'value'=>$count_seikeijouken, 'label'=>false)) ?>
<?= $this->Form->control('countseikeiki', array('type'=>'hidden', 'value'=>$countseikeiki, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('mikan_check', array('type'=>'hidden', 'value'=>$mikan_check, 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>

<?php for($j=1; $j<$gyou; $j++): ?>

<?= $this->Form->control('lossflag'.$j, array('type'=>'hidden', 'value'=>${"lossflag".$j}, 'label'=>false)) ?>

<?php endfor;?>

<?php if ($kaishi_loss_input > 0)://開始ロス入力の場合 ?>

  <br>
 <div align="center"><font size="3"><?= __("ロス重量と備考を入力してください。") ?></font></div>
<br>
<table align="center">
  <tbody class="login">
    <tr height="45">
      <td width="150"><strong>ロス重量（kg）</strong></td>
    </tr>
    <tr height="45">
    <td class="login" width="200"><?= $this->Form->control('kaishi_loss_amount', array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9A-Za-z.-]+$', 'title'=>'半角数字で入力して下さい。')) ?></td>
    </tr>
    </tbody>
</table>
<br>

    <table align="center">
    <tbody class="login">
    <tr height="45">
    <td width="150"><strong>備考（※任意入力）</strong></td>
    </tr>
    <tr height="45">
    <td height="120" colspan="28" style="vertical-align: top; border-bottom: solid;border-width: 1px;text-align: left">
              <textarea name="kaishi_bik"  cols="120" rows="10"></textarea>
          </td>
    </tr>
    </tbody>
</table>
<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録'), array('name' => 'kaishilosstouroku')) ?></td>
    </tr>
  </tbody>
</table>

<?php elseif ($checkloss > 0)://ロス入力の場合 ?>

  <?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>

<br>
 <div align="center"><font size="3"><?= __("ロス重量と備考を入力してください。") ?></font></div>
<br>
<table align="center">
  <tbody class="login">
    <tr height="45">
      <td width="150"><strong>ロス重量（kg）</strong></td>
    </tr>
    <tr height="45">
    <td class="login" width="200"><?= $this->Form->control('loss_amount', array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9A-Za-z.-]+$', 'title'=>'半角数字で入力して下さい。', 'value'=>$loss_amount_moto)) ?></td>
    </tr>
    </tbody>
</table>
<br>

    <table align="center">
    <tbody class="login">
    <tr height="45">
    <td width="150"><strong>備考（※任意入力）</strong></td>
    </tr>
    <tr height="45">
    <td height="120" colspan="28" style="vertical-align: top; border-bottom: solid;border-width: 1px;text-align: left">
              <textarea name="bik"  cols="120" rows="10"><?php echo $bik_moto;?></textarea>
          </td>
    </tr>
    </tbody>
</table>
<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録'), array('name' => 'losstouroku')) ?></td>
    </tr>
  </tbody>
</table>

<?= $this->Form->control('checkloss', array('type'=>'hidden', 'value'=>$checkloss, 'label'=>false)) ?>

<?php for($k=1; $k<=$gyou; $k++): ?>

<?php
   $j = $gyou + 1 - $k;
?>

<?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
<?= $this->Form->control('datetime'.$j, array('type'=>'hidden', 'value'=>${"datetime".$j}, 'label'=>false)) ?>
<?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

<?= $this->Form->control('product_id'.$j, array('type'=>'hidden', 'value'=>${"product_id".$j}, 'label'=>false)) ?>
<?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"gaikan".$j}, 'label'=>false)) ?>
<?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"weight".$j}, 'label'=>false)) ?>
<?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"gouhi".$j}, 'label'=>false)) ?>

  <?php for($i=1; $i<=11; $i++): ?>
    <?= $this->Form->control("result_size".$j."_".$i, array('type'=>'hidden', 'value'=>${"result_size".$j."_".$i}, 'label'=>false)) ?>
  <?php endfor;?>

<?php endfor;?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>

<?= $this->Form->control('product_conditon_child_id'.$j, array('type'=>'hidden', 'value'=>${"product_conditon_child_id".$j}, 'label'=>false)) ?>

<?php endfor;?>

<?php else ://ロスでない場合ここから ?>

<?php
      echo $htmlkensahyouheader;
 ?>

<table class="white">

<tr>
    <td colspan='3'>バンク：<?= h($mode) ?></td>
  <td style='width:107'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <?php if ($i == $num_length + 1): ?>
    <td style='width:75'><div class="length"></div><?= h("長さ") ?></td>
    <?php else : ?>
      <td style='width:75'><div class="length"></div><?= h(${"size_name".$i}) ?></td>
      <?php endif; ?>

  <?php endfor;?>

  <td width="55" rowspan='3'>外観</td>
  <td width="75" rowspan='3'>重量<br>（目安）</td>
  <td style='font-size: 11pt' width="35" rowspan='5'>合<br>否<br>判<br>定</td>
  <td style='font-size: 11pt' width="37" rowspan='5'>登<br>録<br>・<br>修<br>正</td>
  <td style='font-size: 11pt' width="37" rowspan='5'>異<br>常<br>登<br>録</td>

</tr>
<tr>

    <td style='font-size: 11pt' width="37" rowspan='8'>No.</td>
    <td width="85" rowspan='7'>時間</td>
  <td width="82" rowspan='6'><strong><font size="3">長さ<br>(mm)</font></strong></td>

  <td>規格</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <?php if (${"input_type".$i} == "judge"): ?>
        <td style='font-size: 8pt'><div class="size"></div><?= h(${"size".$i}) ?></td>
    <?php else : ?>
      <td><div class="size"></div><?= h(${"size".$i}) ?></td>
      <?php endif; ?>
    <?php endfor;?>

</tr>
<tr>
  <td>公差上限</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <?php if (strlen(${"upper_limit".$i}) > 0 && substr(${"upper_limit".$i}, 0, 1) != "+" && ${"input_type".$i} !== "judge"): ?>
    <td><div class="upper"></div><?= h("+".${"upper_limit".$i}) ?></td>
    <?php elseif (${"input_type".$i} == "judge"): ?>
        <td style='font-size: 8pt'><div class="upper"></div><?= h(${"upper_limit".$i}) ?></td>
    <?php else : ?>
      <td><div class="upper"></div><?= h(${"upper_limit".$i}) ?></td>
      <?php endif; ?>
  <?php endfor;?>

</tr>
<tr>
  <td>公差下限</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <?php if (${"input_type".$i} == "judge"): ?>
        <td style='font-size: 8pt'><div class="lower"></div><?= h(${"lower_limit".$i}) ?></td>
    <?php else : ?>
      <td><div class="lower"></div><?= h(${"lower_limit".$i}) ?></td>
      <?php endif; ?>
    <?php endfor;?>

    <td width="55" style="font-size: 10pt">〇・✕</td>
        <td width="75">g / 本</td>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><div class="measuring_instrument"></div><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="55">目視</td>
    <td width="75" style="font-size: 9pt">デジタル秤</td>

</tr>

</table>

<script>
//こちらがhtmlよりもあとに来ないと反映されない
var moji = "length"
    var tmp = document.getElementsByClassName("length") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

    var moji = "size"
    var tmp = document.getElementsByClassName("size") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

    var moji = "upper"
    var tmp = document.getElementsByClassName("upper") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

    var moji = "lower"
    var tmp = document.getElementsByClassName("lower") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

    var moji = "measuring_instrument"
    var tmp = document.getElementsByClassName("measuring_instrument") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

</script>

<?php for($k=1; $k<=$gyou; $k++): ?>

  <?php
     $j = $gyou + 1 - $k;
     if($gyou - $k == 0){
       ${"lot_number".$j} = "S";
     }else{
       ${"lot_number".$j} = $gyou - $k;
     }
  ?>

  <?php if ($checkedit == 0 && $j == $gyou && $check_seikeijouken == 0)://測定データ入力部分 ?>

    <table class="form" id="position">

  <td style='width:37; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
  <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
  <td style='width:85; border-top-style:none'>
  <p id="RealtimeClockArea"></p>
  </td>
  <?= $this->Form->control('datetimenow'.$j, array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>

      <?php if ($j == 1): ?>

      <td style='width:82; border-top-style:none'><?= $this->Form->control('product_id'.$j, ['options' => $arrLength, 'label'=>false, 'autofocus'=>true, 'id'=>"auto1"]) ?></td>
      
      <?php else : ?>

        <?php
        $h = $j - 1;
          ?>

        <td style='width:82; border-top-style:none'><?= $this->Form->control('product_id'.$j, ['options' => $arrLength, 'value'=>${"product_id".$h}, 'label'=>false, 'autofocus'=>true, 'id'=>"auto1"]) ?></td>

        <?php endif; ?>

  <td style='width:107; border-top-style:none'><font size='1.8'><?= h("ユーザーID：") ?></font>
  <?= $this->Form->control('user_code'.$j, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9A-Za-z-]+$', 'title'=>'半角英数字で入力して下さい。', 'autocomplete'=>"off")) ?></td>

  <?php for($i=1; $i<=11; $i++): ?>

    <?php if (${"input_type".$i} == "judge"): ?>
      <td style='width:75; border-top-style:none'><?= $this->Form->control("result_size".$j."_".$i, ["empty"=>"-", 'options' => $arrJudge, 'label'=>false]) ?></td>
    <?php else : ?>
      <td style='width:75; border-top-style:none'><?= $this->Form->control("result_size".$j."_".$i, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'autocomplete'=>"off", "step"=>"0.01")) ?></td>
      <?php endif; ?>

  <?php endfor;?>

  <td style='width:55; border-top-style:none'><?= $this->Form->control('gaikan'.$j, ["empty"=>"-", 'options' => $arrGaikan, 'label'=>false]) ?></td>
  <td style='width:75; border-top-style:none'><?= $this->Form->control('weight'.$j, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'autocomplete'=>"off")) ?></td>
  <td style='width:35; border-top-style:none'>-</td>

  <?php if ($j == 1 && $kaishi_loss_flag == 0): ?>

  <td style='width:37; border-top-style:none'><?= $this->Form->submit(('登'), array('name' => 'tuika')) ?></td>
  <td style='width:37; border-top-style:none; background-color:gold'><?= $this->Form->submit(('異'), array('name' => 'kaishi_loss')) ?></td>

  <?= $this->Form->control('kaishi_loss_amount', array('type'=>'hidden', 'value'=>$kaishi_loss_amount, 'label'=>false)) ?>
  <?= $this->Form->control('kaishi_bik', array('type'=>'hidden', 'value'=>$kaishi_bik, 'label'=>false)) ?>

  <?php elseif ($kaishi_loss_flag == 0): ?>

<td style='width:37; border-top-style:none'><?= $this->Form->submit(('登'), array('name' => 'tuika')) ?></td>
<td style='width:37; border-top-style:none'>-</td>

<?php else : ?>

    <td style='width:37; border-top-style:none'></td>
    <td style='width:37; border-top-style:none'><?= $this->Form->submit(('異'), array('name' => 'kaishi_loss')) ?></td>
  
  <?php endif; ?>

</table>


<?php if ($gyou == 1 && $kaishi_loss_flag == 1): ?>
  <br>
   <div align="center"><font size="4"><strong style="font-size: 13pt; color:red"><?= __("※最初に開始ロスを登録してください。") ?></strong></font></div>
  <br>
<?php else: ?>
<?php endif; ?>


<?php elseif ($checkedit == 0 && $j < $gyou) ://測定データ表示部分 ?>

  <table class="white">

    <td style='width:37; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
    <td style='width:85; border-top-style:none'><?= h(${"datetime".$j}) ?></td></td>
    <td style='width:82; border-top-style:none'><?= h(${"lengthhyouji".$j}) ?></td>

    <?php

    $Users= $this->Users->find('all')->contain(["Staffs"])->where(['user_code' => ${"user_code".$j}])->toArray();
    ${"staff_name".$j} = $Users[0]["staff"]["name"];

    ?>
    <td style='width:107; border-top-style:none; font-size: 9pt'><?= h(${"staff_name".$j}) ?></td>

    <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('datetime'.$j, array('type'=>'hidden', 'value'=>${"datetime".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

    <?php for($i=1; $i<=11; $i++): ?>
      <?php

      if($i == $num_length + 1){//長さ列の場合

        $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
        ${"size".$i} = $Products[0]["length_cut"];
        ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
        ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
  
      }

      if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
      && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
        echo '<td style="width:75; border-top-style:none">';
        echo ${"result_size".$j."_".$i};
        echo '</td>';
      } elseif(${"input_type".$i} == "judge") {

        if(${"result_size".$j."_".$i} < 1){
          ${"judge".$j."_".$i} = "〇";
          echo '<td style="width:75; border-top-style:none">';
          echo ${"judge".$j."_".$i};
          echo '</td>';
        }else{
          ${"judge".$j."_".$i} = "✕";
          echo '<td style="width:75; border-top-style:none"><font color="red">';
          echo ${"judge".$j."_".$i};
          echo '</td>';
          }

      } else {
        echo '<td style="width:75; border-top-style:none"><font color="red">';
        echo ${"result_size".$j."_".$i};
        echo '</td>';
      }
      ?>

      <?= $this->Form->control("result_size".$j."_".$i, array('type'=>'hidden', 'value'=>${"result_size".$j."_".$i}, 'label'=>false)) ?>

    <?php endfor;?>

    <?php
    if(${"gaikan".$j} == 1){
      ${"gaikanhyouji".$j} = "✕";
    }else{
      ${"gaikanhyouji".$j} = "〇";
    }
    ?>

    <?php if (${"gaikan".$j} == 1): ?>
      <td style='width:55; border-top-style:none'><font color="red"><?= h(${"gaikanhyouji".$j}) ?></td>
    <?php else : ?>
      <td style='width:55; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
    <?php endif; ?>


    <td style='width:75; border-top-style:none'><?= h(${"weight".$j}) ?></td>
    <td style='width:35; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

    <?php if ($check_seikeijouken == 0)://成形条件調整中ではないとき ?>

      <td style='width:37; border-top-style:none'><?= $this->Form->submit(('修'), array('name' => 'edit'.$j)) ?></td>

      <?php if (${"lossflag".$j} > 0)://異常登録されている場合 ?>
        <td style='width:37; border-top-style:none; background-color:gold'><?= $this->Form->submit(('異'), array('name' => 'loss'.$j)) ?></td>
      <?php else : ?>
        <td style='width:37; border-top-style:none'><?= $this->Form->submit(('異'), array('name' => 'loss'.$j)) ?></td>
      <?php endif; ?>

    <?php else ://成形条件調整中 ?>

      <td style='width:37; border-top-style:none'>-</td>
      <td style='width:37; border-top-style:none'>-</td>

    <?php endif; ?>

    <?= $this->Form->control('product_id'.$j, array('type'=>'hidden', 'value'=>${"product_id".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"gaikan".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"weight".$j}, 'label'=>false)) ?>
    <?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"gouhi".$j}, 'label'=>false)) ?>

  </table>

<?php elseif ($j < $gyou) ://入力する行ではないとき ?>

  <?php if ($j == $checkedit) ://修正する行 ?>

  <table class="form" id="position">

  <td style='width:37; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
  <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
  <td style='width:85; border-top-style:none'>
  <?= $this->Form->control('datetime'.$j, array('type'=>'time', 'label'=>false)) ?>
  </td>
  <td style='width:82; border-top-style:none'><?= $this->Form->control('product_id'.$j, ['options' => $arrLength, 'label'=>false, 'autofocus'=>true, 'id'=>"auto1"]) ?></td>
  <td style='width:107; border-top-style:none'><font size='1.8'><?= h("ユーザーID：") ?></font>
  <?= $this->Form->control('user_code'.$j, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9A-Za-z-]+$', 'title'=>'半角英数字で入力して下さい。', 'required' => 'true')) ?></td>

  <?php for($i=1; $i<=11; $i++): ?>

    <?php if (${"input_type".$i} == "judge"): ?>
      <td style='width:75; border-top-style:none'><?= $this->Form->control("result_size".$j."_".$i, ['options' => $arrJudge, 'value'=>${"result_size".$j."_".$i}, 'label'=>false]) ?></td>
    <?php else : ?>
      <td style='width:75; border-top-style:none'><?= $this->Form->control("result_size".$j."_".$i, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'autocomplete'=>"off", "step"=>"0.01")) ?></td>
      <?php endif; ?>

      <?php endfor;?>

  <td style='width:55; border-top-style:none'><?= $this->Form->control('gaikan'.$j, ['options' => $arrGaikan, 'value'=>${"gaikan".$j}, 'label'=>false, 'required'=>true]) ?></td>
  <td style='width:75; border-top-style:none'><?= $this->Form->control('weight'.$j, array('type'=>'tel', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'required' => 'true')) ?></td>
  <td style='width:35; border-top-style:none'>-</td>

  <td style='width:37; border-top-style:none'><?= $this->Form->submit(('決'), array('name' => 'edittouroku')) ?></td>
  <td style='width:37; border-top-style:none'>-</td>

</table>

  <?php else : //修正でない行の時?>

    <table class="white">

      <td style='width:37; border-top-style:none'><?= h(${"lot_number".$j}) ?></td>
      <td style='width:85; border-top-style:none'><?= h(${"datetime".$j}) ?></td></td>
      <td style='width:82; border-top-style:none'><?= h(${"lengthhyouji".$j}) ?></td>
      <td style='width:107; border-top-style:none'><?= h(${"user_code".$j}) ?></td>

      <?= $this->Form->control('lot_number'.$j, array('type'=>'hidden', 'value'=>${"lot_number".$j}, 'label'=>false)) ?>
      <?= $this->Form->control('datetime'.$j, array('type'=>'hidden', 'value'=>${"datetime".$j}, 'label'=>false)) ?>
      <?= $this->Form->control('user_code'.$j, array('type'=>'hidden', 'value'=>${"user_code".$j}, 'label'=>false)) ?>

      <?php for($i=1; $i<=11; $i++): ?>
        <?php

        if($i == $num_length + 1){//長さ列の場合

          $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"lengthhyouji".$j}, 'delete_flag' => 0])->toArray();
          ${"size".$i} = $Products[0]["length_cut"];
          ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
          ${"lower_limit".$i} = $Products[0]["length_lower_limit"];

        }

        if(${"input_type".$i} !== "judge" && ${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
        && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}){
          echo '<td style="width:75; border-top-style:none">';
          echo ${"result_size".$j."_".$i};
          echo '</td>';
        } elseif(${"input_type".$i} == "judge") {
  
          if(${"result_size".$j."_".$i} < 1){
            ${"judge".$j."_".$i} = "〇";
            echo '<td style="width:75; border-top-style:none">';
            echo ${"judge".$j."_".$i};
            echo '</td>';
          }else{
            ${"judge".$j."_".$i} = "✕";
            echo '<td style="width:75; border-top-style:none"><font color="red">';
            echo ${"judge".$j."_".$i};
            echo '</td>';
            }
  
        } else {
          echo '<td style="width:75; border-top-style:none"><font color="red">';
          echo ${"result_size".$j."_".$i};
          echo '</td>';
        }
          ?>

        <?= $this->Form->control("result_size".$j."_".$i, array('type'=>'hidden', 'value'=>${"result_size".$j."_".$i}, 'label'=>false)) ?>

      <?php endfor;?>

      <?php

      if(${"gaikan".$j} == 1){
        ${"gaikanhyouji".$j} = "✕";
      }else{
        ${"gaikanhyouji".$j} = "〇";
      }
        ?>

      <td style='width:55; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
      <td style='width:75; border-top-style:none'><?= h(${"weight".$j}) ?></td>
      <td style='width:35; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>
      <td style='width:37; border-top-style:none'></td>
      <td style='width:37; border-top-style:none'></td>

      <?= $this->Form->control('product_id'.$j, array('type'=>'hidden', 'value'=>${"product_id".$j}, 'label'=>false)) ?>
      <?= $this->Form->control('gaikan'.$j, array('type'=>'hidden', 'value'=>${"gaikan".$j}, 'label'=>false)) ?>
      <?= $this->Form->control('weight'.$j, array('type'=>'hidden', 'value'=>${"weight".$j}, 'label'=>false)) ?>
      <?= $this->Form->control('gouhi'.$j, array('type'=>'hidden', 'value'=>${"gouhi".$j}, 'label'=>false)) ?>

      </table>

    <?php endif; ?>

<?php endif; ?>

<?php endfor;?>

<?= $this->Form->control('gyou', array('type'=>'hidden', 'value'=>$gyou, 'label'=>false)) ?>

 <table class="top">
   <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
   <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<?php if ($checkedit == 0 && $check_seikeijouken == 0)://修正ではなくて成形条件調整でもないとき ?>

<?php elseif($check_seikeijouken == 0) ://修正の時 ?>

<?= $this->Form->control('checkedit', array('type'=>'hidden', 'value'=>$checkedit, 'label'=>false)) ?>

<?php endif; ?>

  <br>

<table>
  <tr><td style="border:none"><strong style="font-size: 13pt"><?= __('成形条件') ?></strong></td></tr>
</table>

<?php
      echo $htmlgenryouheader;
 ?>

 <br>
  
 <?php for($j=1; $j<=$countseikeiki; $j++): ?>

   <?= $this->Form->control('product_conditon_child_id'.$j, array('type'=>'hidden', 'value'=>${"product_conditon_child_id".$j}, 'label'=>false)) ?>

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

 <?php if ($check_seikeijouken == 0)://表示のとき ?>

 <?php

    $num = 2 + $count_seikeijouken;
    for($i=1; $i<=$num; $i++){

      echo "<tr class='children'>\n";

         if($i==1){
           echo "<td rowspan=$num>\n";
           echo "${"cylinder_name".$j}\n";
           echo "</td>\n";
         }

         if($i==1){
           echo "<td style='width:50px'>\n";
           echo "基 準 値\n";
           echo "</td>\n";
         }elseif($i==2){
           echo "<td rowspan=$count_seikeijouken style='width:50px; font-size: 10pt'>\n";
           echo "記録(最新順)\n";
           echo "</td>\n";
         }

         if($i == 1){//基準値
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
         }elseif($i < $num){

          if($i == 2){

            $joukenn_num = $i - 1;
            echo "<td>\n";
            echo "${"inspection_temp_1".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"inspection_temp_2".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"inspection_temp_3".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"inspection_temp_4".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"inspection_temp_5".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"inspection_temp_6".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"inspection_temp_7".$j.$joukenn_num}\n";
            echo "</td>\n";
            echo "<td style='border-right-style:none; text-align:right'>\n";
            echo "${"inspection_extrude_roatation".$j.$joukenn_num}(rpm)\n";
            echo "</td>\n";
            echo "<td style='border-left-style:none; text-align:left'>\n";
            echo "/ ${"inspection_extrusion_load".$j.$joukenn_num}(A)\n";
            echo "</td>\n";
 
          }else{

          $joukenn_num = $i - 1;
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_1".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_2".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_3".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_4".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_5".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_6".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='background-color:#f5f5f5'>\n";
           echo "${"inspection_temp_7".$j.$joukenn_num}\n";
           echo "</td>\n";
           echo "<td style='border-right-style:none; text-align:right; background-color:#f5f5f5'>\n";
           echo "${"inspection_extrude_roatation".$j.$joukenn_num}(rpm)\n";
           echo "</td>\n";
           echo "<td style='border-left-style:none; text-align:left; background-color:#f5f5f5'>\n";
           echo "/ ${"inspection_extrusion_load".$j.$joukenn_num}(A)\n";
           echo "</td>\n";

          }

         }else{
           if($i == $num){
            echo "<td style='width:50px'>\n";
            echo "許容範囲\n";
            echo "</td>\n";
           }
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
             if($i==1){//基準値
               echo "<td>\n";
               echo "$pickup_speed\n";
               echo "</td>\n";
               echo "<td>\n";
               echo "${"screw_mesh_1".$j}\n";
               echo "</td>\n";
               echo "<td>\n";
               echo "${"screw_number_1".$j}\n";
               echo "</td>\n";
               echo "<td rowspan=$num>\n";
               echo "${"screw".$j}\n";
               echo "</td>\n";
             }else{

              if($i == 2){
                $joukenn_num = $i - 1;
                echo "<td>\n";
                echo "${"inspection_pickup_speed".$j.$joukenn_num}\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "${"screw_mesh_2".$j}\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "${"screw_number_2".$j}\n";
                echo "</td>\n";
               }elseif($i == 3){
                if($num > 3){//下線なし
                  $joukenn_num = $i - 1;
                  echo "<td style='background-color:#f5f5f5'>\n";
                  echo "${"inspection_pickup_speed".$j.$joukenn_num}\n";
                  echo "</td>\n";
                }else{
                  echo "<td style='border-top-style:none;'>\n";
                  echo "± 1.0\n";
                  echo "</td>\n";
                  }
                  echo "<td>\n";
                  echo "${"screw_mesh_3".$j}\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "${"screw_number_3".$j}\n";
                echo "</td>\n";
              }elseif($i == $num){
                echo "<td style='border-top-style:none;'>\n";
                echo "± 1.0\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "\n";
                echo "</td>\n";
              }else{
                echo "<td style='border-top-style:none;background-color:#f5f5f5'>\n";
                echo "${"inspection_pickup_speed".$j.$joukenn_num}\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "\n";
                echo "</td>\n";
                echo "<td>\n";
                echo "\n";
                echo "</td>\n";
              }

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
             echo "<td rowspan=$num>\n";
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
           }elseif($i == 3){
            if($num > 3){//下線なし
              echo "<td style='border-bottom-style:none; border-top-style:none;'>\n";
              echo "</td>\n";
              }else{
                echo "<td style='border-top-style:none;'>\n";
                echo "</td>\n";
                }
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
           }elseif($i == $num){
             echo "<td style='border-top-style:none;'>\n";
             echo "</td>\n";
             echo "<td>\n";
            echo "\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
          }else{
            echo "<td style='border-bottom-style:none; border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
          }
         }

         echo "</tr>\n";

       }
  ?>

<?php else ://条件変更のとき?>

  <?php

    $num = 2 + $count_seikeijouken;

   for($i=1; $i<=$num; $i++){

     echo "<tr class='children'>\n";

        if($i==1){
          echo "<td rowspan=$num>\n";
          echo "${"cylinder_name".$j}\n";
          echo "</td>\n";
        }

        if($i==1){
          echo "<td style='width:50px'>\n";
          echo "基 準 値\n";
          echo "</td>\n";
        }elseif($i==2){
          echo "<td rowspan=$count_seikeijouken style='width:50px; font-size: 10pt'>\n";
          echo "記録(最新順)\n";
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
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_1".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_2".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_3".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_4".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_5".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_6".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_temp_7".$j.">\n";
          echo "</td>\n";
          echo "<td style='border-right-style:none'>\n";
          echo "<input type='text' style='width:70px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_extrude_roatation".$j.">(rpm)\n";
          echo "</td>\n";
          echo "<td style='border-left-style:none'>\n";
          echo "/ <input type='text' style='width:70px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' autocomplete='off' required name=inspection_extrusion_load".$j.">(A)\n";
          echo "</td>\n";
          
        }elseif($i < $num){
          
          $joukenn_num = $i - 2;
          echo "<td>\n";
          echo "${"inspection_temp_1".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"inspection_temp_2".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"inspection_temp_3".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"inspection_temp_4".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"inspection_temp_5".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"inspection_temp_6".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "${"inspection_temp_7".$j.$joukenn_num}\n";
          echo "</td>\n";
          echo "<td style='border-right-style:none; text-align:right'>\n";
          echo "${"inspection_extrude_roatation".$j.$joukenn_num}(rpm)\n";
          echo "</td>\n";
          echo "<td style='border-left-style:none; text-align:left'>\n";
          echo "/ ${"inspection_extrusion_load".$j.$joukenn_num}(A)\n";
          echo "</td>\n";

        }else{
         
          if($i == $num){
            echo "<td style='width:50px'>\n";
            echo "許容範囲\n";
            echo "</td>\n";
           }
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
              echo "<td rowspan=$num>\n";
              echo "${"screw".$j}\n";
              echo "</td>\n";
            }elseif($i==2){
              echo "<td>\n";
              echo "<input type='text' style='width:70px' autocomplete='off' required name=inspection_pickup_speed>\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_2".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_2".$j}\n";
              echo "</td>\n";
            }elseif($i == 3){
              $joukenn_num = $i - 2;
              echo "<td>\n";
              echo "${"inspection_pickup_speed".$j.$joukenn_num}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_3".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_3".$j}\n";
              echo "</td>\n";
            }elseif($i == $num){
              echo "<td>\n";
              echo "± 1.0\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_3".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_3".$j}\n";
              echo "</td>\n";
            }else{
              echo "<td>\n";
              echo "${"inspection_pickup_speed".$j.$joukenn_num}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_3".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_3".$j}\n";
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
            echo "<td rowspan=$num>\n";
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
          }elseif($i == 3){
            echo "<td style='border-bottom-style:none; border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
          }elseif($i == $num){
            echo "<td style='border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
           }else{
            echo "<td style='border-bottom-style:none; border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "\n";
            echo "</td>\n";
          }
        }

        echo "</tr>\n";

      }
 ?>

<?php endif; ?>

 </table>

 <?php endfor;?>

 <br>

 <?php if ($check_seikeijouken == 0 && $checkedit == 0): ?>

 <table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('成形条件調整'), array('name' => 'seikei')) ?></td>
    </tr>
  </tbody>
</table>

<br><br>

<table align="center">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border:none"><?= $this->Form->submit(('検査完了'), array('name' => 'finish')) ?></td>
    </tr>
  </tbody>
</table>

<?php elseif ($checkedit == 0) : ?>

  <table align="center">
  <tbody class="login">
    <tr height="45">
      <td width="200">条件変更者ユーザーID</td>
      <td class="login" width="200"><?= $this->Form->control('user_code_henkou', array('type'=>'text', 'label'=>false, 'required'=>true, 'autocomplete'=>"off")) ?></td>
    </tr>
  </tbody>
</table>
<br>
<table align="center">
<tbody class='sample non-sample'>
  <tr>
  <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
    <td style="border:none"><?= $this->Form->submit(('成形条件調整確定'), array('name' => 'seikeikakutei')) ?></td>
  </tr>
</tbody>
</table>

<?php endif; ?>

<br><br>

<script type="text/javascript">
var adjust = 730;//初期表示位置の微調整
$(function() {
  $('html,body').animate({scrollTop:$('#position').offset().top - adjust }, 0, 'swing');
});
</script>


<?php endif;//ロスでない場合ここまで ?>
