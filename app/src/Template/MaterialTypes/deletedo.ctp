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

<form method="post" action="/materialTypes/index">

<?= $this->Form->create($materialType, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="sample non-sample">
    <?= $this->Form->create($materialType) ?>
    <fieldset>

      <legend><strong style="font-size: 15pt; color:red"><?= __('原料種類情報削除') ?></strong></legend>
        <br>
        <table align="center">
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="280"><strong>自社工場</strong></td>
          <td width="280"><strong>原料種類</strong></td>
        </tr>
        <tr>
        <td><?= h($materialType->factory->name) ?></td>
          <td><?= h($materialType['type']) ?></td>
        </tr>
      </table>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('原料種類メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
