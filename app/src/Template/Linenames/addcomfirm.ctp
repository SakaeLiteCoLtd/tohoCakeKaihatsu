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

<?= $this->Form->create($linenames, ['url' => ['action' => 'adddo']]) ?>

<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$this->request->getData('machine_num'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('ライン新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下のデータを登録します。よろしければ「決定」ボタンを押してください。') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <?php if ($usercheck == 1): ?>

          <table>
        <tr>
        <td width="280"><strong>工場名</strong></td>
        </tr>
        <tr>
          <td><?= h($factory_name) ?></td>
        </tr>
      </table>
 
      <?= $this->Form->control('factory_name', array('type'=>'hidden', 'value'=>$factory_name, 'label'=>false)) ?>
      <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
          <?php else : ?>
            <?php endif; ?>

        <table>
        <tr>
        <td width="200"><strong>ライン番号</strong></td>
        <td width="200"><strong>ライン名</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('machine_num')) ?></td>
        <td><?= h($this->request->getData('name')) ?></td>
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
