<?php
 use App\myClass\menulists\htmlstaffAbilitymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlstaffAbilitymenu = new htmlstaffAbilitymenu();
 $htmlstaffAbility = $htmlstaffAbilitymenu->StaffAbilitymenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlstaffAbility;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>
<br>
<div class="staffAbilities index large-9 medium-8 columns content">
  <h2><font color=red><?= __('スタッフ権限一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr bgcolor="#f0e68c">
            <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
              <th scope="col" style='width:200'><?= $this->Paginator->sort('staff_id', ['label'=>"スタッフ"]) ?></th>
                <th scope="col" style='width:250' class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody bgcolor="#FFFFCC">
            <?php foreach ($staffAbilities as $staffAbility): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= h($staffAbility->staff->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('取り扱い可能メニュー表示'), ['action' => 'detail', $staffAbility->staff_id]) ?>
                </td>
            </tr>
            <?php
            $i = $i + 1;
            ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
