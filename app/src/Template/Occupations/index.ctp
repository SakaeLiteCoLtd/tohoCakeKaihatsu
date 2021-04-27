<?php
 use App\myClass\menulists\htmloccupationmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmloccupationmenu = new htmloccupationmenu();
 $htmloccupation = $htmloccupationmenu->Occupationmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmloccupation;
?>
<div class="occupations index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('職種一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr bgcolor="#f0e68c">
            <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
            <th scope="col"><?= $this->Paginator->sort('office_id', ['label'=>"工場・営業所名"]) ?></th>
            <th scope="col"><?= $this->Paginator->sort('occupation', ['label'=>"職種名"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody bgcolor="#FFFFCC">
            <?php foreach ($occupations as $occupation): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= $occupation->has('office') ? $this->Html->link($occupation->office->name, ['controller' => 'Offices', 'action' => 'view', $occupation->office->id]) : '' ?></td>
                <td><?= h($occupation->occupation) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'editform', $occupation->id]) ?>
                  <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $occupation->id]) ?>
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
