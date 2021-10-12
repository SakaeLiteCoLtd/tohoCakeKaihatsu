<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
$htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
$htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
$htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();

use App\myClass\classprograms\htmlLogin;//myClassフォルダに配置したクラスを使用
$htmlinputstaffctp = new htmlLogin();
$inputstaffctp = $htmlinputstaffctp->inputstaffctp();
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
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyouseihinmenu' /><font size='4' color=black>製品関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/seihinyobidashimenu' /><font size='4' color=black>製品呼出</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'seihinkensakusyousai']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
        <br>

        <table>
        <tr class='children'>
          <td width="180"><strong>工場名</strong></td>
          <td width="380"><strong>得意先</strong></td>
        </tr>
        <tr class='children'>
        <td><?= h($factory_name) ?></td>
        <td><?= h($customer_name) ?></td>
        </tr>
      </table>
      <br>
      <table>
          <tr class='children'>
            <td width="380"><strong>品名</strong></td>
            <td width="90"><strong>単位</strong></td>
            <td width="120"><strong>単重(g/m)</strong></td>
            <td width="200"><strong>幅測定器モード番号</strong></td>
        	</tr>
          <tr class='children'>
          <td><?= h($name) ?></td>
          <td><?= h($tanni) ?></td>
          <td><?= h($weight) ?></td>
          <td><?= h($ig_bank_modes) ?></td>
       	</tr>
        </table>

     <br>

<table>
 <tr class='children'>
 <td><strong>製品コード</strong></td>
 <td width="200"><strong>品名</strong></td>
 <td width="120"><strong>検査表に表示</strong></td>
<td><strong>長さ（mm）</strong></td>
 <td><strong>カット長さ（mm）</strong></td>
   <td width="70"><strong>上限</strong></td>
   <td width="70"><strong>下限</strong></td>
   <td width="120"><strong>備考</strong></td>
 </tr>
 
 <?php for($i=0; $i<count($ProductName); $i++): ?>

 <tr class='children'>
 <td><?= h($ProductName[$i]["product_code"]) ?></td>
 <td><?= h($ProductName[$i]["name"]) ?></td>
 <?php
      if($ProductName[$i]["status_kensahyou"] == 0){
        $status_kensahyou_name = "表示";
      }else{
        $status_kensahyou_name = "非表示";
      }
?>

      <td><?= h($status_kensahyou_name) ?></td>
 <td><?= h($ProductName[$i]["length"]) ?></td>
 <td><?= h($ProductName[$i]["length_cut"]) ?></td>
 <td><?= h($ProductName[$i]["length_upper_limit"]) ?></td>
 <td><?= h($ProductName[$i]["length_lower_limit"]) ?></td>
 <td><?= h($ProductName[$i]["bik"]) ?></td>

 <?php endfor;?>

</table>
<br><br>
<table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        </tr>
      </tbody>
    </table>

<br>
    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
    <?= $this->Form->control('name', array('type'=>'hidden', 'value'=>$name, 'label'=>false)) ?>

    <?= $this->Form->end() ?>
  </nav>
