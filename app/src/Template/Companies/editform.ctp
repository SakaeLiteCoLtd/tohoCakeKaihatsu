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

<form method="post" action="/companies/editconfirm">

<?= $this->Form->create($company, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($company) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('会社情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <table>
          <tr>
            <td width="280"><strong>会社名</strong></td>
            <td width="280"><strong>代表者</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'required'=>true)) ?></td>
            <td><?= $this->Form->control('president', array('type'=>'text', 'label'=>false, 'required'=>true)) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>FAX</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('tel', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('fax', array('type'=>'text', 'label'=>false)) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>所在地</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('address', array('type'=>'text', 'label'=>false, 'size'=>50)) ?></td>
        	</tr>
        </table>
    </fieldset>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>
    <?= $this->Form->end() ?>
  </nav>
</form>
