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

<?= $this->Form->create($Groups, ['url' => ['action' => 'addcomfirm']]) ?>

<?= $this->Form->control('name_group', array('type'=>'hidden', 'value'=>$this->request->getData('name_group'), 'label'=>false)) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($Groups) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('権限グループ新規登録') ?></strong></legend>
        <br>

        <table>
          <tr>
            <td width="280"><strong>権限グループ名</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name_group')) ?></td>
        	</tr>
        </table>

        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('グループの取り扱い可能メニューにチェックしてください') ?></strong></td></tr>
          </tbody>
        </table>

        <table>
          <tr>
            <td></td>
            <td width="280"><strong>メニュー一覧</strong></td>
        	</tr>

          <?php for($i=0; $i<count($arrmenu); $i++): ?>

          <tr>
            <td><?= $this->Form->control('checkbox'.$i, array('type'=>'checkbox', 'label'=>false)) ?></td>
            <td><?= h($arrmenu[$i]) ?></td>
        	</tr>

          <?= $this->Form->control('menu'.$i, array('type'=>'hidden', 'value'=>$arrmenu[$i], 'label'=>false)) ?>
          <?= $this->Form->control('nummax', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

        <?php endfor;?>

        </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
