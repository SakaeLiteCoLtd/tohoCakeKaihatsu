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
<?php
      echo $htmlkensahyoukadou;
 ?>
 <br>
 <?php
      echo $htmlkensahyoumenu;
 ?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'addform']]) ?>

<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('tuikaseikeiki', array('type'=>'hidden', 'value'=>$tuikaseikeiki, 'label'=>false)) ?>

<table width="1000">
    <tr>
      <td width="500" colspan="2" nowrap="nowrap" style="height: 60px"><strong>検査成績書</strong><br>（兼　成形条件表・梱包仕様書・作業手順書）</td>
      <td width="250" nowrap="nowrap" style="height: 30px">製品名</td>
      <td width="250" nowrap="nowrap" style="height: 30px"><?= h($product_code) ?></td>
    </tr>
    <tr>
      <td width="250" nowrap="nowrap" style="height: 30px">管理No</td>
      <td width="250" style="height: 30px"><?= h($product_code) ?></td>
      <td width="250" rowspan='2' style="height: 30px">顧客名</td>
      <td width="250" rowspan='2' style="height: 30px"><?= h($product_code) ?></td>
    </tr>
    <tr>
      <td width="250" nowrap="nowrap" style="height: 30px">改訂日</td>
      <td width="250" style="height: 30px"><?= h($product_code); ?></td>
    </tr>
    <tr>
      <td width="1000" colspan="4" nowrap="nowrap" style="height: 400px">画像</td>
    </tr>
</table>

<br>

<table align="right">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('成形機内原料追加'), array('name' => 'genryoutuika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('成形機追加'), array('name' => 'seikeikituika')) ?></td>
      <td style="border:none">　　</td>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
      <td style="border:none">　　　　　　　　　　　</td>
      <td style="border:none">　　　　　　　　　　　</td>
    </tr>
  </tbody>
</table>

<br><br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<table>
<tr>
  <td width="80"><strong>顧客名</strong></td>
  <td width="80"><strong>支店名</strong></td>
  <td width="80"><strong>支店名</strong></td>
  <td width="80"><strong>支店名</strong></td>
  <td width="80"><strong>支店名</strong></td>
  <td width="80"><strong>支店名</strong></td>
</tr>
<?php
   for($i=1; $i<=1; $i++){
        echo "<tr>\n";
        echo "<td>\n";
        echo "<input type='text'  name=product_code".$j.$i." value=${"product_code".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text'  name=product_code".$j.$i." value=${"product_code".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text'  name=product_code".$j.$i." value=${"product_code".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text'  name=product_code".$j.$i." value=${"product_code".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text'  name=product_code".$j.$i." value=${"product_code".$j.$i} >\n";
        echo "</td>\n";
        echo "<td>\n";
        echo "<input type='text'  name=product_code".$j.$i." value=${"product_code".$j.$i} >\n";
        echo "</td>\n";
        echo "</tr>\n";

        echo $this->Form->hidden('max'.$j ,['value'=>$i]);

      }
 ?>
</table>

<?php endfor;?>

<br><br><br>
