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

<form method="post" action="/materialSuppliers/editconfirm">

<?= $this->Form->create($materialSupplier, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">

    <?= $this->Form->create($materialSupplier) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('原料仕入先情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>工場・営業所名</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false]) ?></td>
        </tr>
      </table>
        <table>
          <tr>
            <td width="280"><strong>原料仕入先名</strong></td>
            <td width="280"><strong>支店名</strong></td>
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

        <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

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
