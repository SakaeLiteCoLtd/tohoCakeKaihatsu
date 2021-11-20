<?php
//http://localhost:5050/Zzzcsvs/jidoutest
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrProduct_name_list = json_encode($arrProduct_name_list);//jsに配列を受け渡すために変換
?>

<script>

$(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrProduct_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#product_name_list").autocomplete({
        source: wordlist
      });
  });

</script>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<br><br><br>

<table>
      <tr>
        <td width="320"><strong>製品名（一部のみも可）</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"product_name_list")) ?>
        </td>
      </tr>
    </table>

    <table>
      <tr>
        <td width="320"><strong>製品名1</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名2</strong></td>
      </tr>
      <tr>
        <td id="screenshot">
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名3</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table class="scroll">
      <tr id="scroll-inner">
        <td width="320"><strong>製品名a</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>
    <table>
      <tr>
        <td width="320"><strong>製品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30)) ?>
        </td>
      </tr>
    </table>


    <?php
/*
<?php
//echo $arrLength_size1['size'];

$j = 1;
$num_length = json_encode($num_length);//jsに配列を受け渡すために変換
$count_length = json_encode($count_length);//jsに配列を受け渡すために変換

for($i=0; $i<$count_length; $i++){
  ${"Length_length".$i} = json_encode(${"arrLength_size".$i}['length']);//jsに配列を受け渡すために変換
  ${"Length_size".$i} = json_encode(${"arrLength_size".$i}['size']);//jsに配列を受け渡すために変換
  ${"Length_upper".$i} = json_encode(${"arrLength_size".$i}['upper']);//jsに配列を受け渡すために変換
  ${"Length_lower".$i} = json_encode(${"arrLength_size".$i}['lower']);//jsに配列を受け渡すために変換
}

$i = 0;

?>

<?php for($i=0; $i<=10; $i++): ?>

<script>

      $(document).ready(function() {
          $("#auto1").focusout(function() {
            var inputNumber = $("#auto1").val();
            var resultDivision1 = inputNumber; 
            var resultDivision2 = <?php echo $num_length; ?>;

        //    $("#auto2").text(resultDivision1);//文字として表示する場合はtext、入力フォームに置く場合はval
        //    $("#auto3").text(name);

      //これはOK
  //      if (11 == 10) {
  //        $("#auto2").text(resultDivision1);
  //      }else{
  //        $("#auto3").text(resultDivision1);
  //      }
  //    
  //    //これはOK//長さが同じなら表示
  //    <?php
  //    //$i = 0;
  //    ?>
  //    if (inputNumber == <?php// echo ${"Length_length".$i}; ?>) {
  //      $("#auto2").text(resultDivision1);
  //    }else{
  //      $("#auto3").text(resultDivision1);
  //    }
  //    

        if (inputNumber == <?php echo ${"Length_length".$i}; ?>) {

          var size_length = <?php echo ${"Length_size".$i}; ?>;
          var upper_length = <?php echo ${"Length_upper".$i}; ?>;
          var lower_length = <?php echo ${"Length_lower".$i}; ?>;

          $("#size<?php echo $num_length; ?>").text(size_length);
          $("#upper<?php echo $num_length; ?>").text(upper_length);
          $("#lower<?php echo $num_length; ?>").text(lower_length);

          $("#auto2").text(<?php echo ${"Length_length".$i}; ?>);
          $("#auto3").text(resultDivision1);

        }else{

          $("#auto2").text(<?php echo ${"Length_length".$i}; ?>);
          $("#auto3").text(resultDivision1);

        }

    })

});

</script>

<?php endfor;?>

<br><br><br>

<br>
<table class="white">
<tr><td width="280"><strong>自動補完テスト１</strong></td></tr>
<td><?= $this->Form->input('name_menu1', array('type'=>'text', 'label'=>false, 'id'=>"aaauto1")) ?></td>
<tr><td width="280"><strong>自動補完テスト２</strong></td></tr>
<td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false, 'id'=>"auto1"]) ?></td>
<tr><td width="280"><strong>同じ</strong></td></tr>
<td><div id="auto2"></div></td>
<tr><td width="280"><strong>違う</strong></td></tr>
<td><div id="auto3"></div></td>
<tr><td width="280"><strong>テスト</strong></td></tr>
<td><?= h($i) ?></td>

</table>
<br>

<table class="white"><tr>

<?php for($i=1; $i<=10; $i++): ?>

  <td width="80"><div class="size"></div><?= h(${"result_size".$i}) ?></td>

<?php endfor;?>

</table>

<table class="white"><tr>

<?php for($i=1; $i<=10; $i++): ?>

<td width="80"><div class="upper"></div><?= h(${"result_size".$i}) ?></td>

<?php endfor;?>

</table>

<table class="white"><tr>

<?php for($i=1; $i<=10; $i++): ?>

<td width="80"><div class="lower"></div><?= h(${"result_size".$i}) ?></td>

<?php endfor;?>

</table>

<script>
//こちらがhtmlよりもあとに来ないと反映されない
var moji = "size"
    var tmp = document.getElementsByClassName("size") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

    var moji = "upper"
    var tmp = document.getElementsByClassName("upper") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

    var moji = "lower"
    var tmp = document.getElementsByClassName("lower") ;

    for(var i=1;i<10;i++){
        //id追加
        tmp[i].setAttribute("id",moji+i);
    }

</script>
*/
?>