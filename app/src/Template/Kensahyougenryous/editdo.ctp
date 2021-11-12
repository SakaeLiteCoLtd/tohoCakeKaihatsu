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
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査規格</font></a>
    </a></td>
  </tbody>
</table>

<?= $this->Form->create($product, ['url' => ['controller'=>'Kensahyoukadous', 'action' => 'kensahyoumenu']]) ?>
<br> <br> <br>

<?php
      echo $htmlkensahyouheader;
 ?>

 <table class="top_big">
  <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
</table>

<table align="left">
  <tbody class='sample non-sample'>
    <tr>
    <td style="border:none">　　　　　　　　　</td>
    <td style="border:none"><font size="4"><strong><?= __($machine_num." 号機") ?></strong></font></td>
    </tr>
  </tbody>
</table>
<br>

<?php for($j=1; $j<=$tuikaseikeiki; $j++): ?>
<br>

<table>
  <tr class="parents">
    <td width="150">成形機</td>
    <td width="490">原料名</td>
    <td width="190">配合比</td>
    <td width="190">乾燥温度</td>
    <td width="190">乾燥時間</td>
    <td width="200">再生配合比</td>
</tr>

<?php
   for($i=1; $i<=${"tuikagenryou".$j}; $i++){

     echo "<tr class='children'>\n";

        if($i==1){
          echo "<td rowspan=${"tuikagenryou".$j}>\n";
          echo ${"cylinder_name".$j};
          echo "</td>\n";
        }

        echo "<td>\n";
        echo ${"material_name".$j.$i};
        echo "</td>\n";
        echo "<td>\n";
        echo ${"mixing_ratio".$j.$i};
        echo "</td>\n";
        echo "<td>\n";
        echo ${"dry_temp".$j.$i}." ℃";
        echo "</td>\n";
        echo "<td>\n";
        echo ${"dry_hour".$j.$i}." h以上";
        echo "</td>\n";
        echo "<td>\n";
        echo ${"recycled_mixing_ratio".$j.$i};
        echo "</td>\n";
        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border-style: none;"><div><?= $this->Form->submit('TOP', array('name' => 'kettei')); ?></div></td>
    </tr>
  </tbody>
</table>
<br>
