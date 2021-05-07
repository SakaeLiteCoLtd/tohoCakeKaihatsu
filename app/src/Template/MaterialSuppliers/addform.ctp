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

<?= $this->Form->create($materialSupplier, ['url' => ['action' => 'addcomfirm']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">

    <?= $this->Form->create($materialSupplier) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('原料仕入先新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>原料仕入先名</strong></td>
            <td width="280"><strong>工場・営業所名</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'required'=>true)) ?></td>
            <td><?= $this->Form->control('office', array('type'=>'text', 'label'=>false, 'required'=>true)) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>FAX</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('tel', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('fax', array('type'=>'text', 'label'=>false)) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="180"><strong>部署名</strong></td>
            <td width="350"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('department', array('type'=>'text', 'label'=>false)) ?></td>
            <td><?= $this->Form->control('address', array('type'=>'text', 'label'=>false, 'size'=>30)) ?></td>
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
