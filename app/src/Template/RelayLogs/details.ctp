<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 use Cake\ORM\TableRegistry;//独立したテーブルを扱う
 $this->Linenames = TableRegistry::get('Linenames');

?>
<br>
<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
    <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kadous/menu' /><font size='4' color=black>稼働関係</font></a>
      <font size='4'>　>>　</font><a href='/RelayLogs/yobidashidate' /><font size='4' color=black>リレーログ呼出</font></a>
    </a></td>
  </tbody>
</table>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'details']]) ?>

<?php
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id, 'machine_num' => $machine_num])->toArray();
      $name_machine = $Linenames[0]["name"];
?>

<table>
  <tbody style="background-color: #FFFFCC">
  <tr class="parents">
  <td>呼出日</td>
  <td width="100">ライン</td>
    </tr>
    <tr>
    <td><?= h("　".$date_sta."　～　".$date_fin_hyouji."　") ?></td>
    <td width="100"><?= h($name_machine) ?></td>
    </tr>
  </tbody>
</table>
    <br><br>
<legend align="center"><strong style="font-size: 12pt; color:blue"><?= __('リレーログ一覧') ?></strong></legend>
<br>
<table>
<thead>
            <tr class="parents">
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('　時間　') ?></td>
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('　リレー名　') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('ステイタス') ?></td>
            </tr>
        </thead>
<tbody>
<?php for($j=0; $j<count($arrRelayLogs); $j++): ?>
  <tr class='children'>
  <td style='font-size: 10pt'><?= h("　".$arrRelayLogs[$j]["datetime"]."　") ?></td>
  <td><?= h("　".$arrRelayLogs[$j]["name"]."　") ?></td>
  <td><?= h("　".$arrRelayLogs[$j]["status"]."　") ?></td>
<?php endfor;?>
</tbody>
    </table>
    <br><br>
    <table align="center">
    <tbody class='sample non-sample'>
      <tr>
      <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
      <td style="border-style: none;"><div><?= $this->Form->submit('ショットデータCSV出力', array('name' => 'shotdata')); ?></div></td>
      </tr>
    </tbody>
  </table>
  <br><br>

<?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
<?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
<?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
<?= $this->Form->control('date_fin_hyouji', array('type'=>'hidden', 'value'=>$date_fin_hyouji, 'label'=>false)) ?>
<?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
