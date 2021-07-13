<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproduct;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報編集・削除') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr align="center"><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
        <td width="180"><strong>自社工場</strong></td>
          <td width="380"><strong>得意先</strong></td>
        </tr>
        <tr>
        <td><?= h($this->request->getData('factory_name')) ?></td>
        <td><?= h($this->request->getData('customer_name')) ?></td>
        </tr>
      </table>

      <table>
          <tr>
            <td width="380"><strong>品名</strong></td>
            <td width="180"><strong>単位</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name')) ?></td>
            <td><?= h($this->request->getData('tanni')) ?></td>
        	</tr>
        </table>

     <br>

     <table>
      <tr>
      <td><strong>管理No.</strong></td>
      <td width="300"><strong>品名</strong></td>
      <td><strong>長さ（mm）</strong></td>
      </tr>
      
      <?php for($i=0; $i<count($arrupdateproduct); $i++): ?>

      <tr>
      <td><?= h($arrupdateproduct[$i]["product_code"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["name"]) ?></td>
      <td><?= h($arrupdateproduct[$i]["length"]) ?></td>
      </tr>

      <?php endfor;?>

     </table>

     <?php if (count($arrdeleteproduct) > 0) : ?>

     <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('以下削除') ?></strong></td></tr>
          </tbody>
        </table>

        <table>
      <tr>
      <td><strong>管理No.</strong></td>
      <td width="300"><strong>品名</strong></td>
      <td><strong>長さ（mm）</strong></td>
      </tr>

      <?php for($i=0; $i<count($arrdeleteproduct); $i++): ?>

        <tr>
        <td><?= h($arrdeleteproduct[$i]["product_code"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["name"]) ?></td>
        <td><?= h($arrdeleteproduct[$i]["length"]) ?></td>
        </tr>

        <?php endfor;?>

     </table>

     <?php else : ?>
      <?php endif; ?>

    </fieldset>

    <table align="center">
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div align="center"><?= $this->Form->submit('製品メニュートップへ戻る', array('name' => 'top')); ?></div></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
