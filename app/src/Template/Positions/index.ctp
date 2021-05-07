<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlpositionmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlpositionmenu = new htmlpositionmenu();
 $htmlposition = $htmlpositionmenu->Positionmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlposition;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="positions index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('役職一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr bgcolor="#f0e68c">
            <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
            <th scope="col"><?= $this->Paginator->sort('office_id', ['label'=>"工場・営業所名"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('position', ['label'=>"役職名"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody bgcolor="#FFFFCC">
            <?php foreach ($positions as $position): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= $position->has('office') ? $this->Html->link($position->office->name, ['controller' => 'Offices', 'action' => 'view', $position->office->id]) : '' ?></td>
                <td><?= h($position->position) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'editform', $position->id]) ?>
                  <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $position->id]) ?>
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
