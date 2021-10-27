<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/menu' /><font size='4' color=black>測定データ登録</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyousokuteidatas/kensakumenu' /><font size='4' color=black>登録データ呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>
<?php
$dateYMD = date('Y-m-d');
$dateYMD1 = strtotime($dateYMD);
$dayye = date('Y-m-d', strtotime('-1 day', $dateYMD1));
?>

<?= $this->Form->create($product, ['url' => ['action' => 'kensakudate']]) ?>

<?=$this->Form->hidden("product_code", array('type' => 'value', 'value' => $product_code)); ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
<br>
<div align="center"><strong><font color="blue"><?php echo "測定日絞り込み";?></font></strong></div>
<table>
  <tr>
    <td style="border:none"><strong>開始</strong></td>
    <td style="border:none"><strong>　　　</strong></td>
    <td style="border:none"><strong>終了</strong></td>
  </tr>
  <tr>
    <td style="border:none">
      <?= $this->Form->input('start', array('type'=>'date', 'minYear' => date('Y') - 20,
       'maxYear' => date('Y'), 'monthNames' => false, 'value' => $dayye, 'label'=>false)) ?></td>
    <td style="border:none">　～　</td>
    <td style="border:none">
      <?= $this->Form->input('end', array('type'=>'date', 'minYear' => date('Y') - 20,
       'maxYear' => date('Y'), 'monthNames' => false, 'value' => $dateYMD, 'label'=>false)) ?></td>
   </tr>
</table>
<br>
<table>
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('絞り込み'), array('name' => 'saerch')) ?></td>
    </tr>
  </tbody>
</table>

<br><br>

<?php if ($checksaerch < 1): ?>

  <div align="center"><strong><font color="blue"><?php echo $mes;?></font></strong></div>
<br>
<table class="white">
    <tbody class='sample non-sample'>
      <tr>
        <td style='width:130'>測定日</td>
        <td>製品名</td>
      </tr>

        <?php for($i=0; $i<=2; $i++): ?>
          <?php
          if(isset($arrDates[$i])){
            $date = $arrDates[$i];
          }else{
            $date = "";
          }
          ?>

          <tr>
            <td><?= h($date) ? $this->Html->link($date, ['action' => 'kensakuhyouji', 's' => $date."_".$machine_num."_".$product_code]) : '' ?></td>
            <td><?= h("　".$product_name."　");?></td>
          </tr>
        <?php endfor;?>

    </tbody>
  </table>

  <br><br>

<?php else : ?>

  <div align="center"><strong><font color="blue"><?php echo $mes;?></font></strong></div>
  <br>
  <table class="white">
      <tbody class='sample non-sample'>
        <tr>
          <td style='width:130'>測定日</td>
          <td>製品名</td>
        </tr>

          <?php for($i=0; $i<count($arrDates); $i++): ?>
            <?php
            if(isset($arrDates[$i])){
              $date = $arrDates[$i];
            }else{
              $date = "";
            }
            ?>

            <tr>
            <td><?= h($date) ? $this->Html->link($date, ['action' => 'kensakuhyouji', 's' => $date."_".$machine_num."_".$product_code]) : '' ?></td>
            <td><?= h("　".$product_name."　");?></td>
            </tr>
          <?php endfor;?>

      </tbody>
    </table>

    <br><br>

<?php endif; ?>
<table>
  <tbody class='sample non-sample'>
    <tr>
    <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>
