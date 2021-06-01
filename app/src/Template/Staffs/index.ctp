<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
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
<br>
<div class="staffs index large-9 medium-8 columns content" style="width:70%;">
  <h2><font color=red><?= __('スタッフ一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" style='width:100'><?= $this->Paginator->sort('No.') ?></th>
            <th scope="col"><?= $this->Paginator->sort('office', ['label'=>"工場・営業所"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"氏名"]) ?></th>
            <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($staffs as $staff): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($staff->factory->name) ?></td>
                <td><?= h($staff->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $staff->id]) ?>
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
