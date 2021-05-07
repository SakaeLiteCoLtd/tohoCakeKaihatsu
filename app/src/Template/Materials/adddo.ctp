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

<?= $this->Form->create($material, ['url' => ['action' => 'index']]) ?>

<nav class="large-3 medium-4 columns" style="width:70%">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('原料新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>工場・営業所名</strong></td>
            <td width="280"><strong>原料種類</strong></td>
        	</tr>
          <tr>
            <td><?= h($factory_name) ?></td>
            <td><?= h($type_name) ?></td>
        	</tr>
        </table>
        <table>
        <tr>
          <td width="280"><strong>原料コード</strong></td>
          <td width="280"><strong>グレード</strong></td>
        </tr>
        <tr>
          <td><?= h($this->request->getData('material_code')) ?></td>
          <td><?= h($this->request->getData('grade')) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="280"><strong>色</strong></td>
        <td width="280"><strong>メーカー</strong></td>
      </tr>
      <tr>
        <td><?= h($this->request->getData('color')) ?></td>
        <td><?= h($this->request->getData('maker')) ?></td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('原料メニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
