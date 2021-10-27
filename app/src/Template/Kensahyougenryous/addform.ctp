<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrMaterial_name_list = json_encode($arrMaterial_name_list);//jsに配列を受け渡すために変換
?>

<script>

  $(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrMaterial_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#material_name_list").autocomplete({
        source: wordlist
      });
  });

</script>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/menu' /><font size='4' color=black>原料登録</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/addformpre' /><font size='4' color=black>新規登録</font></a>
    </a></td>
  </tbody>
</table>

<?php
  //   echo $htmlkensahyoukadou;
?>

<br><br><br>
<?php
  //   echo $htmlkensahyoumenu;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>

  <?php
        echo $htmlkensahyouheader;
   ?>

<table class="top">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
</table>

<table align="left">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border:none">　　　　　　　　　</td>
    <td style="border:none"><font size="4"><strong><?= __($machine_num." 号機") ?></strong></font></td>
    </tr>
  </tbody>
</table>
<table align="right">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('成形機内原料追加'), array('name' => 'genryoutuika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機追加'), array('name' => 'seikeikituika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　</td>
    </tr>
  </tbody>
</table>

<br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>

<table>
<tr class="parents">
  <td width="150">成形機</td>
  <td width="490">原料名</td>
  <td width="190">配合比</td>
  <td width="190">乾燥温度</td>
  <td width="190">乾燥時間</td>
  <td width="200">再生配合比</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

        echo "<tr class='children'>\n";
        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
        }
?>

<?php if ($i==1): ?>

    <?= $this->Form->control('cylinder_name'.$j, ['options' => $arrSeikeikis, 'value'=>${"cylinder_name".$j}, 'label'=>false]) ?>
  </td>

<?php else : ?>
<?php endif; ?>

        <?php
          echo "<td>\n";
          if(${"check_material_name".$j.$i} == 1){
            echo "${"material_name".$j.$i}\n";

            ?>

            <?= $this->Form->control('check_material_name'.$j.$i, array('type'=>'hidden', 'value'=>${"check_material_name".$j.$i}, 'label'=>false)) ?>
            <?= $this->Form->control('material_name'.$j.$i, array('type'=>'hidden', 'value'=>${"material_name".$j.$i}, 'label'=>false)) ?>
    
            <?php
    
          }else{
            echo "<input type='text' name=material_name".$j.$i." id='material_name_list' autocomplete='off' required value=${"material_name".$j.$i}>\n";
          }
          echo "</td>\n";

          echo "<td>\n";
          echo "<input type='text' name=mixing_ratio".$j.$i." autocomplete='off' required value=${"mixing_ratio".$j.$i}>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' name=dry_temp".$j.$i." autocomplete='off' required value=${"dry_temp".$j.$i}> ℃ \n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' name=dry_hour".$j.$i." autocomplete='off' required value=${"dry_hour".$j.$i}> h以上\n";
          echo "</td>\n";

          if($i==1){
            echo "<td rowspan=${"tuikagenryou".$j}>\n";
            echo "<input type='text' name=recycled_mixing_ratio".$j." autocomplete='off' required value=${"recycled_mixing_ratio".$j}>\n";
            echo "</td>\n";
            }

          echo "</tr>\n";
  
        }
 ?>

</table>

<?php endfor;?>

<br>
<table class="top">
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red">
    <?= __("※乾燥温度・乾燥時間が不要な場合は「0」を入力してください。") ?>
    </strong></td></tr>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red">
    <?= __("※配合比・再生配合比が不要な場合は「-」を入力してください。") ?>
  </strong></td></tr>
  </table>
<br><br>
