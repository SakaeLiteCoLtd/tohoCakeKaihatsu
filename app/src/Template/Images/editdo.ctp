<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlimgmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlimgmenu = new htmlimgmenu();
 $htmlimgmenu = $htmlimgmenu->Imgmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlimgmenu;
?>
 <br><br><br>
 <?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'ichiran']]) ?>
  <br>
  <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像編集') ?></strong></legend>
  </fieldset>
  <table>
    <tbody class='sample non-sample'>
      <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
    </tbody>
  </table>
  <br>

  <table class='sample non-sample'>
    <tr>
      <td width="300"><strong>製品名</strong></td>
    </tr>
    <tr>
      <td><?= h($product_name) ?></td>
    </tr>
  </table>

  <table class='sample non-sample'>
    <tbody class='sample non-sample'>
      <tr>
        <td style="border:none"><?php echo $this->Html->image($gif,['width'=>'600']);?></td>
      </tr>
    </tbody>
  </table>
  <table>
    <tr>
      <tbody class='sample non-sample'>
      <td style="border-style: none;"><div><?= $this->Form->submit('検査表画像メニュートップへ戻る', array('name' => 'top')); ?></div></td>
    </tbody>
  </tr>
  </table>

  <?= $this->Form->end() ?>
