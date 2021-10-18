<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策

use App\myClass\classprograms\htmlLogin;//myClassフォルダに配置したクラスを使用
$htmllogin = new htmlLogin();
$htmllogin = $htmllogin->login();

?>
<html class='sample non-sample'>
<div>
  <?= $this->Flash->render() ?>
  <?= $this->Form->create() ?>
  <fieldset>
    <br>

    <legend align="center"><?= __('ユーザーIDとパスワードを入力してください') ?></legend>

    <?php
/*
    <table align="center">
      <tr height="45">
        <td width="100"><strong>ユーザーID</strong></td>
        <td width="150">
        <?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'size'=>14, 'autocomplete' => 'new-password', 'content'=>'no-cache')) ?>
         </td>
      </tr>
      <tr height="45">
        <input type="password" name="dummypass" style="visibility: hidden; top: -100px; left: -100px;" />
        <td align="center"><strong>パスワード</strong></td>
        <td align="center">
        <?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'size'=>14, 'autocomplete' => 'new-password')) ?>
        </td>
    </table>
*/

echo $htmllogin;

    ?>

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