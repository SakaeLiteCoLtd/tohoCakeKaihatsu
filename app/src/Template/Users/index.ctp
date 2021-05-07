<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlusermenu = new htmlusermenu();
 $htmluser = $htmlusermenu->Usermenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="users index large-9 medium-8 columns content" style="width:70%">
  <h2><font><?= __('ユーザー一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
              <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
              <th scope="col"><?= $this->Paginator->sort('user_code', ['label'=>"ユーザー名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('staff', ['label'=>"スタッフ"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('super_user', ['label'=>"スーパーユーザー"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('group_name', ['label'=>"グループ"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($user->user_code) ?></td>
                <td><?= $user->has('staff') ? $this->Html->link($user->staff->name, ['controller' => 'Staffs', 'action' => 'view', $user->staff->id]) : '' ?></td>

                <?php
                if($this->Number->format($user->super_user) == 1){
                  $super_user = "はい";
                }else{
                  $super_user = "いいえ";
                }
                $i = $i + 1;
                ?>

                <td><?= h($super_user) ?></td>
                <td><?= h($user->group_name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'editform', $user->id]) ?>
                  <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $user->id]) ?>
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
