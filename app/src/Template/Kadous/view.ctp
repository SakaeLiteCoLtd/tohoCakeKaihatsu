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
      <font size='4'>　>>　</font><a href='/Kadous/yobidashidate' /><font size='4' color=black>日報呼出</font></a>
    </a></td>
  </tbody>
</table>
<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<?php
      $dayyetoy = date('Y');
      $dayyetom = date('n');
      $dayyetod = date('j');
?>

<br><br><br>

<?= $this->Form->create($product, ['url' => ['action' => 'details']]) ?>

<table>
  <tbody style="background-color: #FFFFCC">
  <tr class="parents">
  <td>呼出範囲</td>
    </tr>
    <tr>
    <td><?= h("　".$date_sta."　～　".$date_fin_hyouji."　") ?></td>
    </tr>
  </tbody>
</table>
<br>

<table>
        <thead>
            <tr class="parents">
            <td style='font-size: 9pt; width:50; height:50; border-width: 1px solid black;'><?= __('ライン') ?></td>
            <td style='font-size: 10pt; width:90; border-width: 1px solid black;'><?= __('日付') ?></td>
            <td style='font-size: 10pt; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='font-size: 9pt; width:90; border-width: 1px solid black;'><?= __('生産開始時間') ?></td>
            <td style='font-size: 9pt; width:100; border-width: 1px solid black;'><?= __('検査表開始時間') ?></td>
            <td style='font-size: 9pt; width:100; border-width: 1px solid black;'><?= __('検査表終了時間') ?></td>
            <td style='font-size: 9pt; width:90; border-width: 1px solid black;'><?= __('生産終了時間') ?></td>
            <td style='font-size: 9pt; width:90; border-width: 1px solid black;'><?= __('生産時間') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('開始ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('中間ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('終了ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('ロス時間') ?></td>
            <td style='font-size: 10pt; width:70; border-width: 1px solid black;'><?= __('ロス率') ?><br><?= __('(％)') ?></td>
            <td style='font-size: 10pt; width:70; border-width: 1px solid black;'><?= __('達成率') ?><br><?= __('(％)') ?></td>
            <td style='font-size: 10pt; width:60; border-width: 1px solid black;'></td>
            </tr>
        </thead>

<tbody>
<?php for($j=0; $j<count($arrAll); $j++): ?>

  <?= $this->Form->control('machine_num'.$j, array('type'=>'hidden', 'value'=>$arrAll[$j]["machine_num"], 'label'=>false)) ?>
  <?= $this->Form->control('product_code'.$j, array('type'=>'hidden', 'value'=>$arrAll[$j]["product_code"], 'label'=>false)) ?>
  <?= $this->Form->control('start_datetime'.$j, array('type'=>'hidden', 'value'=>$arrAll[$j]["start_datetime_check"], 'label'=>false)) ?>
  <?= $this->Form->control('num'.$j, array('type'=>'hidden', 'value'=>$j, 'label'=>false)) ?>
  <?= $this->Form->control('num_max', array('type'=>'hidden', 'value'=>$j, 'label'=>false)) ?>

  <tr class='children'>

<?php
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id, 'machine_num' => $arrAll[$j]["machine_num"]])->toArray();
      $name_machine = $Linenames[0]["name"];
?>

    <?php
    echo "<td style='font-size: 10pt'>\n";
    ?>
    <?= h($name_machine) ?></td>

    <?php
    echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["date"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["name"]) ?></td>

  <?php
    echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["relay_start_datetime"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["start_datetime"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["finish_datetime"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["relay_finish_datetime"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["relay_time"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_sta"]) ?></td>

  <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_mid"]) ?></td>

 <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_fin"]) ?></td>

    <td style='font-size: 10pt'></td>

    <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["lossritsu"]) ?></td>

    <?php
  echo "<td style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["tasseiritsu"]) ?></td>

  <?php
  echo "<td>\n";
  ?>
              <?php
              echo $this->Form->submit("詳細" , ['name' => $arrAll[$j]["machine_num"]."_".$arrAll[$j]["product_code"]."_".$arrAll[$j]["start_datetime_check"]]) ;
              ?>
              </td>

            </tr>
<?php endfor;?>

        </tbody>
    </table>
    <?= $this->Form->control('date_select_flag', array('type'=>'hidden', 'value'=>2, 'label'=>false)) ?>
    <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
    <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
    <?= $this->Form->control('date_fin_hyouji', array('type'=>'hidden', 'value'=>$date_fin_hyouji, 'label'=>false)) ?>
    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
    <?= $this->Form->control('product_name', array('type'=>'hidden', 'value'=>$product_name, 'label'=>false)) ?>
    <?= $this->Form->control('machine_num', array('type'=>'hidden', 'value'=>$machine_num, 'label'=>false)) ?>
    <?= $this->Form->control('num_max', array('type'=>'hidden', 'value'=>count($arrAll), 'label'=>false)) ?>
    <br>
    <table align="center">
    <tbody class='sample non-sample'>
      <tr>

      <?php 
/*

*/
        ?>

        <td style="border-style: none;"><div><?= $this->Form->submit('中間ロス有のみ再表示', array('name' => 'tyuukann')); ?></div></td>
        <td style="border-style: none;"><?= __("　") ?></td>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      </tr>
    </tbody>
  </table>
  <br><br>
<br><br>
