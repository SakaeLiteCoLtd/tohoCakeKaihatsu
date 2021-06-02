<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlgroupmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlgroupmenu = new htmlgroupmenu();
 $htmlgroup = $htmlgroupmenu->Groupmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>

<?php
     echo $htmllogin;
?>
<?php
     echo $htmlgroup;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="groups index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('グループ一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
            <th scope="col"><?= $this->Paginator->sort('name_group', ['label'=>"グループ名"]) ?></th>
            <th scope="col" class="actions"><?= __('') ?></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($groups as $group): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($group->name_group) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $group->name_group]) ?>
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
