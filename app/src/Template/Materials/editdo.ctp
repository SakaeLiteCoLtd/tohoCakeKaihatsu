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
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="240"><strong>工場名（変更不可）</strong></td>
          <td width="240"><strong>仕入品コード（変更不可）</strong></td>
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
      <td width="480"><strong>仕入品仕入先</strong></td>
      </tr>
      <tr>
      <td><?= h($supplier_name) ?></td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('仕入品メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
