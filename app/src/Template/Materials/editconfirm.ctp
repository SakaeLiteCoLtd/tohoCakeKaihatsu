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

<form method="post" action="/materials/editdo">

<?= $this->Form->create($material, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$this->request->getData('id'), 'label'=>false)) ?>
<?= $this->Form->control('material_code', array('type'=>'hidden', 'value'=>$this->request->getData('material_code'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('material_supplier_id', array('type'=>'hidden', 'value'=>$this->request->getData('material_supplier_id'), 'label'=>false)) ?>
<?= $this->Form->control('material_type_id', array('type'=>'hidden', 'value'=>$this->request->getData('material_type_id'), 'label'=>false)) ?>
<?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$this->request->getData('tanni'), 'label'=>false)) ?>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('status_kensahyou', array('type'=>'hidden', 'value'=>$this->request->getData('status_kensahyou'), 'label'=>false)) ?>
<br><br><br>

<?php

if($status_kensahyou == 0){
  $status_kensahyou_name = "表示";
}else{
  $status_kensahyou_name = "非表示";
}

?>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('仕入品情報編集') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="240"><strong>工場名</strong></td>
          <td width="240"><strong>仕入品コード</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($this->request->getData('material_code')) ?></td>
        </tr>
      </table>
        <table>
        <tr>
        <td width="240"><strong>単位</strong></td>
        <td width="240"><strong>検査表</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('tanni')) ?></td>
        <td><?= h($status_kensahyou_name) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>仕入品名</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('name')) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>仕入品種類</strong></td>
        </tr>
        <tr>
        <td><?= h($type_name) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="480"><strong>仕入先</strong></td>
      </tr>
      <tr>
      <td><?= h($supplier_name) ?></td>
      </tr>
    </table>


    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('決定', array('name' => 'kettei')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
