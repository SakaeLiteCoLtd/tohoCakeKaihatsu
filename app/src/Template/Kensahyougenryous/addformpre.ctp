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
<?php
     echo $htmlkensahyoukadou;
?>
<br>
<?php
     echo $htmlkensahyoumenu;
?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr>
      <td width="280"><strong>管理No</strong></td>
    </tr>
    <tr>
      <td style="border: 1px solid black"><?= $this->Form->control('product_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true)) ?></td>
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