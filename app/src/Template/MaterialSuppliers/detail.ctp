<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
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

<?= $this->Form->create($materialSuppliers, ['url' => ['action' => 'detail']]) ?>

<?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>

    <table>
      <tr>
        <td width="280"><strong>工場・営業所名</strong></td>
      </tr>
      <tr>
        <td><?= h($factory_name) ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="280"><strong>原料仕入先名</strong></td>
        <td width="280"><strong>支店名</strong></td>
      </tr>
      <tr>
        <td><?= h($name) ?></td>
        <td><?= h($office) ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="280"><strong>電話番号</strong></td>
        <td width="280"><strong>FAX</strong></td>
      </tr>
      <tr>
        <td><?= h($tel) ?></td>
        <td><?= h($fax) ?></td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="180"><strong>部署名</strong></td>
        <td width="380"><strong>住所</strong></td>
      </tr>
      <tr>
        <td><?= h($department) ?></td>
        <td><?= h($address) ?></td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('編集', array('name' => 'edit')); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border-style: none;"><div><?= $this->Form->submit('削除', array('name' => 'delete')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
