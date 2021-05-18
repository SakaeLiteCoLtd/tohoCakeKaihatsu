<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<br>
<?php
     echo $htmlkensahyoukadou;
?>
<br>
<?php
     echo $htmlkensahyoumenu;
?>

<br>
<hr size="5" style="margin: 0rem">
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/kikakukensaku.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<?= $this->Form->create($product, ['url' => ['action' => 'kensakuhyouji']]) ?>

<br>
<div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
<div align="center"><font size="3"><?= __("製品の管理No.を入力してください。") ?></font></div>
<br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
      <td width="280"><strong>管理No</strong></td>
    </tr>
    <tr>
      <td style="border: 1px solid black"><?= $this->Form->control('product_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'required'=>true)) ?></td>
    </tr>
  </tbody>
</table>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
    </tr>
  </tbody>
</table>
