<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<div class="materials index large-9 medium-8 columns content" style="width:70%">
  <h2><font color=red><?= __('原料一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col"><?= $this->Paginator->sort('No.') ?></th>
              <th scope="col"><?= $this->Paginator->sort('factory_id', ['label'=>"工場・営業所名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('material_code', ['label'=>"原料コード"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('grade', ['label'=>"グレード"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('color', ['label'=>"色"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('maker', ['label'=>"メーカー"]) ?></th>
                <th scope="col" class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $material): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= $material->has('factory') ? $this->Html->link($material->factory->name,
               ['controller' => 'Factories', 'action' => 'view', $material->factory->id]) : '' ?></td>
                <td><?= h($material->material_code) ?></td>
                <td><?= h($material->grade) ?></td>
                <td><?= h($material->color) ?></td>
                <td><?= h($material->maker) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $material->id]) ?>
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
