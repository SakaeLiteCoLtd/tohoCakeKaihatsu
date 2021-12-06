<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyouseihinyobidashimenus = $htmlkensahyoukadoumenu->seihinyobidashimenus();

 $i = 1;

?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br>
<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査規格</font></a>
    </td>
  </tbody>
</table>

<br><br>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrProduct_name_list = json_encode($arrProduct_name_list);//javaに配列を受け渡すために変換
?>

<script>

  $(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrProduct_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#product_name_list").autocomplete({
        source: wordlist
      });
  });

</script>

<?= $this->Form->create($product, ['url' => ['action' => 'index']]) ?>

<br><br>
<div align="center"><font color="red" size="3"><?= __($mess) ?></font></div>
<br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr class="parents">
    <td width="400"><strong>製品名（一部のみ入力可）</strong></td>
      <td width="150"><strong>ライン番号</strong></td>
    </tr>
    <tr>
      <td style="border: 1px solid black">
        <?= $this->Form->control('product_name', array('type'=>'text', 'label'=>false, 'id'=>"product_name_list")) ?>
      </td>
      <td><?= $this->Form->control('machine_num', ['options' => $arrGouki, 'label'=>false]) ?></td>
    </tr>
  </tbody>
</table>
<br>
  <table align="center">
    <tbody class='sample non-sample'>
      <tr>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border:none"><?= $this->Form->submit(('検索'), array('name' => 'next')) ?></td>
      </tr>
    </tbody>
  </table>
  <br><br>

  <?php
/*
<table>
        <thead>
            <tr class="parents">
            <td style='width:60; height:60; border-width: 1px solid black;'><?= __('No.') ?></td>
            <td style='height:60; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='width:100; height:60; border-width: 1px solid black;'><?= __('ライン番号') ?></td>
            <td style='width:150; height:60; border-width: 1px solid black;'><?= __('検査表画像・規格') ?></td>
            <td style='width:150; height:60; border-width: 1px solid black;'><?= __('原料・温度条件') ?></td>
            </tr>
        </thead>

        <tbody>
        <?php for($j=0; $j<count($arrKensahyous); $j++): ?>
            <tr class='children'>
              <td><?= h($i) ?></td>
                <td><?= h("　".$arrKensahyous[$j]["product_name"]."　") ?></td>
                <td><?= h($arrKensahyous[$j]["machine_num"]) ?></td>

                <?php if ($arrKensahyous[$j]["kikaku"] !== "登録済み"): ?>
                 <td>
                 <?php
                  echo $this->Form->submit("登録" , ['name' => "kikaku_".$arrKensahyous[$j]["product_code"]]) ;
                 ?>
                  </td>
                <?php else : ?>
                  <td><?= h($arrKensahyous[$j]["kikaku"]) ?></td>
                <?php endif; ?>

                <?php if ($arrKensahyous[$j]["seikeijouken"] !== "登録済み"): ?>
                 <td>
                 <?php
                  echo $this->Form->submit("登録" , ['name' => $arrKensahyous[$j]["product_code"]]) ;
                 ?>
                  </td>
                <?php else : ?>
                  <td><?= h($arrKensahyous[$j]["seikeijouken"]) ?></td>
                <?php endif; ?>

            </tr>

          <?php
          $i = $i + 1;
          ?>

        <?php endfor;?>

        </tbody>
    </table>
    <br><br>

*/
    ?>

    <?php

define('MAX','20'); // 1ページの記事の表示数

$arrKensahyous_num = count($arrKensahyous); // トータルデータ件数
 
$max_page = ceil($arrKensahyous_num / MAX); // トータルページ数※ceilは小数点を切り捨てる関数

if(!isset($_GET['page_id'])){ // $_GET['page_id'] はURLに渡された現在のページ数
    $now = 1; // 設定されてない場合は1ページ目にする
}else{
    $now = $_GET['page_id'];
}

$start_no = ($now - 1) * MAX; // 配列の何番目から取得すればよいか
 
// array_sliceは、配列の何番目($start_no)から何番目(MAX)まで切り取る関数
$disp_data = array_slice($arrKensahyous, $start_no, MAX, true);
 
?>

<table>
        <thead>
            <tr class="parents">
            <td style='width:60; height:60; border-width: 1px solid black;'><?= __('No.') ?></td>
            <td style='height:60; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='width:100; height:60; border-width: 1px solid black;'><?= __('ライン番号') ?></td>
            <td style='width:150; height:60; border-width: 1px solid black;'><?= __('検査表画像・規格') ?></td>
            <td style='width:150; height:60; border-width: 1px solid black;'><?= __('原料・温度条件') ?></td>
            <td style='width:200; height:60; border-width: 1px solid black;'><?= __('データ作成日') ?></td>
            </tr>
        </thead>

        <tbody>

    <?php

foreach($disp_data as $val){ // データ表示

  ?>

  <tr class='children'>
  <td><?= h($i) ?></td>
    <td><?= h("　".$val["product_name"]."　") ?></td>
    <td><?= h($val["machine_num"]) ?></td>

    <?php if ($val["kikaku"] !== "登録済み"): ?>
     <td>
     <?php
      echo $this->Form->submit("登録" , ['name' => "kikaku_".$val["product_code"]]) ;
     ?>
      </td>
    <?php else : ?>
      <td><?= h($val["kikaku"]) ?></td>
    <?php endif; ?>

    <?php if ($val["seikeijouken"] !== "登録済み"): ?>
     <td>
     <?php
      echo $this->Form->submit("登録" , ['name' => $val["product_code"]]) ;
     ?>
      </td>
    <?php else : ?>
      <td><?= h($val["seikeijouken"]) ?></td>
    <?php endif; ?>

    <?php if ($val["seikeijouken"] !== "登録済み" || $val["kikaku"] !== "登録済み"): ?>
      <td>-</td>
    <?php else : ?>
        <td><?= h($val["datetime"]) ?></td>
    <?php endif; ?>


</tr>

<?php
$i = $i + 1;
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
/*
for($i = 1; $i <= $max_page; $i++){ // 最大ページ数分リンクを作成
    if ($i == $now) { // 現在表示中のページ数の場合はリンクを貼らない

        echo $now. '　'; 

    } else {

        echo '<a href="index?page_id='. $i. '">'.$i.'</a>';

    }
}
*/

if($max_page > 7){
  $max_page_max = 7;
}else{
  $max_page_max = $max_page;
}

echo '全件数'. $arrKensahyous_num. '件'. '　'; // 全データ数の表示です。
 
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
