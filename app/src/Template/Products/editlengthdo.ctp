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

<?= $this->Form->create($product, ['url' => ['action' => 'index']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('tuikalength', array('type'=>'hidden', 'value'=>$tuikalength, 'label'=>false)) ?>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品長さ追加') ?></strong></legend>
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
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt;"><?= __('登録済み長さ一覧') ?></strong></td></tr>
          </tbody>
        </table>

    <table>
      <tr>
        <td><strong>製品コード</strong></td>
        <td><strong>品名</strong></td>
        <td><strong>長さ（mm）</strong></td>
        <td><strong>カット長さ（mm）</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($ProductName); $i++): ?>

      <tr>
      <td><?= h($ProductName[$i]["product_code"]) ?></td>
      <td><?= h($ProductName[$i]["name"]) ?></td>
      <td><?= h($ProductName[$i]["length"]) ?></td>
      <td><?= h($ProductName[$i]["length_cut"]) ?></td>
      </tr>

      <?php endfor;?>

     </table>

     <br>
     <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>

     <table>
      <tr>
        <td><strong>品名</strong></td>
        <td><strong>長さ（mm）</strong></td>
        <td><strong>カット長さ（mm）</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= h($name) ?></td>
      <td><?= h(${"length".$k}) ?></td>
      <?= $this->Form->control('length'.$k, array('type'=>'hidden', 'value'=>${"length".$k}, 'label'=>false)) ?>
      <td><?= h(${"length_cut".$k}) ?></td>
      <?= $this->Form->control('length_cut'.$k, array('type'=>'hidden', 'value'=>${"length_cut".$k}, 'label'=>false)) ?>

      <?php endfor;?>

     </table>

     <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('製品メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
