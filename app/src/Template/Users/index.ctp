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
<div class="users index large-9 medium-8 columns content">
  <h2><font><?= __('ユーザー一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
              <th scope="col" style='width:200'><?= $this->Paginator->sort('user_code', ['label'=>"社員コード"]) ?></th>
              <th scope="col" style='width:200'><?= $this->Paginator->sort('staff', ['label'=>"氏名"]) ?></th>
                <th scope="col" class="actions" style='width:100'><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= h($user->user_code) ?></td>
              <td><?= h($user->staff->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $user->id]) ?>
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
          <?= $this->Paginator->first('<< ' . __('最初のページ')) ?>
          <?= $this->Paginator->prev('< ' . __('前へ')) ?>
          <?= $this->Paginator->numbers() ?>
          <?= $this->Paginator->next(__('次へ') . ' >') ?>
          <?= $this->Paginator->last(__('最後のページ') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
