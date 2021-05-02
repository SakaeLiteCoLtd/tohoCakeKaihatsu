<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<?= $this->Form->create($material, ['url' => ['action' => 'addcomfirm']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">

    <?= $this->Form->create($material) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('原料新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>原料コード</strong></td>
          <td width="280"><strong>グレード</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('material_code', array('type'=>'text', 'label'=>false, 'autofocus'=>true)) ?></td>
          <td><?= $this->Form->control('grade', array('type'=>'text', 'label'=>false)) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="280"><strong>色</strong></td>
        <td width="280"><strong>メーカー</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('color', array('type'=>'text', 'label'=>false)) ?></td>
        <td><?= $this->Form->control('maker', array('type'=>'text', 'label'=>false)) ?></td>
      </tr>
    </table>

    <table>
    <tr>
      <td width="280"><strong>原料種類</strong></td>
    <tr>
      <td><?= $this->Form->control('type_id', ['options' => $arrMaterialTypes, 'label'=>false]) ?></td>
    </tr>
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
