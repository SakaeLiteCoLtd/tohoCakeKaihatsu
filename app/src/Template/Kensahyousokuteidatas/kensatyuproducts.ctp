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
<?= $this->Form->create($product, ['url' => ['action' => 'kensatyuichiran']]) ?>

<div align="center"><strong><font color="blue"><?php echo "本日測定中製品一覧";?></font></strong></div>
<br>
<table class="white">
      <tbody class='sample non-sample'>
        <tr>
        <td style='width:200'>管理No.</td>
          <td style='width:200'>製品名</td>
        </tr>

          <?php for($i=0; $i<count($arrInspectionDataResultParents); $i++): ?>
            <tr>
              <td><?= h($arrInspectionDataResultParents[$i]["product_code"]) ? $this->Html->link($arrInspectionDataResultParents[$i]["product_code"], ['action' => 'kensatyuichiran', 's' => $arrInspectionDataResultParents[$i]["product_code"]]) : '' ?></td>
              <td><?= h($arrInspectionDataResultParents[$i]["name"]);?></td>
            </tr>
          <?php endfor;?>

      </tbody>
    </table>

<br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
    </tr>
  </tbody>
</table>
<br>