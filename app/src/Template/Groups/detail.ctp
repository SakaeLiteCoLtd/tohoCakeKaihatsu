<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlgroupmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlgroupmenu = new htmlgroupmenu();
 $htmlgroup = $htmlgroupmenu->Groupmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlgroup;
?>

<?= $this->Form->create($groups, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>

    <table>
      <tr>
        <td width="280"><strong>グループ名</strong></td>
      </tr>
      <tr>
        <td><?= h($name_group) ?></td>
      </tr>
    </table>


    <table>
      <tr>
        <td width="280"><strong>取り扱い可能メニュー</strong></td>
      </tr>

    <?php for($j=0; $j<count($arrGroups); $j++): ?>

      <tr>
        <td><?= h($arrGroups[$j]) ?></td>
      </tr>

    <?php endfor;?>

  </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'delete')); ?></div></td>
        </tr>
      </tbody>
    </table>

<br>

    <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('グループを修正する場合は、一度削除してから登録しなおしてください。') ?></strong></td></tr>
      </tbody>
    </table>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
