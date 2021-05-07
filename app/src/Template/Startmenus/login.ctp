<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
/*
<html>
  <iframe src="http://localhost:5050/startmenus/menu" width="800" height="1000">
  </iframe>
</html>
*/
?>

<div class="users form">
  <?= $this->Flash->render() ?>
  <?= $this->Form->create() ?>
  <fieldset>
    <br>
    <legend align="center"><?= __('ユーザ名とパスワードを入力してください') ?></legend>
    <table align="center">
      <tr height="45">
        <td width="100"><strong>ユーザ名</strong></td>
        <td width="200"><?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'size'=>14)) ?></td>
      </tr>
      <tr height="45">
        <td align="center"><strong>パスワード</strong></td>
        <td align="center"><?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'size'=>14)) ?></td>
      </tr>
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
