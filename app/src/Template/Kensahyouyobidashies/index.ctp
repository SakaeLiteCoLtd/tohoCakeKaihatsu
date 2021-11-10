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

<?php
     echo $htmlkensahyouseihinyobidashimenus;
?>

<br>

<table>
        <thead>
            <tr class="parents">
              <th style='width:100; height:60; borderr-width: 1px;'><font color=black><?= __('No.') ?></font></th>
              <th style='width:200; borderr-width: 1px;'><?= __('製品名') ?></th>
              <th style='width:200; borderr-width: 1px;'><?= __('ライン番号') ?></th>
              <th style='width:200; borderr-width: 1px;'><?= __('検査表画像・規格') ?></th>
              <th style='width:200; borderr-width: 1px;'><?= __('原料・温度条件') ?></th>
            </tr>
        </thead>

        <tbody>
        <?php for($j=0; $j<count($arrKensahyous); $j++): ?>
            <tr class='children'>
              <td><?= h($i) ?></td>
                <td><?= h($arrKensahyous[$j]["product_name"]) ?></td>
                <td><?= h($arrKensahyous[$j]["machine_num"]) ?></td>
                <td><?= h($arrKensahyous[$j]["kikaku"]) ?></td>
                <td><?= h($arrKensahyous[$j]["seikeijouken"]) ?></td>
            </tr>

          <?php
          $i = $i + 1;
          ?>

<?php endfor;?>

        </tbody>
    </table>
