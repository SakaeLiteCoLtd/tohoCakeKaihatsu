<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

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
    <td style='border: none;'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査規格</font></a>
 </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'editformselect']]) ?>
<br>
    <div align="center"><strong style="font-size: 13pt; color:red"><?= __("以下の画像が選択されました。よろしければ規格登録へ進んでください。") ?></strong></div>
    <br>

<table width="300">
<tbody style="background-color: #FFFFCC">
   <tr>
     <td width="300"><strong>製品名</strong></td>
   </tr>
   <tr>
     <td><?= h($product_name) ?></td>
   </tr>
 </table>
 <br>
 <table class='sample non-sample'>
   <tbody class='sample non-sample'>
     <tr>
       <td style="border:none"><?php echo $this->Html->image($gif,['width'=>'1300']);?></td>
     </tr>
   </tbody>
 </table>
 <br>
 <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
 <?= $this->Form->control('change_flag', array('type'=>'hidden', 'value'=>$change_flag, 'label'=>false)) ?>
 <?= $this->Form->control('inspection_standard_size_parent_id', array('type'=>'hidden', 'value'=>$inspection_standard_size_parent_id, 'label'=>false)) ?>
  <?= $this->Form->control('gif', array('type'=>'hidden', 'value'=>$gif, 'label'=>false)) ?>
  <table>
    <tbody class='sample non-sample'>
      <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
        <td style="border-style: none;"><div><?= $this->Form->submit('規格編集へ', array('name' => 'kettei')); ?></div></td>
      </tr>
    </tbody>
  </table>

  <?= $this->Form->end() ?>
