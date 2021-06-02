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

<form method="post" action="/materialSuppliers/index">

<?= $this->Form->create($materialSupplier, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns" style="width:70%">
    <?= $this->Form->create($materialSupplier) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('原料仕入先情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>工場・営業所名</strong></td>
        	</tr>
          <tr>
            <td><?= h($materialSupplier->factory->name) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>原料仕入先名</strong></td>
            <td width="280"><strong>支店名</strong></td>
        	</tr>
          <tr>
            <td><?= h($materialSupplier['name']) ?></td>
            <td><?= h($materialSupplier['office']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="280"><strong>電話番号</strong></td>
            <td width="280"><strong>FAX</strong></td>
        	</tr>
          <tr>
            <td><?= h($materialSupplier['tel']) ?></td>
            <td><?= h($materialSupplier['fax']) ?></td>
        	</tr>
        </table>
        <table>
          <tr>
            <td width="180"><strong>部署名</strong></td>
            <td width="380"><strong>住所</strong></td>
        	</tr>
          <tr>
            <td><?= h($materialSupplier['department']) ?></td>
            <td><?= h($materialSupplier['address']) ?></td>
        	</tr>
        </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('原料仕入先メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
</form>
