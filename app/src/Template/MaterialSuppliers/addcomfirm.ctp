<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlmaterialSuppliermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlmaterialSuppliermenu = new htmlmaterialSuppliermenu();
 $htmlmaterialSupplier = $htmlmaterialSuppliermenu->materialSuppliersmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterialSupplier;
?>

<?= $this->Form->create($materialSupplier, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('office', array('type'=>'hidden', 'value'=>$this->request->getData('office'), 'label'=>false)) ?>
<?= $this->Form->control('department', array('type'=>'hidden', 'value'=>$this->request->getData('department'), 'label'=>false)) ?>
<?= $this->Form->control('tel', array('type'=>'hidden', 'value'=>$this->request->getData('tel'), 'label'=>false)) ?>
<?= $this->Form->control('fax', array('type'=>'hidden', 'value'=>$this->request->getData('fax'), 'label'=>false)) ?>
<?= $this->Form->control('address', array('type'=>'hidden', 'value'=>$this->request->getData('address'), 'label'=>false)) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($materialSupplier) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('原料仕入先新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>原料仕入先名</strong></td>
            <td width="280"><strong>工場・営業所名</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name')) ?></td>
            <td><?= h($this->request->getData('office')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>FAX</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('tel')) ?></td>
            <td><?= h($this->request->getData('fax')) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="180"><strong>部署名</strong></td>
            <td width="380"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('department')) ?></td>
            <td><?= h($this->request->getData('address')) ?></td>
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
