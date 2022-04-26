<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
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
$dateYMD = date('Y-m-d');
$dateYMD1 = strtotime($dateYMD);
$dayye = date('Y-m-d', strtotime('-1 day', $dateYMD1));
?>

<?= $this->Form->create($product, ['url' => ['action' => 'kikakugaiichiran']]) ?>

<br>
<div align="center"><strong><font color="blue"><?php echo "規格外データ一覧";?></font></strong></div>
<br>
<div align="center"><font size="3"><?= __($mes) ?></font></div>
<br>
<table>
        <thead>
            <tr class="parents">
            <td style='font-size: 10pt; width:140; border-width: 1px solid black;'><?= __('検査日時') ?></td>
            <td style='font-size: 9pt; width:50; height:50; border-width: 1px solid black;'><?= __('ライン') ?></td>
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('製品コード') ?></td>
            <td style='font-size: 10pt; width:60; border-width: 1px solid black;'></td>
            </tr>
        </thead>
<tbody>
<?php for($j=0; $j<count($arrDates); $j++): ?>

<tr class='children'>

  <td style='font-size: 10pt;background-color'><?= h($arrDates[$j]["datetime"]) ?></td>
  <td style='font-size: 10pt;background-color'><?= h($arrDates[$j]["machine_num"]) ?></td>
  <td style='font-size: 10pt;background-color'><?= h($arrDates[$j]["product_name"]) ?></td>
  <td style='font-size: 10pt;background-color'><?= h("　".$arrDates[$j]["product_code"]."　") ?></td>

<?php
echo "<td>\n";
?>
<?php
echo $this->Form->submit("検査表表示" , ['name' => $arrDates[$j]["code"]]) ;
?>
</td>

          </tr>
<?php endfor;?>

</table>

<br>
<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>

<br><br>
