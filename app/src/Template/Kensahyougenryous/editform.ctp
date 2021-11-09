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
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/menu' /><font size='4' color=black>成形条件</font></a>
    <font size='4'>　>>　</font><a href='/Kensahyougenryous/kensakupre' /><font size='4' color=black>登録データ呼出</font></a>
    </a></td>
  </tbody>
</table>

<?= $this->Form->create($product, ['url' => ['action' => 'editform']]) ?>

<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$user_code, 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>
<?= $this->Form->control('machine_num_moto', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<br> <br> <br>

<?php
      echo $htmlkensahyouheader;
 ?>


<br>
 <table class="top_big">
  <tbody>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('・データを編集してください。（データを全て削除する場合は「データ削除」ボタンを押してください）') ?></strong></td></tr>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('・成形機ごとデータを削除する場合は「成形機削除」にチェックを入れてください。　　　　　　　　　 ') ?></strong></td></tr>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('・原料データを削除する場合は「原料削除」にチェックを入れてください。　　　　　　　　　　　　　 ') ?></strong></td></tr>
  </tbody>
</table>
<br>

<table align="right">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('データ削除'), array('name' => 'sakujo')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機内原料追加'), array('name' => 'genryoutuika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機追加'), array('name' => 'seikeikituika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('温度条件編集へ'), array('name' => 'kakuninn')) ?></td>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　</td>
    </tr>
  </tbody>
</table>

<br><br>
<table align="left">
  <tbody>
    <tr style="background-color: #FFFFCC">
    <td style="border:none; background-color:#E6FFFF">　　　　　　　　　　</td>
      <td width="100">号機番号</td>
      <td width="50"><?= $this->Form->control('machine_num', ['options' => $arrGouki, 'value'=>$machine_num, 'label'=>false]) ?></td>
    </tr>
  </tbody>
</table>
<br><br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<?= $this->Form->control('tuikagenryou'.$j, array('type'=>'hidden', 'value'=>${"tuikagenryou".$j}, 'label'=>false)) ?>

<table>
  <tr class="parents">
  <td width="50"><font size='2'>成形機<br>削除</td>
  <td width="150">成形機</td>
  <td width="50"><font size='2'>原料<br>削除</td>
  <td width="450" colspan='2'>原料名</td>
  <td width="150">配合比</td>
  <td width="170">乾燥温度</td>
  <td width="170">乾燥時間</td>
  <td width="200">再生配合比</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

     echo "<tr class='children'>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo "<input type='checkbox' name=delete_seikeiki".$j.">\n";
          echo "</td>\n";

          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          //rowspanとプルダウンの併用テクニック
          ?>
            <?= $this->Form->control('cylinder_name'.$j, ['options' => $arrSeikeikis, 'value'=>${"cylinder_name".$j}, 'label'=>false]) ?>
          <?php
/*
          echo "<td rowspan=${"tuikagenryou".$j}><select name=cylinder_name".$j." required value=${"cylinder_name".$j}>\n";
          foreach ($arrSeikeikis as $key => $value){
            echo "<option value=$value>$value</option>";
          }
          echo "</select></td>\n";
*/
        }
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='checkbox' name=delete_genryou".$j.$i.">\n";
        echo "</td>\n";

        if(${"check_material".$j.$i} == 1){

        echo "<td style='border-right-style:none'>${"material_name".$j.$i}</td>\n";//

        ?>
        <td style='border-left-style:none'><?= $this->Form->submit(('変更'), array('name' => 'henkou'.$j.$i)) ?></td>
        <?= $this->Form->control('material_name'.$j.$i, array('type'=>'hidden', 'value'=>${"material_name".$j.$i}, 'label'=>false)) ?>
        <?php
        
        }else{

          echo "<td colspan='2'>変更前：${"material_name".$j.$i}<br>\n";//
          echo "<input type='text' required name=material_name".$j.$i." id='material_name_list'>\n";//
          echo "</td>\n";//

          }

        echo "<td>\n";
        echo "<input type='text' required name=mixing_ratio".$j.$i." value=${"mixing_ratio".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=dry_temp".$j.$i." value=${"dry_temp".$j.$i} > ℃ \n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text' style='width:60px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=dry_hour".$j.$i." value=${"dry_hour".$j.$i} > h以上\n";
        echo "</td>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo "<input type='text' required name=recycled_mixing_ratio".$j.$i." value=${"recycled_mixing_ratio".$j.$i} >\n";
          echo "</td>\n";
          }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br>
<table>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>
<br><br>
