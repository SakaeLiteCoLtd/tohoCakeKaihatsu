<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 use Cake\ORM\TableRegistry;//独立したテーブルを扱う
 $this->Linenames = TableRegistry::get('Linenames');

?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<br>
<table class='sample hesdermenu' style="background-color: #E6FFFF">
  <tbody>
    <td style='border: none;align: left'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kadous/menu' /><font size='4' color=black>稼働関係</font></a>
      <font size='4'>　>>　</font><a href='/Kadous/yobidashidate' /><font size='4' color=black>日報呼出</font></a>
    </a></td>
  </tbody>
</table>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'details']]) ?>

<table align="center" style="width:1310; position: fixed; top: 0px; left: 1%;z-index: 1;">
  <tr><td style="height:250px; border-style: none;background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　　　") ?></td></tr>
</table>
  <br><br>

<table align="center" style="width:1000px; position: fixed; top: 70px; left: 50%; margin-left:-500px;z-index: 2;">
    <tbody class='sample non-sample'>
      <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('前のラインへ', array('name' => 'mae')); ?></div></td>
          <td style="border-style: none;"><?= __("　　　　　　　　　　　　　　　　　　　") ?></td>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('呼出画面へ戻る', array('name' => 'ichiran')); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('検査表表示', array('name' => 'kensahyou')); ?></div></td>
          <td style="border-style: none;"><?= __("　　　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('次のラインへ', array('name' => 'tugi')); ?></div></td>
      </tr>
    </tbody>
  </table>
  <br><br>

<?php
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id, 'machine_num' => $machine_num])->toArray();
      $name_machine = $Linenames[0]["name"];
?>

<table style="position: fixed; top: 130px; left: 7%;z-index: 1;">
  <tbody style="background-color: #FFFFCC">
  <tr class="parents">
  <td>生産時間</td>
  <td width="100">ライン</td>
    </tr>
    <tr>
    <td><?= h("　".$date_sta."　～　".$date_fin_hyouji."　") ?></td>
    <td width="100"><?= h($name_machine) ?></td>

    <td width="30" style="border-style: none;background-color: #E6FFFF"><?= h("　") ?></td>
    <td style="border-style: none;background-color: #E6FFFF"><div><?= $this->Form->submit('ショットデータCSV出力', array('name' => 'shotdata')); ?></div></td>

    </tr>
  </tbody>
</table>
<br>
<table width="1210" style="position: fixed; top: 230px; left: 7%;z-index: 1;">
<thead>
<tr class="parents">
<td style='font-size: 10pt; border-width: 1px solid black;'><?= __('製品コード') ?></td>
<td style='font-size: 10pt; border-width: 1px solid black;'><?= __('製品名') ?></td>
<td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('長さ(mm)') ?></td>
<td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('生産数量(本)') ?></td>
<td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('総重量(kg)') ?></td>
<td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('総ロス重量(kg)') ?></td>
<td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('ロス率(％)') ?></td>
<td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('達成率(％)') ?></td>
</tr>
</thead>
<tbody>
<?php for($j=0; $j<count($arrProdcts); $j++): ?>
  <?php if ($j % 2 == 0): ?>
    <tr class='children' style='background-color: #ffffe0'>
  <?php else : ?>
    <tr class='children'>
  <?php endif; ?>
  <td><?= h("　".$arrProdcts[$j]["product_code"]."　") ?></td>
  <td style='font-size: 10pt'><?= h("　".$arrProdcts[$j]["name"]."　") ?></td>
  <td><?= h($arrProdcts[$j]["length"]) ?></td>
  <td><?= h($arrProdcts[$j]["amount"]) ?></td>
  <td><?= h($arrProdcts[$j]["sum_weight"]) ?></td>
  <td><?= h($arrProdcts[$j]["total_loss_weight"]) ?></td>

  <?php if ($j == 0): ?>
      <?php
      $num = count($arrProdcts);

        echo "<td rowspan=$num style='background-color: #E9FFA5'>";
        echo "$lossritsu";
        echo "</td>";
        echo "<td rowspan=$num style='background-color: #E9FFA5'>";
        echo "$tasseiritsu";
        echo "</td>";
      ?>
    <?php else : ?>
    <?php endif; ?>


