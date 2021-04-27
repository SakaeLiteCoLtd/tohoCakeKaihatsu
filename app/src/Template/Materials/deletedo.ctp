<?php
 use App\myClass\menulists\htmlmaterialmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmaterialmenu = new htmlmaterialmenu();
 $htmlmaterial = $htmlmaterialmenu->Materialsmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterial;
?>

<form method="post" action="/materials/index">

<?= $this->Form->create($material, ['url' => ['action' => 'index']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($material) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('原料情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>原料コード</strong></td>
            <td width="280"><strong>グレード</strong></td>
        	</tr>
          <tr>
            <td><?= h($material['material_code']) ?></td>
            <td><?= h($material['grade']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>色</strong></td>
            <td width="280"><strong>メーカー</strong></td>
        	</tr>
          <tr>
            <td><?= h($material['color']) ?></td>
            <td><?= h($material['maker']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>原料種類</strong></td>
        	</tr>
          <tr>
            <td><?= h($material->material_type->type) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('原料メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
