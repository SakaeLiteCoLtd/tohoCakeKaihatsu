<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlusermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlusermenu = new htmlusermenu();
 $htmluser = $htmlusermenu->Usermenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmluser;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>
<br>
<div class="staffs index large-9 medium-8 columns content">
  
<div style="text-align: right;">
<a style="text-decoration: none" alien="center" href='/staffs/addform' class="buttonlayout"/><font size='4' color=black><?= __('▷新規登録') ?></font></a>
</div>

  <h2><font color=red><?= __('メンバー一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
            <th scope="col" style='width:200'><?= $this->Paginator->sort('office', ['label'=>"工場名"]) ?></th>
            <th scope="col" style='width:200'><?= $this->Paginator->sort('name', ['label'=>"氏名"]) ?></th>
            <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($staffs as $staff): ?>
            <tr>
              <td><?= h($i) ?></td>
                <td><?= h($staff->factory->name) ?></td>
                <td><?= h($staff->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'detail', $staff->id]) ?>
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