<?php endfor;?>
</tbody>
    </table>

    <br><br><br>

    <?php for($j=0; $j<count($arrProdcts); $j++): ?>
      <br><br>
    <?php endfor;?>

<?php
/*
<table width="1200" border="4" style='margin-left:100px;border-bottom-width: 0pt;' id="position">
*/
?>

<table width="1200" border="4" style='margin-left:100px;border-bottom-width: 0pt;'>
  <tr class="parents">
    <td colspan="7"><strong><?= h($name_machine."号ライン") ?></strong></td>
  </tr>
  <tr class='parents'>
    <td width="80"></td>
    <td><strong>ヒーターON時間</td>
    <td><strong>稼働開始時間</td>
    <td><strong>検査表開始時間</td>
    <td><strong>検査表終了時間</td>
    <td><strong>稼働終了時間</td>
    <td><strong>ヒーターOFF時間</td>
  </tr>
  <tr class='children' style='background-color: #ffffe0'>
    <td style="background-color: #f0e68c" width="80"><strong>日時</td>
    <td style='font-size: 11pt;'><?= h($heater_start_datetime) ?></td>
    <td style='font-size: 11pt;'><?= h($relay_start_datetime) ?></td>
    <td style='font-size: 11pt;'><?= h($start_datetime) ?></td>
    <td style='font-size: 11pt;'><?= h($finish_datetime) ?></td>
    <td style='font-size: 11pt;'><?= h($relay_finish_datetime) ?></td>
    <td style='font-size: 11pt;'><?= h($heater_finish_datetime) ?></td>
  </tr>
</table>
<table width="1200" border="4" style='margin-left:100px;border-bottom-width: 0pt;border-top-width: 0pt;'>
  <tr class='children'>
    <td style="background-color: #f0e68c; border-top:none" width="80"><strong>間隔</td>
    <td style="border-top:none"></td>
    <td style="background-color: #ffc0cb;border-top:none" width="176"><?= h($interval_heater_relay_on) ?> 分</td>
    <td style="background-color: #ffc0cb;border-top:none" width="176"><?= h($interval_relay_start) ?> 分</td>
    <td style="background-color: #ffc0cb;border-top:none" width="176"><?= h($interval_start_finish) ?> 分</td>
    <td style="background-color: #ffc0cb;border-top:none" width="176"><?= h($interval_finish_relay) ?> 分</td>
    <td style="background-color: #ffc0cb;border-top:none" width="176"><?= h($interval_relay_heater_off) ?> 分</td>
    <td style="border-top:none"></td>
  </tr>
</table>
<table width="1200" border="4" style='margin-left:100px;border-top-width: 0pt;'>
  <tr class='children' style='background-color: #ffffe0'>
    <td style="background-color: #f0e68c; border-top:none" width="80"><strong>備考</td>
    <td style="border-top:none" colspan="6" align="left"><?= h("　".$bik) ?></td>
  </tr>
</table>

<br>

