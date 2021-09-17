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

<?= $this->Form->create($customer, ['url' => ['action' => 'editconfirm']]) ?>
<br><br><br>

<nav class="sample non-sample">

    <?= $this->Form->create($customer) ?>
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('得意先情報編集・削除') ?></strong></legend>
        <br>
        <table>
          <tbody class='sample non-sample'>
          <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('データを編集してください') ?></strong></td></tr>
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
        <td width="280"><strong>得意先コード（変更不可）</strong></td>
      </tr>
      <tr>
        <td><?= $this->Form->control('name', array('type'=>'text', 'value'=>$name, 'label'=>false, 'required' => 'true')) ?></td>
        <td><?= h($customer_code) ?></td>
    </tr>
    </table>
  <table>
    <tr>
    <td width="280"><strong>フリガナ(半角カタカナで入力)</strong></td>
      <td width="280"><strong>略称</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('furigana', array('type'=>'text', 'pattern' => '[\uFF66-\uFF9F]*', 'title'=>'半角カタカナで入力してください', 'value'=>$furigana, 'label'=>false)) ?></td>
      <td><?= $this->Form->control('ryakusyou', array('type'=>'text', 'value'=>$ryakusyou, 'label'=>false)) ?></td>
    </tr>
  </table>
  <table>
    <tr>
    <td width="200"><strong>部署</strong></td>
    <td width="180"><strong>電話番号</strong></td>
      <td width="180"><strong>ファックス</strong></td>
    </tr>
    <tr>
    <td><?= $this->Form->control('department', array('type'=>'text', 'value'=>$department, 'label'=>false)) ?></td>
      <td><?= $this->Form->control('tel', array('type'=>'text', 'value'=>$tel, 'label'=>false, 'size'=>15)) ?></td>
      <td><?= $this->Form->control('fax', array('type'=>'text', 'value'=>$fax, 'label'=>false, 'size'=>15)) ?></td>
    </tr>
  </table>
  <table>
    <tr>
      <td width="150"><strong>郵便番号</strong></td>
      <td width="410"><strong>住所</strong></td>
    </tr>
    <tr>
      <td><?= $this->Form->control('yuubin', array('type'=>'text', 'value'=>$yuubin, 'label'=>false, 'size'=>10)) ?></td>
      <td><?= $this->Form->control('address', array('type'=>'text', 'value'=>$address, 'label'=>false, 'size'=>35)) ?></td>
    </tr>
  </table>

  <table>
  <tbody>
    <tr>
      <td><?= $this->Form->control('check', array('type'=>'checkbox', 'label'=>false)) ?></td>
      <td><div><strong style="font-size: 13pt; color:blue">データを削除する場合はチェックを入れてください。</strong></div></td>
    </tr>
  </tbody>
</table>

    <table>
      <tbody class='sample non-sample'>
        <tr>
          <td style="border:none"><?= $this->Form->submit(__('次へ')) ?></td>
        </tr>
      </tbody>
    </table>

    <?= $this->Form->control('id', array('type'=>'hidden', 'value'=>$id, 'label'=>false)) ?>
    <?= $this->Form->control('customer_code', array('type'=>'hidden', 'value'=>$customer_code, 'label'=>false)) ?>

</fieldset>

    <?= $this->Form->end() ?>
  </nav>
  <br><br><br>
