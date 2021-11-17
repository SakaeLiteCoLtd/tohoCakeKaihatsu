<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmllinenamemenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmllinenamemenu = new htmllinenamemenu();
 $htmllinename = $htmllinenamemenu->linenamemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmllinename;
?>

<form method="post" action="/linenames/editconfirm">

<?= $this->Form->create($linename, ['url' => ['action' => 'addcomfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($linename) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('ライン情報編集') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>ライン</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'autofocus'=>true, 'autocomplete'=>"off")) ?></td>
        </tr>
      </table>

      <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
