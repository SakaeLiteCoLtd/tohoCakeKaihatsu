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
      <font size='4'>　>>　</font><a href='/RelayLogs/yobidashidate' /><font size='4' color=black>リレーログ呼出</font></a>
    </a></td>
  </tbody>
</table>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<?php
      $dayyetoy = date('Y');
      $dayyetom = date('n');
      $dayyetod = date('j');
?>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'details']]) ?>

<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>1, 'label'=>false)) ?>

    <table>
  <tbody style="background-color: #FFFFCC">
    <tr class="parents">
    <td width="250" colspan='3'><strong>リレーログ呼出日</strong></td>
    <td width="100" colspan='3'><strong>ライン</strong></td>
    </tr>
    <tr>
    <td style="border-right-style: none;border-width: 1px">
    <div align="center">
         <?= $this->Form->input("date_sta_year", array('type' => 'select', "options"=>$arrYears, 'value' => $dayyetoy, 'label'=>false)); ?>
     </div></td>
  <td style="border-right-style: none;border-left-style: none;border-width: 1px">
  <div align="center">
       <?= $this->Form->input("date_sta_month", array('type' => 'select', "options"=>$arrMonths, 'value' => $dayyetom, 'monthNames' => false, 'label'=>false)); ?>
     </div></td>
  <td style="border-left-style: none;border-width: 1px">
  <div align="center">
       <?= $this->Form->input("date_sta_date", array('type' => 'select', "options"=>$arrDays, 'value' => $dayyetod, 'label'=>false)); ?>
     </div></td>

     <td><?= $this->Form->control('machine_num', ['options' => $arrGouki, 'label'=>false]) ?></td>

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
