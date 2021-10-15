<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
/*
<html>
  <iframe src="http://localhost:5050/startmenus/menu" width="800" height="1000">
  </iframe>
</html>
*/
?>
<html class='sample non-sample'>
<div>
  <?= $this->Flash->render() ?>
  <?= $this->Form->create() ?>
  <fieldset>
    <br>
    <legend align="center"><?= __('ユーザーIDとパスワードを入力してください') ?></legend>
    <table align="center">
      <tr height="45">
        <td width="100"><strong>ユーザーID</strong></td>
        <td width="150">
          <?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'size'=>14, 'autocomplete' => 'off')) ?>
         </td>
      </tr>
      <tr height="45">
        <input type="password" name="dummypass" style="visibility: hidden; top: -100px; left: -100px;" />
        <td align="center"><strong>パスワード</strong></td>
        <td align="center">
          <?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'size'=>14, 'autocomplete' => 'new-password')) ?>
        </td>
    </table>
  </fieldset>

  <table align="center" width="100">
    <tbody class='sample non-sample'>
      <tr>
        <td style="border:none"><?= $this->Form->submit(__('ログイン')); ?></td>
      </tr>
    </tbody>
  </table>
  <?= $this->Form->end() ?>
</div>
</html>