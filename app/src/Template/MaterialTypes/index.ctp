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
<br>
<div class="materialTypes index large-9 medium-8 columns content">
  <h2><font color=red><?= __('原料種類一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
              <th scope="col" style='width:200'><?= $this->Paginator->sort('factory_id', ['label'=>"工場・営業所名"]) ?></th>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('type', ['label'=>"原料種類"]) ?></th>
                <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materialTypes as $materialType): ?>
            <tr>
              <td><?= h($i) ?></td>
               <td><?= h($materialType->factory->name) ?></td>
               <td><?= h($materialType->type) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $materialType->id]) ?>
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
