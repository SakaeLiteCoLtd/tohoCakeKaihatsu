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

<nav class="large-3 medium-4 columns">
  <?= $this->Form->create($inspectionStandardSizeParents, ['url' => ['action' => 'addform']]) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('検査表画像新規登録') ?></strong></legend>
    </fieldset>

    <br>
    <div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
    <br>

    <table>
      <tr>
        <td width="280"><strong>社内品番</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('product_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true)) ?></td>
      </tr>
    </table>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
