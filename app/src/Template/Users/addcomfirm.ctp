<?php
use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
$htmlusermenu = new htmlusermenu();
$htmluser = $htmlusermenu->Usermenus();
$htmlloginmenu = new htmlloginmenu();
$htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;

 $super_useroptions = [
  '0' => 'いいえ',
  '1' => 'はい'
        ];
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<nav class="large-3 medium-4 columns" style="width:70%">
  <?= $this->Form->create($Users, ['url' => ['action' => 'adddo']]) ?>
  <?= $this->Form->control('user_code', array('type'=>'hidden', 'value'=>$this->request->getData('user_code'), 'label'=>false)) ?>
  <?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$this->request->getData('staff_id'), 'label'=>false)) ?>
  <?= $this->Form->control('super_user', array('type'=>'hidden', 'value'=>$this->request->getData('super_user'), 'label'=>false)) ?>
  <?= $this->Form->control('group_name', array('type'=>'hidden', 'value'=>$this->request->getData('group_name'), 'label'=>false)) ?>
  <?= $this->Form->control('password', array('type'=>'hidden', 'value'=>$this->request->getData('password'), 'label'=>false)) ?>

    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('ユーザー新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>ユーザー名</strong></td>
            <td width="280"><strong>スタッフ</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('user_code')) ?></td>
            <td><?= h($staffhyouji) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>スーパーユーザー</strong></td>
            <td width="280"><strong>グループ</strong></td>
        	</tr>
          <tr>
            <td><?= h($super_userhyouji) ?></td>
            <td><?= h($this->request->getData('group_name')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
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
