<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();

 use Cake\ORM\TableRegistry;//独立したテーブルを扱う
 $this->Products = TableRegistry::get('Products');

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
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/kensakumenu' /><font size='4' color=black>登録データ呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?php
      echo $htmlkensahyouheader;
 ?>

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('datetimesta', array('type'=>'hidden', 'value'=>$datetimesta, 'label'=>false)) ?>
<?= $this->Form->control('datetimefin', array('type'=>'hidden', 'value'=>$datetimefin, 'label'=>false)) ?>
<?= $this->Form->control('datekensaku', array('type'=>'hidden', 'value'=>$datekensaku, 'label'=>false)) ?>

  <table class="white">

  <tr>
  <td colspan='3'>バンク：<?= h($mode) ?></td>

  <td style='width:105'>測定箇所</td>

  <?php for($i=1; $i<=11; $i++): ?>
    <td style='width:80'><?= h(${"size_name".$i}) ?></td>
  <?php endfor;?>

  <td width="60" rowspan='3'>外観</td>
  <td style='font-size: 9pt' width="60" rowspan='3'>重量<br>（目安）</td>
  <td style='font-size: 9pt' width="50" rowspan='5'>合否<br>判定</td>

</tr>
<tr>
<td style='font-size: 9pt' width="36" rowspan='8'>No.</td>
<td width="130" rowspan='7'>時間</td>
<td width="60" rowspan='6'>長さ<br>(mm)</td>

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

    <td width="60" style="font-size: 10pt">〇・✕</td>
        <td width="60">g / 本</td>

</tr>
<tr>
  <td>検査器具</td>

    <?php for($i=1; $i<=11; $i++): ?>
      <td style='font-size: 8pt'><?= h(${"measuring_instrument".$i}) ?></td>
    <?php endfor;?>

    <td width="60">目視</td>
    <td style='width:60; border-top-style:none; font-size: 8pt'>デジタル秤</td>

</tr>

</table>

<?php for($j=1; $j<=$gyou; $j++): ?>

  <table class="form">

  <?php if ($j == 1): ?>
  <td style='width:36; border-top-style:none; font-size: 11pt'>S</td>
  <?php elseif ($j == $gyou) : ?>
    <td style='width:36; border-top-style:none; font-size: 11pt'>E</td>
  <?php else : ?>
    <td style='width:36; border-top-style:none; font-size: 11pt'><?= h(${"lot_number".$j}) ?></td>
  <?php endif; ?>


  <td style='width:130; border-top-style:none; font-size: 10pt'><?= h(${"datetime".$j}) ?></td></td>

  <td style='width:60; border-top-style:none'><?= h(${"length".$j}) ?></td>

  <td style='width:105; border-top-style:none'><?= h(${"staff_hyouji".$j}) ?></td>

  <?php for($i=1; $i<=11; $i++): ?>

  <?php if (${"input_type".$i} == "judge"): ?>

      <?php
     if(${"result_size".$j."_".$i} == 0){
       ${"judge".$j."_".$i} = "〇";
     }else{
      ${"judge".$j."_".$i} = "✕";
    }
  ?>

      <?php if (${"result_size".$j."_".$i} == 1): ?>
        <td style='width:80; border-top-style:none'><font color="red"><?= h(${"judge".$j."_".$i}) ?></td>
      <?php else : ?>
        <td style='width:80; border-top-style:none'><?= h(${"judge".$j."_".$i}) ?></td>
      <?php endif; ?>

  <?php else : ?>

    <?php
  if(${"size_name".$i} == "長さ"){//長さ列の場合
    $Products= $this->Products->find()->where(['product_code like' => $product_code_ini.'%', 'length' => ${"length".$j}, 'delete_flag' => 0])->toArray();
    ${"size".$i} = $Products[0]["length_cut"];
    ${"upper_limit".$i} = $Products[0]["length_upper_limit"];
    ${"lower_limit".$i} = $Products[0]["length_lower_limit"];
  }
  ?>

      <?php if (${"result_size".$j."_".$i} <= (float)${"size".$i} + (float)${"upper_limit".$i}
      && ${"result_size".$j."_".$i} >= (float)${"size".$i} + (float)${"lower_limit".$i}): ?>
        <td style='width:80; border-top-style:none'><?= h(${"result_size".$j."_".$i}) ?></td>
      <?php else : ?>
        <td style='width:80; border-top-style:none'><font color="red"><?= h(${"result_size".$j."_".$i}) ?></td>
      <?php endif; ?>

  <?php endif; ?>

  <?php endfor;?>

  <?php
  if(${"appearance".$j} == 1){
    ${"gaikanhyouji".$j} = "✕";
  }else{
    ${"gaikanhyouji".$j} = "〇";
  }

  if(${"judge".$j} == 1){
    ${"gouhihyouji".$j} = "否";
  }else{
    ${"gouhihyouji".$j} = "合";
  }

  ?>

