<?php
 use App\myClass\menulists\htmlofficemenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlofficemenu = new htmlofficemenu();
 $htmloffice = $htmlofficemenu->Officemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmloffice;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="offices index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('工場・営業所一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
              <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
              <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"工場・営業所名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('company_id', ['label'=>"会社名"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($offices as $office): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($office->name) ?></td>
                <td><?= $office->has('company') ? $this->Html->link($office->company->name, ['controller' => 'Companies', 'action' => 'view', $office->company->id]) : '' ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'editform', $office->id]) ?>
                  <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $office->id]) ?>
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
