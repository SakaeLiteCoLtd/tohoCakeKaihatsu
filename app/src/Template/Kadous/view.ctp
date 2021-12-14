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
      <font size='4'>　>>　</font><a href='/Kadous/yobidashidate' /><font size='4' color=black>稼働関係</font></a>
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
  <td>呼出日</td>
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
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('検査表開始時間') ?></td>
            <td style='font-size: 9pt; width:125; border-width: 1px solid black;'><?= __('リレーログ開始時間') ?></td>
            <td style='font-size: 10pt; width:100; border-width: 1px solid black;'><?= __('検査表終了時間') ?></td>
            <td style='font-size: 9pt; width:125; border-width: 1px solid black;'><?= __('リレーログ終了時間') ?></td>
            <td style='font-size: 10pt; width:200; border-width: 1px solid black;'><?= __('製品名') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('長さ(mm)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('数量(本)') ?></td>
            <td style='font-size: 10pt; width:80; border-width: 1px solid black;'><?= __('予定数量(本)') ?></td>
            <td style='font-size: 9pt; width:80; border-width: 1px solid black;'><?= __('検査表') ?><br><?= __('使用重量(kg)') ?></td>
            <td style='font-size: 9pt; width:80; border-width: 1px solid black;'><?= __('理論') ?><br><?= __('使用重量(kg)') ?></td>
            <td style='font-size: 10pt; width:60; border-width: 1px solid black;'></td>
            </tr>
        </thead>

<tbody>
<?php for($j=0; $j<count($arrAll); $j++): ?>
  <tr class='children'>

  <?php
        $Linenames = $this->Linenames->find()
        ->where(['delete_flag' => 0, 'factory_id' => $factory_id, 'machine_num' => $arrAll[$j]["machine_num"]])->toArray();
      $name_machine = $Linenames[0]["name"];
?>

<?php if ($arrAll[$j]["count"] == 1): ?>
  <?php
  $machine_num = $arrAll[$j]["machine_num"];
  $count_rowspan = $arrCountMachine[$machine_num];
  echo "<td rowspan=$count_rowspan>\n";
  ?>
  <?= h($name_machine) ?></td>
  <?php else : ?>
  <?php endif; ?>

  <?php if ($arrAll[$j]["countproduct_code_ini"] == 1): ?>
  <?php
  if($arrAll[$j]["product_code_ini"] == "-"){
    $countproduct_rowspan = 1;
  }else{
    $product_code_ini_machine_num = $arrAll[$j]["product_code_ini"]."_".$arrAll[$j]["machine_num"];
    $countproduct_rowspan = $arrCountProducts[$product_code_ini_machine_num];
    }
  echo "<td rowspan=$countproduct_rowspan>\n";
  ?>
  <?= h(substr($arrAll[$j]["start_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["start_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan>\n";
  ?>
  <?= h(substr($arrAll[$j]["relay_start_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["relay_start_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan>\n";
  ?>
  <?= h(substr($arrAll[$j]["finish_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["finish_datetime"], 11, 8)) ?></td>

  <?php
  echo "<td rowspan=$countproduct_rowspan>\n";
  ?>
  <?= h(substr($arrAll[$j]["relay_finish_datetime"], 0, 10)) ?><br><?= h(substr($arrAll[$j]["relay_finish_datetime"], 11, 8)) ?></td>

  <?php else : ?>
  <?php endif; ?>

  <td style='font-size: 10pt'><?= h($arrAll[$j]["name"]) ?></td>
  <td style='font-size: 10pt'><?= h($arrAll[$j]["length"]) ?></td>
  <td style='font-size: 10pt'><?= h($arrAll[$j]["amount"]) ?></td>
  <td style='font-size: 10pt'><?= h($arrAll[$j]["riron_amount"]) ?></td>
  <td style='font-size: 10pt'><?= h($arrAll[$j]["sum_weight"]) ?></td>
  <td style='font-size: 11pt'></td>

  <?php if ($arrAll[$j]["countproduct_code_ini"] == 1): ?>
  <?php
  if($arrAll[$j]["product_code_ini"] == "-"){
    $countproduct_rowspan = 1;
  }else{
    $product_code_ini_machine_num = $arrAll[$j]["product_code_ini"]."_".$arrAll[$j]["machine_num"];
    $countproduct_rowspan = $arrCountProducts[$product_code_ini_machine_num];
    }
  echo "<td rowspan=$countproduct_rowspan>\n";
  ?>
              <?php if ($arrAll[$j]["product_code"] !== "-"): ?>
              <?php
              echo $this->Form->submit("詳細" , ['name' => $arrAll[$j]["machine_num"]."_".$arrAll[$j]["product_code"]]) ;
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
    <?= $this->Form->control('date_sta', array('type'=>'hidden', 'value'=>$date_sta, 'label'=>false)) ?>
    <?= $this->Form->control('date_fin', array('type'=>'hidden', 'value'=>$date_fin, 'label'=>false)) ?>
    <?= $this->Form->control('factory_id', array('type'=>'hidden', 'value'=>$factory_id, 'label'=>false)) ?>
    <br>
    <table align="center">
    <tbody class='sample non-sample'>
      <tr>
        <td style="border: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
      </tr>
    </tbody>
  </table>
  <br><br>
<br><br>
