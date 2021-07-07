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

<?= $this->Form->create($product, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
<?= $this->Form->control('namemoto', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>
<?= $this->Form->control('factory_name', array('type'=>'hidden', 'value'=>$factory_name, 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$customer_name, 'label'=>false)) ?>

<nav class="large-3 medium-4 columns">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報編集・削除') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red">
          <?= __('管理No.と得意先を変更する場合はデータを削除して新規登録をしなおしてください。') ?>
          </strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>

        <table>
        <tr>
          <td width="280"><strong>自社工場</strong></td>
          <td width="280"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($customer_name) ?></td>
        </tr>
      </table>

      <table>
          <tr>
            <td width="280"><strong>品名</strong></td>
            <td width="280"><strong>単位</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('name', array('type'=>'text', 'value'=>$name, 'label'=>false)) ?></td>
            <td><?= $this->Form->control('tanni', array('type'=>'text', 'value'=>$tanni, 'label'=>false)) ?></td>
        	</tr>
        </table>

     <br>

     <table>
      <tr>
      <td width="280"><strong>管理No.</strong></td>
      <td><strong>長さ（mm）</strong></td>
      <td><strong>削除</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($ProductName); $i++): ?>

      <tr>
      <td><?= h($ProductName[$i]["product_code"]) ?></td>
      <td><?= $this->Form->control
      ('length'.$i, array('type'=>'text', 'label'=>false, 'value'=>$ProductName[$i]["length"], 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true')) ?></td>
      <td><?= $this->Form->control('delete'.$i, array('type'=>'checkbox', 'label'=>false)) ?></td>
      </tr>

      <?= $this->Form->control('product_code'.$i, array('type'=>'hidden', 'value'=>$ProductName[$i]["product_code"], 'label'=>false)) ?>
      <?= $this->Form->control('num', array('type'=>'hidden', 'value'=>$i, 'label'=>false)) ?>

      <?php endfor;?>

     </table>

     <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
