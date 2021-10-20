<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlgroupmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlgroupmenu = new htmlgroupmenu();
 $htmlgroup = $htmlgroupmenu->Groupmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlgroup;
?>

<?php
$this->layout = false;
echo $this->Html->css('index');
?>
<br>
<div class="groups index large-9 medium-8 columns content">
  
<div style="text-align: right;">
<a style="text-decoration: none" alien="center" href='/groups/addpre' class="buttonlayout"/><font size='4' color=black><?= __('▷新規登録') ?></font></a>
</div>

  <h2><font color=red><?= __('権限グループ一覧') ?></font></h2>
    <table cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th scope="col" style='width:100'><font color=black><?= __('No.') ?></font></th>
            <th scope="col" style='width:200'><?= $this->Paginator->sort('name_group', ['label'=>"権限グループ名"]) ?></th>
            <th scope="col" style='width:100' class="actions"><?= __('') ?></th>
          </tr>
        </thead>
        <tbody>

        <?php for($j=0; $j<count($Groups); $j++): ?>

        <tr>
        <td><?= h($i) ?></td>
        <td><?= h($Groups[$j]["name_group"]) ?></td>
        <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $Groups[$j]["name_group"]]) ?>
        </td>
        </tr>
        <?php
            $i = $i + 1;
            ?>

        <?php endfor;?>

        <?php
        /*
            <?php foreach ($groups as $group): ?>
            <tr>
              <td><?= h($i) ?></td>
              <td><?= h($group->name_group) ?></td>
                <td class="actions">
                  <?= $this->Html->link(__('詳細'), ['action' => 'detail', $group->name_group]) ?>
                </td>
            </tr>
            <?php
            $i = $i + 1;
            ?>
            <?php endforeach; ?>
            */
            ?>
        </tbody>
    </table>
</div>
