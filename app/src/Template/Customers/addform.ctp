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

<?= $this->Form->create($customer, ['url' => ['action' => 'addcomfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($customer) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('得意先・仕入先新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>
        <?php if ($usercheck == 1): ?>
          <table>
          <tr>
            <td width="280"><strong>工場名</strong></td>
        	</tr>
          <tr>
            <td><?= $this->Form->control('factory_id', ['options' => $Factories, 'label'=>false]) ?></td>
        	</tr>
        </table>
 <?php else : ?>
<?php endif; ?>

      <table>
      <tr>
        <td width="280"><strong>得意先・仕入先名</strong></td>
        <td width="280"><strong>得意先・仕入先コード6桁目</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
        <td><?= $this->Form->control('customer_code_last', ['options' => $arrcustomer_code_last, 'label'=>false]) ?></td>
    </tr>
    </table>
    <table>
    <tr>
      <td width="280"><strong>フリガナ(半角カタカナで入力)</strong></td>
      <td width="280"><strong>※略称</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('furigana', array('type'=>'text', 'pattern' => '[\uFF66-\uFF9F]*', 'title'=>'半角カタカナで入力してください', 'label'=>false, 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('ryakusyou', array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
    </tr>
  </table>
  <table>
    <tr>
    <td width="200"><strong>※部署</strong></td>
      <td width="180"><strong>※電話番号</strong></td>
      <td width="180"><strong>※ファックス</strong></td>
    </tr>
    <tr>
    <td><?= $this->Form->control('department', array('type'=>'text', 'label'=>false, 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('tel', array('type'=>'tel', 'label'=>false, 'size'=>15, 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('fax', array('type'=>'tel', 'label'=>false, 'size'=>15, 'autocomplete'=>"off")) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="150"><strong>※郵便番号</strong></td>
      <td width="410"><strong>※住所</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('yuubin', array('type'=>'tel', 'label'=>false, 'size'=>15, 'autocomplete'=>"off")) ?></td>
      <td><?= $this->Form->control('address', array('type'=>'text', 'label'=>false, 'size'=>35, 'autocomplete'=>"off")) ?></td>
    </tr>
  </table>

  <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 12pt">
            <?= __('「※」のついている欄は空白のまま登録できます。') ?>
          </strong></td></tr>
          </tbody>
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
    <br><br>

    <?= $this->Form->end() ?>
  </nav>
