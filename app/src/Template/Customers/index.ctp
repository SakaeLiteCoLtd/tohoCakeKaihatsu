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
<br>
<div class="customers index large-9 medium-8 columns content">
  
<div style="text-align: right;">
<a style="text-decoration: none" alien="center" href='/customers/addform' class="buttonlayout"/><font size='4' color=black><?= __('▷新規登録') ?></font></a>
</div>

  <h2><font color=red><?= __('得意先・仕入先一覧') ?></font></h2>

  <table>
      <tbody class='sample non-sample'>
        <tr alien='right'>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border-style:none; background-color: #E6FFFF"><?= __("　　　　　　　　　　　　　　　　　") ?></td>
          <td style="border:none; background-color: #E6FFFF" class="actions"><?= $this->Html->link(__('データの新しい順に並び替え'), ['action' => 'index', "narabikae"]) ?></td>
        </tr>
      </tbody>
    </table>

    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
              <th scope="col" style='width:220'><?= $this->Paginator->sort('name', ['label'=>"得意先・仕入先コード"]) ?></th>
              <th scope="col" style='width:250'><?= $this->Paginator->sort('name', ['label'=>"得意先・仕入先名"]) ?></th>
                <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= h($customer->customer_code) ?></td>
              <td><?= h($customer->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'detail', $customer->id]) ?>
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
