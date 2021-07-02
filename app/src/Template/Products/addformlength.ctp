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
  //   echo $htmlproduct;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'addformlength']]) ?>
<br><br><br>
<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$this->request->getData('factory_id'), 'label'=>false)) ?>
<?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$this->request->getData('name'), 'label'=>false)) ?>
<?= $this->Form->control('customer_name', array('type'=>'hidden', 'value'=>$this->request->getData('customer_name'), 'label'=>false)) ?>
<?= $this->Form->control('tuikalength', array('type'=>'hidden', 'value'=>$tuikalength, 'label'=>false)) ?>

<nav class="large-3 medium-4 columns">

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
          <td width="280"><strong>自社工場</strong></td>
        </tr>
        <tr>
        <td><?= h($factory_name) ?></td>
        </tr>
      </table>


      <table>
      <tr>
        <td width="320"><strong>得意先</strong></td>
        <td><strong>品名</strong></td>
        <td width="50"><strong>長さ（mm）</strong></td>
      </tr>
      
      <?php for($k=1; $k<=$tuikalength; $k++): ?>

      <tr>
      <td><?= h($customer_name) ?></td>
      <td><?= h($name) ?></td>
      <td><?= $this->Form->control
      ('length'.$k, array('type'=>'text', 'label'=>false, 'pattern' => '^[0-9.-]+$', 'title'=>'半角数字で入力して下さい。', 'size'=>6, 'required' => 'true')) ?></td>
      </tr>

      <?php endfor;?>

     </table>

    <table>
      <tbody class='sample non-sample'>
      <tr>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　</td>
      <td style="border:none"><?= $this->Form->submit(('長さ追加'), array('name' => 'tuika')) ?></td>
      <td style="border:none"><?= $this->Form->submit(('登録確認'), array('name' => 'kakuninn')) ?></td>
      </tr>
  </tbody>
</table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
