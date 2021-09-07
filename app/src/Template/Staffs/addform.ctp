<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlstaffmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlstaffmenu = new htmlstaffmenu();
 $htmlstaff = $htmlstaffmenu->Staffmenus();
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
     echo $htmlstaff;
?>

<br><br><br>
<nav class="sample non-sample">
  <?= $this->Form->create($Staffs, ['url' => ['action' => 'addcomfirm']]) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('スタッフ新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>氏名</strong></td>
            <td width="280"><strong>性別</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('sex', ['options' => $sexoptions, 'label'=>false]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>工場・営業所</strong></td>
            <td width="280"><strong>部署</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('factory_id', ['options' => $Factories, 'label'=>false]) ?></td>
            <td><?= $this->Form->control('department_id', ['options' => $departments, 'label'=>false, 'empty' => true]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>職種</strong></td>
            <td width="280"><strong>役職</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('occupation_id', ['options' => $occupations, 'label'=>false, 'empty' => true]) ?></td>
            <td><?= $this->Form->control('position_id', ['options' => $positions, 'label'=>false, 'empty' => true]) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>メール</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('tel', array('type'=>'text', 'pattern' => '^[0-9A-Za-z-]+$', 'title'=>'半角数字で入力して下さい。', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('mail', array('type'=>'text', 'pattern' => '^[0-9A-Za-z@._-]+$', 'title'=>'半角英数字で入力して下さい。', 'label'=>false)) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="560"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('address', array('type'=>'text', 'label'=>false, 'size'=>50)) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>生年月日</strong></td>
            <td width="280"><strong>入社日</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->input("birth", array('type' => 'date', 'minYear' => date('Y') - 70, 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
            <td><?= $this->Form->input("date_start", array('type' => 'date', 'monthNames' => false, 'label'=>false, 'empty' => true)); ?></td>
        	</tr>
        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>
    <?= $this->Form->end() ?>
  </nav>
