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

<?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
      <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>
    <table>
  <tbody class='sample non-sample'>
    <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('長さのみが異なる製品には同じ検査表画像が使用されます。') ?></strong></td></tr>
  </tbody>
</table>

    <table>
      <tr>
      <td width="280"><strong>製品名</strong></td>
      </tr>
      <tr>
      <td><?= h($name) ?></td>
      </tr>
    </table>

    <table class='sample non-sample'>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?php echo $this->Html->image($image_file_name_dir,['width'=>'800']);?></td>
        </tr>
      </tbody>
    </table>
    <table>

    </fieldset>

<br>

<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border-style: none;"><div><?= $this->Form->submit('編集', array('name' => 'edit')); ?></div></td>
      <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'delete')); ?></div></td>
    </tr>
  </tbody>
</table>

<br>

<table>
  <tbody class='sample non-sample'>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('検査表画像を更新する場合は「編集」から進んでください　　　　　　　　　　　　　　　　') ?></strong></td></tr>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('検査表画像を新たに登録しなおす場合は「削除」をして、新しい画像を新規登録してください') ?></strong></td></tr>
  </tbody>
</table>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
