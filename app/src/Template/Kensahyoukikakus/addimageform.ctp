<?php
header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策
 ?>

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
    <td style='border: none;'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyouyobidashies/index' /><font size='4' color=black>検査規格</font></a>
 </td>
  </tbody>
</table>

<br><br><br>

<table>
  <tbody class='sample non-sample'>
  <tr><td style="border:none"><strong style="font-size: 15pt; color:red"><?= __('検査表画像　新規登録') ?></strong></td></tr>
  </tbody>
</table>

    <br>
    <div align="center"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></div>
    <div align="center"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></div>
    <br>
    <br>
      <div align="center"><font size="4"><?= __($product_name." の検査表に使用する画像ファイルを選択してください。") ?></font></div>
      <br>

  <?= $this->Form->create($inspectionStandardSizeParents,['action'=>'addimagecomfirm', 'type'=>'file']) ?>
        <table width="300">
        <tbody style="background-color: #FFFFCC">
            <tr>
              <td><?= $this->Form->file('upfile') ?></td>
            </tr>
          </tbody>
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

      <br>
      <div align="center"><strong style="font-size: 13pt; color:red"><?= __("※画像ファイルの拡張子は「.JPG」にしてください。　　　") ?></strong></div>
      <div align="center"><strong style="font-size: 13pt; color:red"><?= __("※長さのみ異なる製品には同じ検査表画像が使用されます。") ?></strong></div>
      <br>

      <?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
      <?= $this->Form->end() ?>
</nav>
