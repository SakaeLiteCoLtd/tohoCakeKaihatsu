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

<nav class="sample non-sample">
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像編集') ?></strong></legend>
    </fieldset>
    <table>
  <tbody class='sample non-sample'>
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('長さのみ異なる製品には同じ検査表画像が使用されます。') ?></strong></td></tr>
  </tbody>
</table>

    <br>
    <div align="center"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></div>
    <div align="center"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></div>
    <br>

  <?= $this->Form->create($inspectionStandardSizeParents,['action'=>'editcomfirm', 'type'=>'file']) ?>
      <fieldset>
        <table>
          <tbody>
            <tr>
              <td><?= $this->Form->file('upfile') ?></td>
            </tr>
          </tbody>
        </table>
      </fieldset>
      <table>
        <tbody class='sample non-sample'>
          <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
            <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
          </tr>
        </tbody>
      </table>

      <br>
      <div align="center"><font size="4"><?= __($product_name." の検査表に使用する画像ファイルを選択してください。") ?></font></div>
      <div align="center"><strong style="font-size: 13pt; color:red"><?= __("※画像ファイルの拡張子は「.JPG」にしてください。") ?></strong></div>
      <br>

      <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
      <?= $this->Form->end() ?>
</nav>
