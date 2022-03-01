<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlmaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmaterialmenu = new htmlmaterialmenu();
 $htmlmaterial = $htmlmaterialmenu->materialmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterial;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>

<br>
<div class="materials index large-9 medium-8 columns content">
  
<div style="text-align: right;">
<a style="text-decoration: none" alien="center" href='/materials/addform' class="buttonlayout"/><font size='4' color=black><?= __('▷新規登録') ?></font></a>
</div>

  <h2><font color=red><?= __('仕入品一覧') ?></font></h2>

  <?php
/*
  <table>
      <tbody class='sample non-sample'>
        <tr alien='right'>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border:none; background-color: #E6FFFF" class="actions"><?= $this->Html->link(__('データの新しい順に並び替え'), ['action' => 'index', "narabikae"]) ?></td>
        </tr>
      </tbody>
    </table>
*/
    ?>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:50'><font color=black><?= __('No.') ?></font></th>
              <th scope="col"><?= $this->Paginator->sort('material_code', ['label'=>"仕入品コード"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"仕入品名"]) ?></th>
              <th scope="col"><?= $this->Paginator->sort('name', ['label'=>"仕入先"]) ?></th>
              <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($materials as $material): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($material->material_code) ?></td>
                <td><?= h($material->name) ?></td>
                <td><?= h($material->material_supplier->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'detail', $material->id]) ?>
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
