<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlmaterialTypemenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmaterialTypemenu = new htmlmaterialTypemenu();
 $htmlmaterialType = $htmlmaterialTypemenu->materialTypemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterialType;
?>

<?= $this->Form->create($materialType, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('仕入品種類新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <?php if ($usercheck == 1): ?>

<table>
<tr>
<td width="280"><strong>工場名</strong></td>
</tr>
<tr>
<td><?= h($factory_name) ?></td>
</tr>
</table>

<?php else : ?>
  <?php endif; ?>

        <table>
        <tr>
          <td width="280"><strong>仕入品種類</strong></td>
        </tr>
        <tr>
          <td><?= h($this->request->getData('type')) ?></td>
        </tr>
      </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('トップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
