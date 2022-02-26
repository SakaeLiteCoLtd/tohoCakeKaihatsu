<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<br>
<table class='sample hesdermenu'>
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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrProduct_name_list = json_encode($arrProduct_name_list);//jsに配列を受け渡すために変換
?>

<script>
  $(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrProduct_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#product_name_list").autocomplete({
        source: wordlist
      });
  });
</script>

<?php
      $dayyetoy = date('Y');
      $dayyetom = date('n');
      $dayyetod = date('j');
?>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'view']]) ?>

<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>

<br>
<div align="center"><font size="3"><?= __("日報呼出日以外は空のまま呼出できます") ?></font></div>
<div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
<br>

    <table>
  <tbody style="background-color: #FFFFCC">
    <tr class="parents">
    <td width="100"><strong>ライン</strong></td>
    <td width="300"><strong>製品名</strong></td>
    <td width="500" colspan='7'><strong>日報呼出日</strong></td>
    </tr>
    <tr>
    <td><?= $this->Form->control('machine_num', ['options' => $arrGouki, 'label'=>false]) ?></td>
    <td><?= $this->Form->control('product_name', array('type'=>'text', 'label'=>false, 'id'=>"product_name_list")) ?></td>
    <td style="border-right-style: none;border-width: 1px">
    <div align="center">
         <?= $this->Form->input("date_sta_year", array('type' => 'select', "options"=>$arrYears, 'value' => $dayyetoy, 'label'=>false)); ?>
     </div></td>
  <td style="border-right-style: none;border-left-style: none;border-width: 1px">
  <div align="center">
       <?= $this->Form->input("date_sta_month", array('type' => 'select', "options"=>$arrMonths, 'value' => $dayyetom, 'monthNames' => false, 'label'=>false)); ?>
     </div></td>
     <td style="border-right-style: none;border-left-style: none;border-width: 1px">
  <div align="center">
       <?= $this->Form->input("date_sta_date", array('type' => 'select', "options"=>$arrDays, 'value' => $dayyetod, 'label'=>false)); ?>
     </div></td>
     <td style="border-right-style: none;border-left-style: none;border-width: 1px">
    <div align="center">
    <?= __('～') ?>
     </div></td>
     <td style="border-right-style: none;border-left-style: none;border-width: 1px">
    <div align="center">
         <?= $this->Form->input("date_sta_year_fin", array('type' => 'select', "options"=>$arrYearsfin, 'label'=>false)); ?>
     </div></td>
  <td style="border-right-style: none;border-left-style: none;border-width: 1px">
  <div align="center">
       <?= $this->Form->input("date_sta_month_fin", array('type' => 'select', "options"=>$arrMonthsfin, 'monthNames' => false, 'label'=>false)); ?>
     </div></td>
  <td style="border-left-style: none;border-width: 1px">
  <div align="center">
       <?= $this->Form->input("date_sta_date_fin", array('type' => 'select', "options"=>$arrDaysfin, 'label'=>false)); ?>
     </div></td>
    </tr>
  </tbody>
</table>
<br>
  <table align="center">
    <tbody class='sample non-sample'>
      <tr>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('呼出'), array('name' => 'next')) ?></td>
      </tr>
    </tbody>
  </table>