<?php if (${"appearance".$j} == 1): ?>
      <td style='width:60; border-top-style:none'><font color="red"><?= h(${"gaikanhyouji".$j}) ?></td>
    <?php else : ?>
      <td style='width:60; border-top-style:none'><?= h(${"gaikanhyouji".$j}) ?></td>
    <?php endif; ?>

  <td style='width:60; border-top-style:none'><?= h(${"result_weight".$j}) ?></td>
  <td style='width:50; border-top-style:none'><?= h(${"gouhihyouji".$j}) ?></td>

</table>

<?php endfor;?>

<br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
    <td width="150">長さ（mm）</td>
    <td width="150">生産数量（本）</td>
    <td width="150">総重量（kg）</td>
    <td width="150">総ロス重量（kg）</td>
    </tr>
    <?php for($k=0; $k<count($arrProducts); $k++): ?>
    <tr>
    <td><?= h($arrProducts[$k]["length"]) ?></td>
    <td><?= h($arrProducts[$k]["amount"]) ?></td>
    <td><?= h($arrProducts[$k]["sum_weight"]) ?></td>
    <td><?= h($arrProducts[$k]["total_loss_weight"]) ?></td>
    </tr>
    <?php endfor;?>
  </tbody>
</table>

<br>
<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
    <td width="400">備考</td>
    </tr>
    <tr>
   <td><?= h($arrProducts[0]["bik"]) ?></td>
    </tr>
  </tbody>
</table>
<br>

<?php
       echo $htmlgenryouheader;
  ?>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>

<table>
  <tr class="parents">
  <td style='width:80'>成形機</td>
  <td width="100">温度条件</td>
  <td style='width:70'>C １</td>
  <td style='width:70'>C ２</td>
  <td style='width:70'>C ３</td>
  <td style='width:70'>C ４</td>
  <td style='width:70'>A D</td>
  <td style='width:70'>D １</td>
  <td style='width:70'>D ２</td>
  <td style='width:226' colspan="2">押出回転(rpm)/負荷(A)</td>
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
              echo "<td rowspan=3>\n";
              echo "${"screw".$j}\n";
              echo "</td>\n";
            }elseif($i==2){
              echo "<td>\n";
              echo "${"inspection_pickup_speed".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_mesh_2".$j}\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "${"screw_number_2".$j}\n";
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
          }else{
            echo "<td style='border-top-style:none;'>\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_mesh_3".$j}\n";
            echo "</td>\n";
            echo "<td>\n";
            echo "${"screw_number_3".$j}\n";
            echo "</td>\n";
          }
        }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br>

<?php if ($account_check == 0): ?>

<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>
<br>

<?php else : ?>

  <table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      <td style="border: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('編集'), array('name' => 'hensyuu')) ?></td>
    </tr>
  </tbody>
</table>
<br>

<?php endif; ?>
