<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcustomermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcustomermenu = new htmlcustomermenu();
 $htmlcustomer = $htmlcustomermenu->Customersmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcustomer;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="customers index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('顧客一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
              <th scope="col"><?= $this->Paginator->sort('factory_id', ['label'=>"工場・営業所名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"顧客名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('factory', ['label'=>"支店名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('department', ['label'=>"部署名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('address', ['label'=>"住所"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('tel', ['label'=>"電話番号"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('fax', ['label'=>"ファックス"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= $customer->has('factory') ? $this->Html->link($customer->factory->name, ['controller' => 'Factories', 'action' => 'view', $customer->factory->id]) : '' ?></td>
                <td><?= h($customer->name) ?></td>
                <td><?= h($customer->office) ?></td>
                <td><?= h($customer->department) ?></td>
                <td><?= h($customer->address) ?></td>
                <td><?= h($customer->tel) ?></td>
                <td><?= h($customer->fax) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('編集'), ['action' => 'editform', $customer->id]) ?>
                    <?= $this->Html->link(__('削除'), ['action' => 'deleteconfirm', $customer->id]) ?>
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
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
