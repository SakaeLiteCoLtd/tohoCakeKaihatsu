<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlaccountmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlaccountmenu = new htmlaccountmenu();
 $htmlmaterialdeletemenus = $htmlaccountmenu->materialdeletemenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlmaterialdeletemenus;
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
      for($i=0; $i<$countFactories; $i++){
        ${"factory_id".$i} = json_encode(${"factory_id".$i});//jsに配列を受け渡すために変換
        ${"arrMaterial_name_list".$i} = json_encode(${"arrMaterial_name_list".$i});//jsに配列を受け渡すために変換
      }
?>

<?php for($i=0; $i<$countFactories; $i++): ?>

<script>

$(document).ready(function() {
      $("#auto1").focusout(function() {
        var inputNumber = $("#auto1").val();

          if (inputNumber == <?php echo ${"factory_id".$i}; ?>) {
     //       $("#auto2").text(inputNumber);

            $(function() {
                // 入力補完候補の単語リスト
                let wordlist = <?php echo ${"arrMaterial_name_list".$i}; ?>
                // 入力補完を実施する要素に単語リストを設定
                $("#material_name_list").autocomplete({
                  source: wordlist
                });
            });
            
          }

    })
});

</script>

<?php endfor;?>

<?= $this->Form->create($product, ['url' => ['action' => 'materialdeletedconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($product) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('削除済み仕入品復元') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('復元するデータを入力してください') ?></strong></td></tr>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td><strong>工場名</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false, 'autofocus'=>true, 'id'=>"auto1"]) ?></td>
        </tr>
      </table>

      <table>
      <tr>
        <td width="300"><strong>仕入品名</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"material_name_list")) ?>
        </td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
