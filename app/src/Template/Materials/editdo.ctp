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

<form method="post" action="/materials/index">

<?= $this->Form->create($material, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('原料情報編集') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>自社工場</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        </tr>
      </table>
        <table>
        <tr>
        <td width="260"><strong>原料コード</strong></td>
        <td width="200"><strong>単位</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('material_code')) ?></td>
        <td><?= h($this->request->getData('tanni')) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>原料名</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('name')) ?></td>
        </tr>
      </table>

      <table>
        <tr>
          <td width="480"><strong>原料種類</strong></td>
        </tr>
        <tr>
        <td><?= h($type_name) ?></td>
        </tr>
      </table>

      <table>
      <tr>
      <td width="480"><strong>原料仕入先</strong></td>
      </tr>
      <tr>
      <td><?= h($supplier_name) ?></td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('原料メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
