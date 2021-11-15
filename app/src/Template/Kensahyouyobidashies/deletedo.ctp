<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyouseihinyobidashimenus = $htmlkensahyoukadoumenu->seihinyobidashimenus();
?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br>
<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査規格</font></a>
    </td>
  </tbody>
</table>

<?= $this->Form->create($product, ['url' => ['controller'=>'Kensahyouyobidashies', 'action' => 'index']]) ?>

<br><br><br>

<table class="top_big">
  <tr><td style="border:none">
  <strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong>
</td></tr>
</table>

<br>
  <table align="center">
    <tbody class='sample non-sample'>
      <tr>
            <td style="border:none"><?= $this->Form->submit(__('TOPへ戻る'), array('name' => 'kettei')) ?></td>
      </tr>
    </tbody>
  </table>
<br><br>