<table width="580" style='margin-left:100px'>
  <tr class="parents">
    <td width="80"></td>
    <td colspan="5">稼働開始 ～ 稼働終了</td>
  </tr>
  <tr class='parents'>
    <td width="80"></td>
    <td width="250">切断長</td>
    <td width="250">本数</td>
    <td width="250">メートル換算</td>
    <td width="250">合計長さ</td>
    <td width="250">総ロス率</td>
  </tr>

  <?php for($j=0; $j<count($arrlength); $j++): ?>

    <?php if ($j == 0): ?>
      <tr class='children' style='background-color: #FFFF99'>

      <?php
        $num = count($arrlength);
        echo "<td width='80' rowspan=$num>\n";
        echo "理論\n";
        echo "</td>\n";
        ?>

        <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
        <td><?= h(${"riron_amount".$arrlength[$j]["product_id"]}) ?>(本)</td>
        <td><?= h(${"riron_total_length_".$arrlength[$j]["product_id"]}) ?>(m)</td>

        <?php
        $num = count($arrlength);
        echo "<td width='80' rowspan=$num>\n";
        echo "$total_length_riron\n";
        echo "(m)</td>\n";
        echo "<td width='80' rowspan=$num>\n";
        echo "$riron_lossritsu\n";
        echo "(%)</td>\n";
        ?>

      </tr>
    <?php else : ?>
      <tr class='children' style='background-color: #FFFF99'>
      <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
      <td><?= h(${"riron_amount".$arrlength[$j]["product_id"]}) ?>(本)</td>
      <td><?= h(${"riron_total_length_".$arrlength[$j]["product_id"]}) ?>(m)</td>
    </tr>
    <?php endif; ?>

  <?php endfor;?>

  <?php for($j=0; $j<count($arrlength); $j++): ?>

    <?php if ($j == 0): ?>
      <tr class='children' style='background-color: #E9FFA5'>

        <?php
        $num = count($arrlength);
        echo "<td width='80' rowspan=$num>\n";
        echo "実数\n";
        echo "</td>\n";
        ?>
        <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
        <td><?= h(${"amount".$arrlength[$j]["product_id"]}) ?>(本)</td>
        <td><?= h(${"total_length_".$arrlength[$j]["product_id"]}) ?>(m)</td>

      <?php
      $num = count($arrlength);
      echo "<td width='80' rowspan=$num>\n";
      echo "$total_length_jissai\n";
      echo "(m)</td>\n";
      echo "<td width='80' rowspan=$num>\n";
      echo "$lossritsu\n";
      echo "(%)</td>\n";
      ?>

      </tr>
    <?php else : ?>
      <tr class='children' style='background-color: #E9FFA5'>
      <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
      <td><?= h(${"amount".$arrlength[$j]["product_id"]}) ?>(本)</td>
      <td><?= h(${"total_length_".$arrlength[$j]["product_id"]}) ?>(m)</td>
      </tr>
    <?php endif; ?>

  <?php endfor;?>

  </table>

  <br>

  <table width="580" style='margin-left:100px'>
  <tr class="parents">
    <td width="80"></td>
    <td colspan="5">検査開始 ～ 検査終了</td>
  </tr>
  <tr class='parents'>
    <td width="80"></td>
    <td width="250">切断長</td>
    <td width="250">本数</td>
    <td width="250">メートル換算</td>
    <td width="250">合計長さ</td>
    <td width="250">中間ロス率</td>
  </tr>

  <?php for($j=0; $j<count($arrlength); $j++): ?>

    <?php if ($j == 0): ?>
      <tr class='children' style='background-color: #FFFF99'>

      <?php
        $num = count($arrlength);
        echo "<td width='80' rowspan=$num>\n";
        echo "理論\n";
        echo "</td>\n";
        ?>

        <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
        <td><?= h(${"riron_amount_kensatyu".$arrlength[$j]["product_id"]}) ?>(本)</td>
        <td><?= h(${"riron_total_length_kensatyu_".$arrlength[$j]["product_id"]}) ?>(m)</td>

        <?php
        $num = count($arrlength);
        echo "<td width='80' rowspan=$num>\n";
        echo "$total_length_kensa_riron\n";
        echo "(m)</td>\n";
        echo "<td width='80' rowspan=$num>\n";
        echo "$kensa_riron_lossritsu\n";
        echo "(%)</td>\n";
        ?>

      </tr>
    <?php else : ?>
      <tr class='children' style='background-color: #FFFF99'>
      <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
      <td><?= h(${"riron_amount_kensatyu".$arrlength[$j]["product_id"]}) ?>(本)</td>
      <td><?= h(${"riron_total_length_kensatyu_".$arrlength[$j]["product_id"]}) ?>(m)</td>
    </tr>
    <?php endif; ?>

  <?php endfor;?>

  <?php for($j=0; $j<count($arrlength); $j++): ?>

    <?php if ($j == 0): ?>
      <tr class='children' style='background-color: #E9FFA5'>

        <?php
        $num = count($arrlength);
        echo "<td width='80' rowspan=$num>\n";
        echo "実数\n";
        echo "</td>\n";
        ?>
        <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
        <td><?= h(${"amount".$arrlength[$j]["product_id"]}) ?>(本)</td>
        <td><?= h(${"total_length_".$arrlength[$j]["product_id"]}) ?>(m)</td>

      <?php
      $num = count($arrlength);
      echo "<td width='80' rowspan=$num>\n";
      echo "$total_length_jissai\n";
      echo "(m)</td>\n";
      echo "<td width='80' rowspan=$num>\n";
      echo "$mid_lossritsu\n";
      echo "(%)</td>\n";
      ?>

      </tr>
    <?php else : ?>
      <tr class='children' style='background-color: #E9FFA5'>
      <td><?= h($arrlength[$j]["length_cut"]) ?>(mm)</td>
      <td><?= h(${"amount".$arrlength[$j]["product_id"]}) ?>(本)</td>
      <td><?= h(${"total_length_".$arrlength[$j]["product_id"]}) ?>(m)</td>
      </tr>
    <?php endif; ?>

  <?php endfor;?>

  </table>

  <br><br>

