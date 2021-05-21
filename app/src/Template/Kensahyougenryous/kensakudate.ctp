<?php
$username = $this->request->Session()->read('Auth.User.username');

header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
?>
<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<br>
<?php
     echo $htmlkensahyoukadou;
?>
<br>
<?php
     echo $htmlkensahyoumenu;
?>

<br>
<hr size="5" style="margin: 0rem">
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/sokuteidatatouroku.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subyobidashi.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');

$dateYMD = date('Y-m-d');
$dateYMD1 = strtotime($dateYMD);
$dayye = date('Y-m-d', strtotime('-1 day', $dateYMD1));

?>

<?= $this->Form->create($product, ['url' => ['action' => 'kensakudate']]) ?>

<?=$this->Form->hidden("product_code", array('type' => 'value', 'value' => $product_code)); ?>
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
  <table>
    <tbody class='sample non-sample'>
      <tr>
        <td style='width:130'>測定日</td>
        <td style='width:130'>管理No.</td>
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
            <td><?= h($date) ? $this->Html->link($date, ['action' => 'kensakuhyouji', 's' => $date."_".$product_code]) : '' ?></td>
            <td><?= h($product_code);?></td>
          </tr>
        <?php endfor;?>

    </tbody>
  </table>

  <br><br>

<?php else : ?>

  <div align="center"><strong><font color="blue"><?php echo $mes;?></font></strong></div>
  <br>
    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style='width:130'>測定日</td>
          <td style='width:130'>管理No.</td>
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
              <td><?= h($date) ? $this->Html->link($date, ['action' => 'kensakuhyouji', 's' => $date."_".$product_code]) : '' ?></td>
              <td><?= h($product_code);?></td>
            </tr>
          <?php endfor;?>

      </tbody>
    </table>

    <br><br>

<?php endif; ?>
