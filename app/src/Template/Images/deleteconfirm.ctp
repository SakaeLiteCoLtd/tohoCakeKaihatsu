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

<form method="post" action="/images/deletedo">

<?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($inspectionStandardSizeParents) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下の画像データとの紐づけを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

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
              <td style="border:none"><?php echo $this->Html->image($image_file_name_dir,['width'=>'600']);?></td>
            </tr>
          </tbody>
        </table>
        <table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
</form>
