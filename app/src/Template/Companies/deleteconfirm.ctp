<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcompanymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcompanymenu = new htmlcompanymenu();
 $htmlcompany = $htmlcompanymenu->Companiesmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcompany;
?>

<form method="post" action="/companies/deletedo">

<?= $this->Form->create($company, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($company) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$company['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('会社情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>会社名</strong></td>
            <td width="280"><strong>代表者</strong></td>
        	</tr>
          <tr>
            <td><?= h($company['name']) ?></td>
            <td><?= h($company['address']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>FAX</strong></td>
        	</tr>
          <tr>
            <td><?= h($company['tel']) ?></td>
            <td><?= h($company['fax']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>所在地</strong></td>
        	</tr>
          <tr>
            <td><?= h($company['address']) ?></td>
        	</tr>
        </table>

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
