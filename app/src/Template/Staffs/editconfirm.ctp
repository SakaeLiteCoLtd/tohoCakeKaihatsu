<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlusermenu = new htmlusermenu();
 $htmluser = $htmlusermenu->Usermenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<form method="post" action="/staffs/editdo">

<?= $this->Form->create($Staffs, ['url' => ['action' => 'editdo']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$this->request->getData('id'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('sex', array('type'=>'hidden', 'value'=>$this->request->getData('sex'), 'label'=>false)) ?>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('department_id', array('type'=>'hidden', 'value'=>$this->request->getData('department_id'), 'label'=>false)) ?>
<?= $this->Form->control('position_id', array('type'=>'hidden', 'value'=>$this->request->getData('position_id'), 'label'=>false)) ?>
<?= $this->Form->control('tel', array('type'=>'hidden', 'value'=>$this->request->getData('tel'), 'label'=>false)) ?>
<?= $this->Form->control('mail', array('type'=>'hidden', 'value'=>$this->request->getData('mail'), 'label'=>false)) ?>
<?= $this->Form->control('address', array('type'=>'hidden', 'value'=>$this->request->getData('address'), 'label'=>false)) ?>
<?= $this->Form->control('birth', array('type'=>'hidden', 'value'=>$birth, 'label'=>false)) ?>
<?= $this->Form->control('date_start', array('type'=>'hidden', 'value'=>$date_start, 'label'=>false)) ?>
<?= $this->Form->control('date_finish', array('type'=>'hidden', 'value'=>$date_finish, 'label'=>false)) ?>
<?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$this->request->getData('user_code'), 'label'=>false)) ?>
<?= $this->Form->control('staff_code', array('type'=>'hidden', 'value'=>$this->request->getData('staff_code'), 'label'=>false)) ?>
<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$this->request->getData('staff_id'), 'label'=>false)) ?>
<?= $this->Form->control('super_user', array('type'=>'hidden', 'value'=>$this->request->getData('super_user'), 'label'=>false)) ?>
<?= $this->Form->control('group_name_id', array('type'=>'hidden', 'value'=>$this->request->getData('group_name_id'), 'label'=>false)) ?>
<?= $this->Form->control('password', array('type'=>'hidden', 'value'=>$this->request->getData('password'), 'label'=>false)) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($Staffs) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('メンバー情報編集') ?></strong></legend>
      <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <table>
          <tr>
            <td width="280"><strong>工場名</strong></td>
        	</tr>
          <tr>
            <td><?= h($factory_name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>氏名</strong></td>
            <td width="280"><strong>性別</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name')) ?></td>
            <td><?= h($sexhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
          <td width="280"><strong>部署</strong></td>
            <td width="280"><strong>職種</strong></td>
        	</tr>
          <tr>
          <td><?= h($department_name) ?></td>
            <td><?= h($position_name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>メール</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('tel')) ?></td>
            <td><?= h($this->request->getData('mail')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('address')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="180"><strong>生年月日</strong></td>
            <td width="180"><strong>入社日</strong></td>
            <td width="180"><strong>退社日</strong></td>
        	</tr>
          <tr>
            <td><?= h($birth) ?></td>
            <td><?= h($date_start) ?></td>
            <td><?= h($date_finish) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
          <td width="280"><strong>ユーザーID</strong></td>
          <td width="280"><strong>メンバーコード</strong></td>
        	</tr>
          <tr>
          <td><?= h($this->request->getData('user_code')) ?></td>
          <td><?= h($this->request->getData('staff_code')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
            <td><?= h($group_name) ?></td>
            <td><?= __("****") ?></td>
        	</tr>
        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
