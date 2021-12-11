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
      <font size='4'>　>>　</font><a href='/Kadous/yobidashidate' /><font size='4' color=black>稼働関係</font></a>
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

<?= $this->Form->create($product, ['url' => ['action' => 'view']]) ?>
        <?php if ($usercheck == 1): ?>
          <table>
          <tr class="parents">
            <td width="200"><strong>工場名</strong></td>
        	</tr>
          <tr style="background-color: #FFFFCC">
            <td><?= $this->Form->control('factory_id', ['options' => $Factories, 'label'=>false]) ?></td>
        	</tr>
        </table>
        <br>
        <br>

 <?php else : ?>
<?php endif; ?>

    <table>
  <tbody style="background-color: #FFFFCC">
    <tr class="parents">
    <td width="250" colspan='3'><strong>日報呼出日</strong></td>
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
