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
<?php
/*
echo $this->Html->image($gif,['width'=>'400', 'height'=>'250']
*/
?>

 <br><br><br>
 <?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'adddo']]) ?>
 <fieldset>
     <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像新規登録') ?></strong></legend>
 </fieldset>
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
       <td style="border:none"><?php echo $this->Html->image($gif,['width'=>'800']);?></td>
     </tr>
   </tbody>
 </table>

  <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
  <?= $this->Form->control('gif', array('type'=>'hidden', 'value'=>$gif, 'label'=>false)) ?>
  <table>
    <tbody class='sample non-sample'>
      <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
        <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
      </tr>
    </tbody>
  </table>

  <?= $this->Form->end() ?>
