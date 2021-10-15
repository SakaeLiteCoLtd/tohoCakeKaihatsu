<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlusermenu = new htmlusermenu();
 $htmluser = $htmlusermenu->Usermenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;

 $sexoptions = [
  '0' => '男',
  '1' => '女'
        ];

?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<form method="post" action="/staffs/editconfirm">

<?= $this->Form->create($Staffs, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($Staffs) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('メンバー情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <table>
          <tr>
            <td width="280"><strong>工場・営業所</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('factory_id', ['options' => $Factories, 'label'=>false]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>氏名</strong></td>
            <td width="280"><strong>性別</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
            <td><?= $this->Form->control('sex', ['options' => $sexoptions, 'label'=>false]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
          <td width="280"><strong>部署</strong></td>
            <td width="280"><strong>職種</strong></td>
        	</tr>
          <tr>
          <td><?= $this->Form->control('department_id', ['options' => $departments, 'label'=>false, 'empty' => true]) ?></td>
            <td><?= $this->Form->control('position_id', ['options' => $positions, 'label'=>false, 'empty' => true]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>メール</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('tel', array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
            <td><?= $this->Form->control('mail', array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('address', array('type'=>'text', 'label'=>false, 'size'=>50, 'autocomplete'=>"off")) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="180"><strong>生年月日</strong></td>
            <td width="180"><strong>入社日</strong></td>
            <td width="180"><strong>退社日</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->input("birth", array('type' => 'date', 'minYear' => date('Y') - 70, 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
            <td><?= $this->Form->input("date_start", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
            <td><?= $this->Form->input("date_finish", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>ユーザーID</strong></td>
            <td width="280"><strong>メンバーコード</strong></td>
        	</tr>
          <tr>
          <td><?= $this->Form->control('user_code', array('type'=>'text', 'value'=>$user_code, 'label'=>false, 'required'=>true, 'autocomplete'=>"off")) ?></td>
          <td><?= $this->Form->control('staff_code', array('type'=>'text', 'label'=>false, 'required'=>true, 'autocomplete'=>"off")) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>グループ</strong></td>
            <td width="280"><strong>パスワード</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('group_name', ['options' => $Groupnames, 'value'=>$group_name, 'label'=>false, "empty"=>"選択してください", 'required'=>true]) ?></td>
            <td><?= $this->Form->control('password', array('type'=>'password', 'label'=>false, 'size'=>20,  'value'=>"", 'required'=>true)) ?></td>
        	</tr>
        </table>
        
    </fieldset>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
    <?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>
    <?= $this->Form->end() ?>
  </nav>
</form>
<br><br><br>
