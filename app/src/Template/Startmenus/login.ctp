
<div class="users form">
  <?= $this->Flash->render() ?>
  <?= $this->Form->create() ?>
  <fieldset>
    <br>
    <legend align="center"><?= __('ユーザ名とパスワードを入力してください') ?></legend>
    <table align="center">
      <tr>
        <td width="100" align="center" bgcolor="#FFFFCC" style="border-bottom:solid;border:solid;border-width: 1px"><strong style="font-size: 11pt">ユーザ名</strong></td>
        <td width="200" align="center" bgcolor="#FFFFCC" style="margin:0;border-bottom:solid;border:solid;border-width: 1px"><?= $this->Form->control('user_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true)) ?></td>
      </tr>
      <tr>
        <td align="center" bgcolor="#FFFFCC" style="border-bottom:solid;border:solid;border-width: 1px"><strong style="font-size: 11pt">パスワード</strong></td>
        <td align="center" bgcolor="#FFFFCC" style="margin:0;border-bottom:solid;border:solid;border-width: 1px"><?= $this->Form->control('password', array('type'=>'password', 'label'=>false)) ?></td>
      </tr>
    </table>
  </fieldset>

  <table align="center" width="100" align="center" style="border-bottom: solid;border-width: 0px">
    <tr>
      <td><?= $this->Form->submit(__('ログイン')); ?></td>
    </tr>
  </table>
  <?= $this->Form->end() ?>
</div>
