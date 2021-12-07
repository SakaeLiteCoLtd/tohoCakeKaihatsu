<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<br>
<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kadous/yobidashidate' /><font size='4' color=black>稼働関係</font></a>
    </a></td>
  </tbody>
</table>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<?php
      $dayyetoy = date('Y');
      $dayyetom = date('n');
      $dayyetod = date('j');
?>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'view']]) ?>

<table>
        <thead>
            <tr class="parents">
            <td style='font-size: 10pt; width:80; height:60; border-width: 1px solid black;'><?= __('ライン番号') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('検査表開始時間') ?></td>
            <td style='font-size: 9pt; width:125; border-width: 1px solid black;'><?= __('リレーログ開始時間') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('検査表終了時間') ?></td>
            <td style='font-size: 9pt; width:125; border-width: 1px solid black;'><?= __('リレーログ終了時間') ?></td>
            <td style='font-size: 10pt; width:200; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('長さ(mm)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('数量(本)') ?></td>
            <td style='font-size: 9pt; width:80; border-width: 1px solid black;'><?= __('検査表') ?><br><?= __('使用重量(kg)') ?></td>
            <td style='font-size: 10pt; width:60; border-width: 1px solid black;'></td>
            </tr>
        </thead>

<tbody>
        <?php for($j=0; $j<count($arrAll); $j++): ?>
            <tr class='children'>
            <td><?= h($arrAll[$j]["machine_num"]) ?></td>
            <td style='font-size: 11pt'><?= h(substr($arrAll[$j]["start_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["start_datetime"], 11, 8)) ?></td>
            <td style='font-size: 11pt'><?= h(substr($arrAll[$j]["start_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["start_datetime"], 11, 8)) ?></td>
            <td style='font-size: 11pt'><?= h(substr($arrAll[$j]["finish_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["finish_datetime"], 11, 8)) ?></td>
            <td style='font-size: 11pt'><?= h(substr($arrAll[$j]["finish_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["finish_datetime"], 11, 8)) ?></td>
            <td style='font-size: 10pt'><?= h($arrAll[$j]["name"]) ?></td>
            <td style='font-size: 10pt'><?= h($arrAll[$j]["length"]) ?></td>
            <td style='font-size: 10pt'><?= h($arrAll[$j]["amount"]) ?></td>
            <td style='font-size: 10pt'><?= h($arrAll[$j]["sum_weight"]) ?></td>
              <td>
              <?php if ($arrAll[$j]["product_code"] !== "-"): ?>
              <?php
              echo $this->Form->submit("詳細" , ['name' => $arrAll[$j]["machine_num"]."_".$arrAll[$j]["product_code"]]) ;
              ?>
              <?php else : ?>
                <?= h("-") ?>
              <?php endif; ?>
              </td>
            </tr>
<?php endfor;?>

        </tbody>
    </table>
