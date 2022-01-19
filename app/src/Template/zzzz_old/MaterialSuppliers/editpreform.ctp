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

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrMaterialSupplier_name_list = json_encode($arrMaterialSupplier_name_list);//jsに配列を受け渡すために変換
?>

<script>

$(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrMaterialSupplier_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#MaterialSupplier_name_list").autocomplete({
        source: wordlist
      });
  });

</script>

<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterial;
     ?>

<?= $this->Form->create($materialSupplier, ['url' => ['action' => 'detail']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($materialSupplier) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('原料仕入先情報検索') ?></strong></legend>
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
        <td width="320"><strong>原料仕入先名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"MaterialSupplier_name_list")) ?>
        </td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('次へ', array('name' => 'kensaku')); ?></div></td>
       </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
