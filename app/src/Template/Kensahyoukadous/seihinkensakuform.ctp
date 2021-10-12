<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
$htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
$htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
$htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();

use App\myClass\classprograms\htmlLogin;//myClassフォルダに配置したクラスを使用
$htmlinputstaffctp = new htmlLogin();
$inputstaffctp = $htmlinputstaffctp->inputstaffctp();
?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyouseihinmenu' /><font size='4' color=black>製品関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/seihinkensakuform' /><font size='4' color=black>製品呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

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

<?= $this->Form->create($product, ['url' => ['action' => 'seihinkensakusyousai']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('詳細表示するデータを入力してください') ?></strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr class='children'>
          <td width="300"><strong>工場名</strong></td>
        </tr>
        <tr class='children'>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false, 'autofocus'=>true, 'id'=>"auto1"]) ?></td>
        </tr>
      </table>
      <br>
      <table>
      <tr class='children'>
        <td width="300"><strong>品名</strong></td>
      </tr>
      <tr class='children'>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"product_name_list")) ?>
        </td>
      </tr>
    </table>
    <br>
    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
