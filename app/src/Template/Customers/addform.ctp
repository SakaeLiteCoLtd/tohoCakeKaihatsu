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

<nav class="large-3 medium-4 columns">

    <?= $this->Form->create($customer) ?>
    <fieldset>
        <legend><strong style="font-size: 15pt; color:red"><?= __('得意先新規登録') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('登録するデータを入力してください') ?></strong></td></tr>
            <tr class='sample non-sample'><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mess) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
        <tr>
          <td width="280"><strong>自社工場</strong></td>
        </tr>
        <tr>
          <td><?= $this->Form->control('factory_id', ['options' => $arrFactories, 'label'=>false]) ?></td>
        </tr>
      </table>
      <table>
      <tr>
        <td width="280"><strong>得意先名</strong></td>
        <td width="280"><strong>得意先コード</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('name', array('type'=>'text', 'label'=>false)) ?></td>
        <td><?= $this->Form->control('customer_code', array('type'=>'text', 'label'=>false)) ?></td>
    </tr>
    </table>
  <table>
    <tr>
      <td width="280"><strong>フリガナ</strong></td>
      <td width="280"><strong>部署</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('furigana', array('type'=>'text', 'label'=>false)) ?></td>
      <td><?= $this->Form->control('department', array('type'=>'text', 'label'=>false)) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="280"><strong>電話番号</strong></td>
      <td width="280"><strong>ファックス</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('tel', array('type'=>'text', 'label'=>false)) ?></td>
      <td><?= $this->Form->control('fax', array('type'=>'text', 'label'=>false)) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="150"><strong>郵便番号</strong></td>
      <td width="410"><strong>住所</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('yuubin', array('type'=>'text', 'label'=>false, 'size'=>10)) ?></td>
      <td><?= $this->Form->control('address', array('type'=>'text', 'label'=>false, 'size'=>35)) ?></td>
    </tr>
  </table>

    </fieldset>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->end() ?>
  </nav>
