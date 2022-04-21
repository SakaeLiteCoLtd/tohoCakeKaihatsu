<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlproductmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlproductmenu = new htmlproductmenu();
 $htmlproduct = $htmlproductmenu->productmenus($check_gyoumu);
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrCustomer_name_list = json_encode($arrCustomer_name_list);//jsに配列を受け渡すために変換

    for($i=0; $i<$countFactories; $i++){
        ${"factory_id".$i} = json_encode(${"factory_id".$i});//jsに配列を受け渡すために変換
        ${"arrProduct_name_list".$i} = json_encode(${"arrProduct_name_list".$i});//jsに配列を受け渡すために変換
      }
?>

<?php for($i=0; $i<$countFactories; $i++): ?>

<script>

$(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrCustomer_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#customer_name_list").autocomplete({
        source: wordlist
      });
  });

$(document).ready(function() {
      $("#auto1").focusout(function() {
        var inputNumber = $("#auto1").val();

          if (inputNumber == <?php echo ${"factory_id".$i}; ?>) {

            $(function() {
                // 入力補完候補の単語リスト
                let wordlist = <?php echo ${"arrProduct_name_list".$i}; ?>
                // 入力補完を実施する要素に単語リストを設定
                $("#product_name_list").autocomplete({
                  source: wordlist
                });
            });
            
          }

    })
});

</script>

<?php endfor;?>

<?php
     echo $htmllogin;
?>
<?php
  //   echo $htmlproduct;
?>

<?= $this->Form->create($product, ['url' => ['action' => 'editpreform']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('製品情報検索') ?></strong></legend>
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
      <td width="400" colspan=2><strong>顧客名</strong></td>
        <td width="300"><strong>品名</strong></td>
      </tr>
      <tr>
      <td style="border-right-style:none">
        <?= $this->Form->control('customer_name', array('type'=>'text', 'label'=>false, 'id'=>"customer_name_list")) ?>
      </td>
      <td style="border-left-style:none"><?= $this->Form->submit(('顧客絞込'), array('name' => 'customer')) ?></td>
        <td>
        <?= $this->Form->control('namepro', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"product_name_list")) ?>
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
