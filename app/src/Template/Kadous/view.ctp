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
            <td style='font-size: 10pt; width:50; height:60; border-width: 1px solid black;'><?= __('ライン') ?></td>
            <td style='font-size: 10pt; width:110; border-width: 1px solid black;'><?= __('稼働開始時間') ?></td>
            <td style='font-size: 10pt; width:110; border-width: 1px solid black;'><?= __('検査表開始時間') ?></td>
            <td style='font-size: 10pt; width:110; border-width: 1px solid black;'><?= __('検査表終了時間') ?></td>
            <td style='font-size: 10pt; width:110; border-width: 1px solid black;'><?= __('稼働終了時間') ?></td>
            <td style='font-size: 10pt; width:110; border-width: 1px solid black;'><?= __('稼働時間') ?></td>
            <td style='font-size: 10pt; width:200; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('開始ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('中間ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('終了ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('合計ロス') ?><br><?= __('(kg)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('長さ') ?><br><?= __('(mm)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('当日数量') ?><br><?= __('(本)') ?></td>
            <td style='font-size: 10pt; width:60; border-width: 1px solid black;'></td>
            </tr>
        </thead>

<tbody>
<?php for($j=0; $j<count($arrAll); $j++): ?>

  <?= $this->Form->control('machine_num'.$j, array('type'=>'hidden', 'value'=>$arrAll[$j]["machine_num"], 'label'=>false)) ?>
  <?= $this->Form->control('product_code'.$j, array('type'=>'hidden', 'value'=>$arrAll[$j]["product_code"], 'label'=>false)) ?>
  <?= $this->Form->control('start_datetime'.$j, array('type'=>'hidden', 'value'=>$arrAll[$j]["start_datetime"], 'label'=>false)) ?>
  <?= $this->Form->control('num'.$j, array('type'=>'hidden', 'value'=>$j, 'label'=>false)) ?>
  <?= $this->Form->control('num_max', array('type'=>'hidden', 'value'=>$j, 'label'=>false)) ?>

  <tr class='children'>

  <?php if ($arrAll[$j]["countproduct_code_ini"] == 1): ?>

  <?php
  if($arrAll[$j]["product_code_ini"] == ""){
    $countproduct_rowspan = 1;
  }else{
    $product_code_ini_machine_num_datetime = $arrAll[$j]["product_code_ini"]."_".$arrAll[$j]["machine_num"]."_".$arrAll[$j]["start_datetime"];
    $countproduct_rowspan = $arrCountProducts[$product_code_ini_machine_num_datetime];
    }
  ?>

<?php
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id, 'machine_num' => $arrAll[$j]["machine_num"]])->toArray();
      $name_machine = $Linenames[0]["name"];
?>

    <?php
    echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
    ?>
    <?= h($name_machine) ?></td>

    <?php
    echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
  <?= h(substr($arrAll[$j]["relay_start_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["relay_start_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
  <?= h(substr($arrAll[$j]["start_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["start_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
  <?= h(substr($arrAll[$j]["finish_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["finish_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
  <?= h(substr($arrAll[$j]["relay_finish_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["relay_finish_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["relay_time"]) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
  <?= h($arrAll[$j]["name"]) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_sta"]) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_mid"]) ?></td>

 <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_fin"]) ?></td>

 <?php
  echo "<td rowspan=$countproduct_rowspan style='font-size: 10pt'>\n";
  ?>
    <?= h($arrAll[$j]["loss_total"]) ?></td>

  <?php else : ?>
  <?php endif; ?>

  <td style='font-size: 10pt'><?= h($arrAll[$j]["length"]) ?></td>
  <td style='font-size: 10pt'><?= h($arrAll[$j]["amount"]) ?></td>

  <?php if ($arrAll[$j]["countproduct_code_ini"] == 1): ?>
  <?php
  if($arrAll[$j]["product_code_ini"] == ""){
    $countproduct_rowspan = 1;
  }else{
    $arrproduct_code_ini_machine_num_datetime = $arrAll[$j]["product_code_ini"]
    ."_".$arrAll[$j]["machine_num"]."_".$arrAll[$j]["start_datetime"];
    $countproduct_rowspan = $arrCountProducts[$arrproduct_code_ini_machine_num_datetime];
    }
  echo "<td rowspan=$countproduct_rowspan>\n";
  ?>
              <?php if ($arrAll[$j]["product_code"] !== ""): ?>
              <?php
              echo $this->Form->submit("詳細" , ['name' => $arrAll[$j]["machine_num"]."_".$arrAll[$j]["product_code"]."_".$arrAll[$j]["start_datetime"]]) ;
              ?>
              <?php else : ?>
                <?= h("-") ?>
              <?php endif; ?>
              </td>

  <?php else : ?>
  <?php endif; ?>

            </tr>
<?php endfor;?>

        </tbody>
    </table>
    <?= $this->Form->control('date_select_flag', array('type'=>'hidden', 'value'=>2, 'label'=>false)) ?>
    <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
    <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
    <?= $this->Form->control('date_fin_hyouji', array('type'=>'hidden', 'value'=>$date_fin_hyouji, 'label'=>false)) ?>
    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
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
