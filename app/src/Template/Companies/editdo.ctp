<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcompanymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcompanymenu = new htmlcompanymenu();
 $htmlcompany = $htmlcompanymenu->Companiesmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcompany;
?>

<?= $this->Form->create($company, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($company) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('会社情報編集') ?></strong></legend>
      <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>会社名</strong></td>
            <td width="280"><strong>代表者</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name')) ?></td>
            <td><?= h($this->request->getData('president')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>FAX</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('tel')) ?></td>
            <td><?= h($this->request->getData('fax')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>所在地</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('address')) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('会社メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
