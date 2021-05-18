<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
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

<br>
<hr size="5" style="margin: 0rem">
<br>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/seikeiondomenu.gif',array('width'=>'145','height'=>'50'));?></td>
  </tr>
</table>
<br>

<?= $this->Form->create($product, ['url' => ['action' => 'addcomfirm']]) ?>

<?= $this->Form->control('staff_id', array('type'=>'hidden', 'value'=>$staff_id, 'label'=>false)) ?>
<?= $this->Form->control('staff_name', array('type'=>'hidden', 'value'=>$staff_name, 'label'=>false)) ?>
<?= $this->Form->control('product_code', array('type'=>'hidden', 'value'=>$product_code, 'label'=>false)) ?>
<?= $this->Form->control('countseikeiki', array('type'=>'hidden', 'value'=>$countseikeiki, 'label'=>false)) ?>

<table width="1000">
    <tr>
      <td width="500" colspan="2" nowrap="nowrap" style="height: 50px"><strong>検査成績書</strong><br>（兼　成形条件表・梱包仕様書・作業手順書）</td>
      <td width="100" nowrap="nowrap" style="height: 30px">製品名</td>
      <td width="400" nowrap="nowrap" style="height: 30px"><?= h($name) ?></td>
    </tr>
    <tr>
      <td width="200" nowrap="nowrap" style="height: 30px">管理No</td>
      <td width="300" style="height: 30px"><?= h($product_code) ?></td>
      <td width="200" rowspan='2' style="height: 30px">顧客名</td>
      <td width="300" rowspan='2' style="height: 30px"><?= h($customer) ?></td>
    </tr>
    <tr>
      <td width="200" nowrap="nowrap" style="height: 30px">改訂日</td>
      <td width="300" style="height: 30px"><?= h("（仮）".$today); ?></td>
    </tr>
    <tr>
      <td width="1000" colspan="4" nowrap="nowrap" style="height: 400px">画像</td>
    </tr>
</table>

<br>

<?php for($j=1; $j<=$countseikeiki; $j++): ?>
<br>

<?= $this->Form->control('product_material_machine_id'.$j, array('type'=>'hidden', 'value'=>${"product_material_machine_id".$j}, 'label'=>false)) ?>

<table>
<tr>
  <td style='width:80'>成形機</td>
  <td width="100">温度条件</td>
  <td style='width:70'>C １</td>
  <td style='width:70'>C ２</td>
  <td style='width:70'>C ３</td>
  <td style='width:70'>C ４</td>
  <td style='width:70'>A D</td>
  <td style='width:70'>D １</td>
  <td style='width:70'>D ２</td>
  <td style='width:100'>押出回転<br>（rpm）</td>
  <td style='width:100'>負荷（A）</td>
  <td style='width:100'>引取速度<br>（m/min）</td>
  <td style='width:100'>ｽｸﾘｰﾝﾒｯｼｭ</td>
  <td style='width:100'>ｽｸﾘｭｳ</td>
</tr>

<?php
   for($i=1; $i<=3; $i++){

        echo "<tr>\n";

        if($i==1){
          echo "<td rowspan=3>\n";
          echo "<input type='text' style='width:60px' required name=cylinder_name".$j." value=${"cylinder_name".$j}>\n";
          echo "</td>\n";
        }

        if($i==1){
          echo "<td style='width:50px'>\n";
          echo "基 準 値\n";
          echo "</td>\n";
        }elseif($i==2){
          echo "<td style='width:50px'>\n";
          echo "記    録\n";
          echo "</td>\n";
        }elseif($i==3){
          echo "<td style='width:50px'>\n";
          echo "許容範囲\n";
          echo "</td>\n";
        }

        if($i == 1){
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_1".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_2".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_3".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_4".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_5".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_6".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:50px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=temp_7".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:70px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=extrude_roatation".$j.">\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "<input type='text' style='width:70px' pattern='^[0-9.]+$' title='半角数字で入力して下さい。' required name=extrusion_load".$j.">\n";
          echo "</td>\n";
        }elseif($i == 2){
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "</td>\n";
        }else{
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 10\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 5.0\n";
          echo "</td>\n";
          echo "<td>\n";
          echo "± 5.0\n";
          echo "</td>\n";
        }

        if($j==1){
          if($i < 3){
            if($i==1){
              echo "<td>\n";
              echo "<input type='text' style='width:70px' required name=pickup_speed>\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "<input type='text' style='width:70px' required name=screw_mesh>\n";
              echo "</td>\n";
              echo "<td>\n";
              echo "<input type='text' style='width:70px' required name=screw_number>\n";
              echo "</td>\n";
            }else{
              echo "<td>\n";
              echo "</td>\n";
              echo "<td>\n";
              echo " - \n";
              echo "</td>\n";
              echo "<td>\n";
              echo " - \n";
              echo "</td>\n";
            }
          }else{
            echo "<td>\n";
            echo "± 1.0\n";
            echo "</td>\n";
            echo "<td>\n";
            echo " - \n";
            echo "</td>\n";
            echo "<td>\n";
            echo " - \n";
            echo "</td>\n";
          }
        }

        echo "</tr>\n";

      }
 ?>
</table>

<?php endfor;?>

<br><br>
<table align="center">
  <tbody class='sample non-sample'>
    <tr>
      <td style="border:none"><?= $this->Form->submit(('登録確認へ'), array('name' => 'kakuninn')) ?></td>
    </tr>
  </tbody>
</table>
<br>
