<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlseikeikimenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlseikeikimenu = new htmlseikeikimenu();
 $htmlseikeiki = $htmlseikeikimenu->seikeikimenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlseikeiki;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>
<br>
<div class="seikeikis index large-9 medium-8 columns content">
  
<div style="text-align: right;">
<a style="text-decoration: none" alien="center" href='/seikeikis/addform' class="buttonlayout"/><font size='4' color=black><?= __('▷新規登録') ?></font></a>
</div>

  <h2><font color=red><?= __('成形機一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
              <?php if ($usercheck == 1): ?>
                <th scope="col" style='width:200'><?= $this->Paginator->sort('name', ['label'=>"工場名"]) ?></th>
                <?php else : ?>
                  <?php endif; ?>

                <th scope="col" style='width:200'><?= $this->Paginator->sort('name', ['label'=>"成形機"]) ?></th>
                <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($seikeikis as $seikeiki): ?>
            <tr>
              <td><?= h($i) ?></td>
              <?php if ($usercheck == 1): ?>
                <td><?= h($seikeiki->factory->name) ?></td>
                <?php else : ?>
                  <?php endif; ?>
              <td><?= h($seikeiki->name) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('編集'), ['action' => 'detail', $seikeiki->id]) ?>
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
