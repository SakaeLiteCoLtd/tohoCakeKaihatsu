<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php

if(!isset($_SESSION)){
  session_start();
  header('Expires:-1');
  header('Cache-Control:');
  header('Pragma:');
}

 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus($check_gyoumu);
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

<?= $this->Form->create($product, ['url' => ['action' => 'addformlength']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('tanni', array('type'=>'hidden', 'value'=>$this->request->getData('tanni'), 'label'=>false)) ?>
<?= $this->Form->control('weight', array('type'=>'hidden', 'value'=>$this->request->getData('weight'), 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$this->request->getData('customer_name'), 'label'=>false)) ?>
<?= $this->Form->control('status_kensahyou', array('type'=>'hidden', 'value'=>$this->request->getData('status_kensahyou'), 'label'=>false)) ?>
<?= $this->Form->control('tuikalength', array('type'=>'hidden', 'value'=>$tuikalength, 'label'=>false)) ?>
<?= $this->Form->control('status_kensahyou_flag', array('type'=>'hidden', 'value'=>$status_kensahyou_flag, 'label'=>false)) ?>
<?= $this->Form->control('ig_bank_modes', array('type'=>'hidden', 'value'=>$this->request->getData('ig_bank_modes'), 'label'=>false)) ?>

<?php

if($this->request->getData('status_kensahyou') == 0){
  $status_kensahyou_name = "表示";
}else{
  $status_kensahyou_name = "非表示";
}
?>

<?php
//<nav class="large-3 medium-4 columns">
?>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt;"><?= __('長さを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="150"><strong>工場名</strong></td>
        <td width="350"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        <td><?= h($customer_name) ?></td>
        </tr>
      </table>
      <table>
        <tr>
        <td width="280"><strong>品名</strong></td>
        <td><strong>単位</strong></td>
        <td><strong>単重(g/m)</strong></td>
        <td><strong>検査表に表示</strong></td>
        <td><strong>幅測定器モード番号</strong></td>
        </tr>
        <tr>
        <td><?= h($name) ?></td>
        <td><?= h($tanni) ?></td>
        <td><?= h($weight) ?></td>
        <td><?= h($status_kensahyou_name) ?></td>
        <td><?= h($this->request->getData('ig_bank_modes')) ?></td>
        </tr>
      </table>

      <?php if ($status_kensahyou_flag > 0): ?>

      <table>
      <tr>
        <td width="50"><strong>長さ（mm）</strong></td>
        <td width="50"><strong>カット長さ（mm）</strong></td>
        <td width="50"><strong>上限</strong></td>
        <td width="50"><strong>下限</strong></td>
        <td width="200"><strong>備考</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= $this->Form->control
      ('length'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true')) ?></td>
      <td><?= $this->Form->control
      ('length_cut'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true')) ?></td>
      <td><?= $this->Form->control
      ('length_upper_limit'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6)) ?></td>
      <td><?= $this->Form->control
      ('length_lower_limit'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6)) ?></td>
        <td><?= $this->Form->control('bik'.$k, array('type'=>'text', 'label'=>false)) ?></td>
      </tr>

      <?php endfor;?>

     </table>

     <?php else : ?>

      <table>
      <tr>
        <td width="50"><strong>長さ（mm）</strong></td>
        <td width="50"><strong>カット長さ（mm）</strong></td>
        <td width="200"><strong>備考</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= $this->Form->control
      ('length'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true')) ?></td>
      <td><?= $this->Form->control
      ('length_cut'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true')) ?></td>
      <?= $this->Form->control('length_upper_limit'.$k, array('type'=>'hidden', 'value'=>"", 'label'=>false)) ?>
      <?= $this->Form->control('length_lower_limit'.$k, array('type'=>'hidden', 'value'=>"", 'label'=>false)) ?>
      <td><?= $this->Form->control('bik'.$k, array('type'=>'text', 'label'=>false)) ?></td>
      </tr>

      <?php endfor;?>

     </table>

      <?php endif; ?>

    <table>
      <tbody class='sample non-sample'>
      <tr>
      <td style="border:none">　　　　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　　　　</td>
      <td style="border:none"><?= $this->Form->submit(('長さ追加'), array('name' => 'tuika')) ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確認'), array('name' => 'kakuninn')) ?></td>
      </tr>
  </tbody>
</table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
