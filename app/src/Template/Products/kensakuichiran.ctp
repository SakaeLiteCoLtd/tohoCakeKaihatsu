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

<?= $this->Form->create($product, ['url' => ['action' => 'kensakuichiran']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('登録済み情報一覧') ?></strong></legend>
        <br>

    <table>
          <tr>
          <td width="280"><strong>製品名</strong></td>
          <td width="100"><strong>長さ</strong></td>
          <td width="180"><strong>製品コード</strong></td>
          <td width="180"><strong>使用工場</strong></td>
        	</tr>

        <?php for($i=0; $i<count($Products); $i++): ?>

          <tr>
          <td><?= h($Products[$i]["name"]) ?></td>
          <td><?= h($Products[$i]["length"]) ?></td>
          <td><?= h($Products[$i]["product_code"]) ?></td>
          <td><?= h($Products[$i]["factory"]["name"]) ?></td>
          </tr>

      <?php endfor;?>

    </table>
    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        </tr>
      </tbody>
    </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
