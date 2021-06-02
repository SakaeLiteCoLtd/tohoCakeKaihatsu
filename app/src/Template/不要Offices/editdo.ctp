<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlofficemenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlofficemenu = new htmlofficemenu();
 $htmloffice = $htmlofficemenu->Officemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmloffice;
?>

<?= $this->Form->create($Offices, ['url' => ['action' => 'index']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($Offices) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('工場・営業所情報編集') ?></strong></legend>
      <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>工場・営業所名</strong></td>
            <td width="280"><strong>会社名</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name')) ?></td>
            <td><?= h($Company_name) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('工場・営業所メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
