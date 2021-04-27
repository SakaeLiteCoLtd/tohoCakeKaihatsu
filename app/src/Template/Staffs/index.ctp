<?php
 use App\myClass\menulists\htmlstaffmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlstaffmenu = new htmlstaffmenu();
 $htmlstaff = $htmlstaffmenu->Staffmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlstaff;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="staffs index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('スタッフ一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
            <th scope="col"><?= $this->Paginator->sort('office', ['label'=>"工場・営業所"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('department', ['label'=>"部署"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('occupation', ['label'=>"職種"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('position', ['label'=>"役職"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"氏名"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('sex', ['label'=>"性別"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('mail', ['label'=>"メール"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('tel', ['label'=>"電話番号"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('address', ['label'=>"住所"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('birth', ['label'=>"生年月日"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('date_start', ['label'=>"入社日"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('date_finish', ['label'=>"退社日"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staffs as $staff): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= $staff->has('office') ? $this->Html->link($staff->office->name, ['controller' => 'Offices', 'action' => 'view', $staff->office->id]) : '' ?></td>
                <td><?= $staff->has('department') ? $this->Html->link($staff->department->id, ['controller' => 'Departments', 'action' => 'view', $staff->department->id]) : '' ?></td>
                <td><?= $staff->has('occupation') ? $this->Html->link($staff->occupation->id, ['controller' => 'Occupations', 'action' => 'view', $staff->occupation->id]) : '' ?></td>
                <td><?= $staff->has('position') ? $this->Html->link($staff->position->id, ['controller' => 'Positions', 'action' => 'view', $staff->position->id]) : '' ?></td>
                <td><?= h($staff->name) ?></td>

                <?php
                if($this->Number->format($staff->sex) == 0){
                  $sex = "男";
                }else{
                  $sex = "女";
                }
                $i = $i + 1;
                ?>

                <td><?= h($sex) ?></td>
                <td><?= h($staff->mail) ?></td>
                <td><?= h($staff->tel) ?></td>
                <td><?= h($staff->address) ?></td>
                <td><?= h($staff->birth) ?></td>
                <td><?= h($staff->date_start) ?></td>
                <td><?= h($staff->date_finish) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'editform', $staff->id]) ?>
                  <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $staff->id]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
      </table>
      <div class="paginator">
          <ul class="pagination">
              <?= $this->Paginator->first('<< ' . __('最初のページ')) ?>
              <?= $this->Paginator->prev('< ' . __('前へ')) ?>
              <?= $this->Paginator->numbers() ?>
              <?= $this->Paginator->next(__('次へ') . ' >') ?>
              <?= $this->Paginator->last(__('最後のページ') . ' >>') ?>
          </ul>
          <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
      </div>
  </div>
