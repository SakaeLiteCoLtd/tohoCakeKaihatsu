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

<?= $this->Form->create($material, ['url' => ['action' => 'kensakuichiran']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($material) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('登録済み情報一覧') ?></strong></legend>
        <br>

    <table>
          <tr>
          <td><strong>使用工場</strong></td>
          <td><strong>仕入品コード</strong></td>
          <td><strong>仕入品名</strong></td>
          <td><strong>仕入先</strong></td>
        	</tr>

        <?php for($i=0; $i<count($Materials); $i++): ?>

          <tr>
          <td><?= h($Materials[$i]["factory"]["name"]) ?></td>
          <td><?= h($Materials[$i]["material_code"]) ?></td>
          <td><?= h($Materials[$i]["name"]) ?></td>
          <td><?= h($Materials[$i]["material_supplier"]["name"]) ?></td>
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
