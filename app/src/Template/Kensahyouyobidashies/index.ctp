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
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査表呼出</font></a>
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

<br><br><br>

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
                <td><?= h($arrKensahyous[$j]["product_name"]) ?></td>
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
