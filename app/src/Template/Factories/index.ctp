<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlfactorymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlfactorymenu = new htmlfactorymenu();
 $htmlfactory = $htmlfactorymenu->factorymenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlfactory;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="factories index large-9 medium-8 columns content" style="width:70%">
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
            <?php foreach ($Factories as $factory): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($factory->name) ?></td>
                <td><?= $factory->has('company') ? $this->Html->link($factory->company->name, ['controller' => 'Companies', 'action' => 'view', $factory->company->id]) : '' ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'editform', $factory->id]) ?>
                  <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $factory->id]) ?>
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
