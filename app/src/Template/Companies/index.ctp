<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcompanymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcompanymenu = new htmlcompanymenu();
 $htmlcompany = $htmlcompanymenu->Companiesmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcompany;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="companies index large-9 medium-8 columns content" style="width:70%">
    <h2><font color=red><?= __('会社一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"会社名"]) ?></th>
                <th scope="col"><?= $this->Paginator->sort('address', ['label'=>"所在地"]) ?></th>
                <th scope="col"><?= $this->Paginator->sort('tel', ['label'=>"電話番号"]) ?></th>
                <th scope="col"><?= $this->Paginator->sort('fax', ['label'=>"FAX"]) ?></th>
                <th scope="col"><?= $this->Paginator->sort('president', ['label'=>"代表者"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($companies as $company): ?>
            <tr>
                <td><?= h($i) ?></td>
                <td><?= h($company->name) ?></td>
                <td><?= h($company->address) ?></td>
                <td><?= h($company->tel) ?></td>
                <td><?= h($company->fax) ?></td>
                <td><?= h($company->president) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('編集'), ['action' => 'editform', $company->id]) ?>
                    <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $company->id]) ?>
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
