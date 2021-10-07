<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlcustomermenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlcustomermenu = new htmlcustomermenu();
 $htmlcustomer = $htmlcustomermenu->Customersmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>

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
        <legend><strong style="font-size: 15pt; color:red"><?= __('登録済み情報一覧') ?></strong></legend>
        <br>

    <table>
          <tr>
          <td width="250"><strong>得意先・仕入先名</strong></td>
          <td width="250"><strong>部署・営業所名</strong></td>
          <td width="150"><strong>得意先・仕入先コード</strong></td>
          <td width="150"><strong>使用工場</strong></td>
        	</tr>

        <?php for($i=0; $i<count($Customers); $i++): ?>

          <tr>
          <td><?= h($Customers[$i]["name"]) ?></td>
          <td><?= h($Customers[$i]["department"]) ?></td>
          <td><?= h($Customers[$i]["customer_code"]) ?></td>
          <td><?= h($Customers[$i]["factory"]["name"]) ?></td>
          </tr>

      <?php endfor;?>

    </table>
    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border-style: none;"><div><?= $this->Form->submit('戻る', ['onclick' => 'history.back()', 'type' => 'button']); ?></div></td>
        </tr>
      </tbody>
    </table>

    </fieldset>

    <?= $this->Form->end() ?>
  </nav>
