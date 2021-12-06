<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlaccountmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlaccountmenu = new htmlaccountmenu();
 $htmlproductdeletemenus = $htmlaccountmenu->productdeletemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlproductdeletemenus;
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
      for($i=0; $i<$countFactories; $i++){
        ${"factory_id".$i} = json_encode(${"factory_id".$i});//jsに配列を受け渡すために変換
        ${"arrProduct_name_list".$i} = json_encode(${"arrProduct_name_list".$i});//jsに配列を受け渡すために変換
      }
?>

<?php for($i=0; $i<$countFactories; $i++): ?>

<script>

$(document).ready(function() {
      $("#auto1").focusout(function() {
        var inputNumber = $("#auto1").val();

          if (inputNumber == <?php echo ${"factory_id".$i}; ?>) {
     //       $("#auto2").text(inputNumber);

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

<?= $this->Form->create($product, ['url' => ['action' => 'productdeletedconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('削除済み製品復元') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('復元するデータを入力してください') ?></strong></td></tr>
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
        <td width="300"><strong>品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"product_name_list")) ?>
        </td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <br>
    <?php

define('MAX','20'); // 1ページの記事の表示数

$Product_name_lists_num = count($Product_name_lists); // トータルデータ件数
 
$max_page = ceil($Product_name_lists_num / MAX); // トータルページ数※ceilは小数点を切り捨てる関数

if(!isset($_GET['page_id'])){ // $_GET['page_id'] はURLに渡された現在のページ数
    $now = 1; // 設定されてない場合は1ページ目にする
}else{
    $now = $_GET['page_id'];
}

$start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか
 
// array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
$disp_data = array_slice($Product_name_lists, $start_no, MAX, true);
 
?>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('復元するデータを入力してください') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

<table>
        <thead>
            <tr class="parents">
            <td style='background-color: #f0e68c; border-width: 1px solid black;'><?= __('No.') ?></td>
            <td style='background-color: #f0e68c; border-width: 1px solid black;'><?= __('工場名') ?></td>
            <td style='background-color: #f0e68c; border-width: 1px solid black;'><?= __('製品コード') ?></td>
            <td style='background-color: #f0e68c; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='background-color: #f0e68c; border-width: 1px solid black;'><?= __('長さ(mm)') ?></td>
            </tr>
        </thead>

        <tbody>

        <?php
$h = 1;
?>

<?php
foreach($disp_data as $val){ // データ表示
?>

  <tr class='children'>
  <td><?= h($h) ?></td>
    <td><?= h($val["factory"]) ?></td>
    <td><?= h($val["product_code"]) ?></td>
    <td><?= h($val["name"]) ?></td>
    <td><?= h($val["length"]) ?></td>
</tr>

<?php
$h = $h + 1;
?>

<?php
}
?>

</tbody>
    </table>

<br><br>

<table>
<div align="center">

<?php

if($max_page > 7){
  $max_page_max = 7;
}else{
  $max_page_max = $max_page;
}

echo '全件数'. $Product_name_lists_num. '件'. '　'; // 全データ数の表示です。
 
if($now > 1){ // リンクをつけるかの判定
  echo '<a href="index?page_id='.($now - 1). '">前へ</a>　';
} else {
    echo '前へ'. '　';
}
 
for($i = 1; $i <= $max_page_max; $i++){
    if ($i == $now) {
        echo $now. '　'; 
    } else {
      echo '<a href="index?page_id='. $i. '">'.$i.'</a>　';
    }
}
 
if($now < $max_page){ // リンクをつけるかの判定
    echo '<a href="index?page_id='.($now + 1). '">次へ</a>';
  } else {
    echo '次へ';
}

?>

</div>
</table>
<br><br>
    <?= $this->Form->end() ?>
  </nav>
