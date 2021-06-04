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
  //   echo $htmlimgmenu;
?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像新規登録') ?></strong></legend>
    </fieldset>

    <br>
    <div align="center"><font color="red" size="4"><?= __($product_code." の検査表に使用する画像ファイルを選択してください。") ?></font></div>
    <div align="center"><strong style="font-size: 13pt; color:red"><?= __("※画像ファイルの拡張子は「.gif」にしてください。") ?></strong></div>
    <br>

    <br>
    <div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
    <br>

  <?= $this->Form->create($inspectionStandardSizeParents,['action'=>'addcomfirm', 'type'=>'file']) ?>
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
            <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
          </tr>
        </tbody>
      </table>
      <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
      <?= $this->Form->end() ?>
</nav>
