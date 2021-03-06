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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<?php
$arrMaterialSuppliers_name_list = json_encode($arrMaterialSuppliers_name_list);//jsに配列を受け渡すために変換
$arrMaterials_name_list = json_encode($arrMaterials_name_list);//jsに配列を受け渡すために変換
?>

<script>

$(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrMaterialSuppliers_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#materialSuppliers_name_list").autocomplete({
        source: wordlist
      });
  });

  $(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrMaterials_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#Materials_name_list").autocomplete({
        source: wordlist
      });
  });

</script>

<?= $this->Form->create($materials, ['url' => ['action' => 'editpreform']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($materials) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('仕入品情報検索') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('詳細表示するデータを入力してください') ?></strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td><strong>工場名</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false, 'autofocus'=>true, 'id'=>"auto1"]) ?></td>
        </tr>
      </table>
      <table>
      <tr>
      <td width="400" colspan=2><strong>仕入先名</strong></td>
        <td width="320"><strong>仕入品名</strong></td>
      </tr>
      <tr>
      <td style="border-right-style:none">
        <?= $this->Form->control('materialSuppliername', array('type'=>'text', 'label'=>false, 'id'=>"materialSuppliers_name_list")) ?>
      </td>
      <td style="border-left-style:none"><?= $this->Form->submit(('仕入先絞込'), array('name' => 'materialSupplier')) ?></td>
        <td>
        <?= $this->Form->control('namematerial', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"Materials_name_list")) ?>
        </td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(('次へ'), array('name' => 'next')) ?></td>
       </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