<legend style='margin-left:100px'><strong style="font-size: 12pt; color:blue"><?= __('工程異常一覧') ?></strong></legend>
<br>
<table style='margin-left:100px'>
<thead>
            <tr class="parents">
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('　時間　') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('長さ(mm)') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('ロス重量(kg)') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('報告者') ?></td>
            <td style='font-size: 10pt; width:300; border-width: 1px solid black;'><?= __('備考') ?></td>
            </tr>
        </thead>
<tbody>
<?php for($j=0; $j<count($arrIjous); $j++): ?>

  <?php if ($j % 2 == 0): ?>
    <tr class='children' style='background-color: #ffffe0'>
  <?php else : ?>
    <tr class='children'>
  <?php endif; ?>

  <td style='font-size: 10pt'><?= h("　".$arrIjous[$j]["datetime"]."　") ?></td>
  <td><?= h("　".$arrIjous[$j]["length"]."　") ?></td>
  <td><?= h("　".$arrIjous[$j]["loss_amount"]."　") ?></td>
  <td style='font-size: 10pt'><?= h("　".$arrIjous[$j]["staff_name"]."　") ?></td>
  <td style='text-align: left;font-size: 10pt'><?= h("　".$arrIjous[$j]["bik"]) ?></td>
<?php endfor;?>
</tbody>
    </table>

    <br><br>

<legend style='margin-left:100px'><strong style="font-size: 12pt; color:blue"><?= __('リレーログ一覧') ?></strong></legend>
<br>
<table style='margin-left:100px'>
<thead>
            <tr class="parents">
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('　時間　') ?></td>
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('　リレー名　') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('ステイタス') ?></td>
            </tr>
        </thead>
<tbody>
<?php for($j=0; $j<count($arrRelayLogs); $j++): ?>
  <?php if ($j % 2 == 0): ?>
    <tr class='children' style='background-color: #ffffe0'>
  <?php else : ?>
    <tr class='children'>
  <?php endif; ?>
  <td style='font-size: 10pt'><?= h("　".$arrRelayLogs[$j]["datetime"]."　") ?></td>
  <td><?= h("　".$arrRelayLogs[$j]["name"]."　") ?></td>
  <td><?= h("　".$arrRelayLogs[$j]["status"]."　") ?></td>
<?php endfor;?>
</tbody>
    </table>
    <br><br>

  <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
  <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
  <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
  <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
  <?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
  <?= $this->Form->control('target_num', array('type'=>'hidden', 'value'=>$target_num, 'label'=>false)) ?>

<?php for($j=0; $j<$num_max; $j++): ?>
  <?= $this->Form->control('machine_num'.$j, array('type'=>'hidden', 'value'=>$this->request->getData('machine_num'.$j), 'label'=>false)) ?>
  <?= $this->Form->control('product_code'.$j, array('type'=>'hidden', 'value'=>$this->request->getData('product_code'.$j), 'label'=>false)) ?>
  <?= $this->Form->control('start_datetime'.$j, array('type'=>'hidden', 'value'=>$this->request->getData('start_datetime'.$j), 'label'=>false)) ?>
  <?= $this->Form->control('num'.$j, array('type'=>'hidden', 'value'=>$this->request->getData('num'.$j), 'label'=>false)) ?>
  <?= $this->Form->control('num_max', array('type'=>'hidden', 'value'=>$num_max, 'label'=>false)) ?>
<?php endfor;?>

<br><br>
<br><br>

<script type="text/javascript">
var adjust = 400;//初期表示位置の微調整
$(function() {
  $('html,body').animate({scrollTop:$('#position').offset().top - adjust }, 0, 'swing');
});
</script>
