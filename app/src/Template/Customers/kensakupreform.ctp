<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcustomermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcustomermenu = new htmlcustomermenu();
 $htmlcustomer = $htmlcustomermenu->Customersmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>

<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<?php
$arrCustomer_name_list = json_encode($arrCustomer_name_list);//jsに配列を受け渡すために変換
?>

<script>

$(function() {
      // 入力補完候補の単語リスト
      let wordlist = <?php echo $arrCustomer_name_list; ?>
      // 入力補完を実施する要素に単語リストを設定
      $("#customer_name_list").autocomplete({
        source: wordlist
      });
  });

</script>

<?php
     echo $htmllogin;
?>
<?php
     echo $htmlcustomer;
     ?>

<?= $this->Form->create($customer, ['url' => ['action' => 'kensakuichiran']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($customer) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('得意先・仕入先情報検索') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt"><?= __('検索するデータを入力してください（得意先・仕入先名の一部で検索できます）') ?></strong></td></tr>
          </tbody>
        </table>
        <br>

      <table>
      <tr>
        <td width="320"><strong>得意先・仕入先名（一部のみも可）</strong></td>
      </tr>
      <tr>
        <td>
        <?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'size'=>30, 'id'=>"customer_name_list")) ?>
        </td>
      </tr>
    </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
        <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
          <td style="border-style: none;"><?= __("　") ?></td>
         <td style="border:none"><?= $this->Form->submit(__('検索')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
