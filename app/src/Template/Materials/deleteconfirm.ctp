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

<form method="post" action="/materials/deletedo">

<?= $this->Form->create($material, ['url' => ['action' => 'deletedo']]) ?>
<br><br><br>

<?php

if($status_kensahyou == 0){
  $status_kensahyou_name = "表示";
}else{
  $status_kensahyou_name = "非表示";
}

?>

<nav class="sample non-sample">
    <?= $this->Form->create($material) ?>
    <fieldset>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$material['id'], 'label'=>false)) ?>

      <legend><strong style="font-size: 15pt; color:red"><?= __('仕入品情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを削除します。よろしければ「削除」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="260"><strong>工場名</strong></td>
          <td width="200"><strong>検査表</strong></td>
        </tr>
        <tr>
        <td><?= h($material->factory->name) ?></td>
        <td><?= h($status_kensahyou_name) ?></td>
        </tr>
      </table>
        <table>
        <tr>
        <td width="260"><strong>仕入品コード</strong></td>
        <td width="200"><strong>単位</strong></td>
        </tr>
        <tr>
        <td><?= h($material['material_code']) ?></td>
        <td><?= h($material['tanni']) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>仕入品名</strong></td>
        </tr>
        <tr>
        <td><?= h($material['name']) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>仕入品種類</strong></td>
        </tr>
        <tr>
        <td><?= h($material->material_type->type) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="480"><strong>仕入先</strong></td>
      </tr>
      <tr>
      <td><?= h($material->material_supplier->name) ?></td>
      </tr>
    </table>
    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
</form>
