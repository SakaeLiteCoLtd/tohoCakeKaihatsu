<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlmenumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmenumenu = new htmlmenumenu();
 $htmlmenu = $htmlmenumenu->Menumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmenu;
?>

<?= $this->Form->create($menu, ['url' => ['action' => 'addcomfirm']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">

    <?= $this->Form->create($menu) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('メニュー新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>メニュー名</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('name_menu', array('type'=>'text', 'label'=>false)) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
