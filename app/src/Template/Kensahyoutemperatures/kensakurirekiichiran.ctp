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
      <font size='4'>　>>　</font><a href='/Kensahyoutemperatures/menu' /><font size='4' color=black>成形温度登録</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoutemperatures/kensakumenu' /><font size='4' color=black>登録データ呼出</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyougenryous/kensakurirekipre' /><font size='4' color=black>登録履歴呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>
<?php
$dateYMD = date('Y-m-d');
$dateYMD1 = strtotime($dateYMD);
$dayye = date('Y-m-d', strtotime('-1 day', $dateYMD1));
?>

<?= $this->Form->create($product, ['url' => ['action' => 'kensakurirekiichiran']]) ?>

<?=$this->Form->hidden("product_code", array('type' => 'value', 'value' => $product_code)); ?>
<?=$this->Form->hidden("machine_num", array('type' => 'value', 'value' => $machine_num)); ?>
<br>
<div align="center"><strong><font color="blue"><?php echo "改定日絞り込み";?></font></strong></div>
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

<table>
      <tr>
      <td width="180" style='background-color: #FFFFCC'><strong>号機番号</strong></td>
      <td width="280" style='background-color: #FFFFCC'><strong>製品名</strong></td>
      </tr>
      <tr>
      <td style='background-color: #FFFFCC'><?= h($machine_num) ?></td>
      <td style='background-color: #FFFFCC'><?= h($product_name) ?></td>
      </tr>
    </table>
    <br>
<?php if ($checksaerch < 1): ?>

  <div align="center"><strong><font color="blue"><?php echo $mes;?></font></strong></div>
<br>
<table>
    <tbody class='sample non-sample'>
      <tr class='parents'>
        <td style='width:250'>温度条件登録日</td>
        <td style='width:250'>更新日</td>
        <td style='width:100'></td>
      </tr>

        <?php for($i=0; $i<=2; $i++): ?>
          <?php
          if(isset($arrDates[$i]["created_at"])){
            $created_at = $arrDates[$i]["created_at"];
            $updated_at = $arrDates[$i]["updated_at"];
            $syousai = "詳細表示";
          }else{
            $created_at = "";
            $updated_at = "";
            $syousai = "";
          }
          ?>

          <tr>
            <td style='background-color: #FFFFCC'><?= h($created_at);?></td>
            <td style='background-color: #FFFFCC'><?= h($updated_at);?></td>
            <td style='background-color: #FFFFCC'><?= h("詳細表示") ? $this->Html->link($syousai, ['action' => 'kensakurirekihyouji', 's' => $created_at."_".$machine_num."_".$product_code]) : '' ?></td>
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
      <tr class='parents'>
        <td style='width:250'>温度条件登録日</td>
        <td style='width:250'>更新日</td>
        <td style='width:100'></td>
      </tr>

          <?php for($i=0; $i<count($arrDates); $i++): ?>
            <?php
          if(isset($arrDates[$i]["created_at"])){
            $created_at = $arrDates[$i]["created_at"];
            $updated_at = $arrDates[$i]["updated_at"];
            $syousai = "詳細表示";
          }else{
            $created_at = "";
            $updated_at = "";
            $syousai = "";
          }

            ?>

            <tr>
            <td style='background-color: #FFFFCC'><?= h($created_at);?></td>
            <td style='background-color: #FFFFCC'><?= h($updated_at);?></td>
            <td style='background-color: #FFFFCC'><?= h("詳細表示") ? $this->Html->link($syousai, ['action' => 'kensakurirekihyouji', 's' => $created_at."_".$product_code]) : '' ?></td>
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
