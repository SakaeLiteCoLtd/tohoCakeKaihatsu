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

<?= $this->Form->create($material, ['url' => ['action' => 'addcomfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

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
          <td width="280"><strong>自社工場</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false]) ?></td>
        </tr>
      </table>
        <table>
        <tr>
        <td width="260"><strong>原料コード</strong></td>
        <td width="200"><strong>単位</strong></td>
        </tr>
        <tr>
        <td><?= $this->Form->control('material_code', array('type'=>'text', 'label'=>false, 'size'=>20, 'autofocus'=>true)) ?></td>
        <td><?= $this->Form->control('tanni', array('type'=>'text', 'label'=>false, 'size'=>15)) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>原料名</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>40)) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>原料種類</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('type_id', ['options' => $arrMaterialTypes, 'label'=>false]) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="480"><strong>原料仕入先</strong></td>
      </tr>
      <tr>
      <td><?= $this->Form->control('material_supplier_id', ['options' => $arrMaterialSuppliers, 'label'=>false]) ?></td>
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
