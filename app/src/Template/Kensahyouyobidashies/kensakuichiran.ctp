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

<?= $this->Form->create($product, ['url' => ['action' => 'kensakuichiran']]) ?>

<br><br><br>

<table>
  <tbody style="background-color: #FFFFCC">
    <tr class="parents">
      <td width="400"><strong>製品名（一部のみ入力可）</strong></td>
      <td width="150"><strong>ライン</strong></td>
    </tr>
    <tr>
      <td><?= h($product_name) ?></td>
      <td><?= h($linename) ?></td>
      <td style="border: none; background-color: #E6FFFF"><strong><?= h("　で検索しています。　") ?></strong></td>
    </tr>
  </tbody>
</table>
  <br><br>

  <table class="top_big">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<?php if ($check == 0): ?>

<table>
        <thead>
            <tr class="parents">
            <td style='width:60; height:60; border-width: 1px solid black;'><?= __('No.') ?></td>
            <td style='height:60; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='width:100; height:60; border-width: 1px solid black;'><?= __('ライン') ?></td>
            <td style='width:150; height:60; border-width: 1px solid black;' colspan='2'><?= __('検査表画像・規格') ?></td>
            <td style='width:150; height:60; border-width: 1px solid black;' colspan='2'><?= __('原料・温度条件') ?></td>

            <?php if ($account_check == 0): ?>
              <?php else : ?>

            <td style='width:100; height:60; border-width: 1px solid black;'><?= __('複製') ?></td>
            <td style='width:100; height:60; border-width: 1px solid black;'><?= __('削除') ?></td>

            <?php endif; ?>

            </tr>
        </thead>

        <tbody>
        <?php for($j=0; $j<count($arrKensahyous); $j++): ?>
            <tr class='children'>
              <td><?= h($i) ?></td>
              <td><?= h("　".$arrKensahyous[$j]["product_name"]."　") ?></td>
                <td><?= h($arrKensahyous[$j]["Linename"]) ?></td>

                <?php if ($arrKensahyous[$j]["kikaku"] !== "登録済み"): ?>
                 <td colspan='2'>

                 <?php if ($account_check == 0): ?>
                  <?= h("未登録") ?>
                  <?php else : ?>
                    <?php
                  echo $this->Form->submit("登録" , ['name' => "kikaku_".$arrKensahyous[$j]["product_code"]]) ;
                 ?>
                    <?php endif; ?>


                  </td>
                <?php else : ?>
                  <td style='border-right-style:none'><?= h($arrKensahyous[$j]["kikaku"]) ?></td>
                  <td style='border-left-style:none'>
                  <?php
                  echo $this->Form->submit("表示" , ['name' => "kikaku_".$arrKensahyous[$j]["product_code"]]) ;
                  ?>
                </td>
                <?php endif; ?>

                <?php if ($arrKensahyous[$j]["seikeijouken"] !== "登録済み"): ?>
                 <td colspan='2'>

                 <?php if ($account_check == 0): ?>
                  <?= h("未登録") ?>
                  <?php else : ?>

                 <?php
                  echo $this->Form->submit("登録" , ['name' => $arrKensahyous[$j]["product_code"]]) ;
                 ?>
                  </td>

                  <?php endif; ?>

                <?php else : ?>
                  <td style='border-right-style:none'><?= h($arrKensahyous[$j]["seikeijouken"]) ?></td>
                  <td style='border-left-style:none'>
                  <?php
                  echo $this->Form->submit("表示" , ['name' => $arrKensahyous[$j]["machine_num"]."_".$arrKensahyous[$j]["product_code"]]) ;
                  ?>
                </td>
                <?php endif; ?>

                <?php if ($account_check == 0): ?>
              <?php else : ?>

                <?php if ($arrKensahyous[$j]["kikaku"] === "登録済み" && $arrKensahyous[$j]["seikeijouken"] === "登録済み"): ?>
                 <td>
                 <?php
                  echo $this->Form->submit("複製" , ['name' => $arrKensahyous[$j]["machine_num"]."_".$arrKensahyous[$j]["product_code"]]) ;
                  ?>
                  </td>
                <?php else : ?>
                  <td><?= h("-") ?></td>
                <?php endif; ?>

                  <td>
                 <?php
                  echo $this->Form->submit("削除" , ['name' => $arrKensahyous[$j]["machine_num"]."_".$arrKensahyous[$j]["product_code"]]) ;
                 ?>
                  </td>

                  <?php endif; ?>

            </tr>

          <?php
          $i = $i + 1;
          ?>

<?php endfor;?>

        </tbody>
    </table>

    <?php else : ?>
    <?php endif; ?>

    <?php if ($account_check == 0): ?>

    <br><br>
    <table>
        <tbody class='sample non-sample'>
          <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          </tr>
        </tbody>
      </table>
    <br><br>

    <?php else : ?>

      <br><br>
    <table>
        <tbody class='sample non-sample'>
          <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
            <td style="border:none"><?= $this->Form->submit(__('新規登録'), array('name' => 'add')) ?></td>
          </tr>
        </tbody>
      </table>
    <br><br>

      <?php endif; ?>
