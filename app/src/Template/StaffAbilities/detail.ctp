<?php
 use App\myClass\menulists\htmlstaffAbilitymenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlstaffAbilitymenu = new htmlstaffAbilitymenu();
 $htmlstaffAbility = $htmlstaffAbilitymenu->StaffAbilitymenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();

 $i = 1;
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlstaffAbility;
?>

<?= $this->Form->create($staffAbility, ['url' => ['action' => 'detail']]) ?>

<br><br><br>

<nav class="sample non-sample">
    <fieldset>
      <table>
      <tbody class='sample non-sample'>
        <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __('詳細表示') ?></strong></td></tr>
      </tbody>
    </table>

    <table>
      <tr>
        <td width="280"><strong>スタッフ</strong></td>
      </tr>
      <tr>
        <td><?= h($staff_name) ?></td>
      </tr>
    </table>


    <table>
      <tr>
        <td width="280"><strong>取り扱い可能メニュー</strong></td>
      </tr>

    <?php for($j=0; $j<count($arrStaffAbilities); $j++): ?>

      <tr>
        <td><?= h($arrStaffAbilities[$j]) ?></td>
      </tr>

    <?php endfor;?>

  </table>

    </fieldset>

    <br><br><br>

    <?= $this->Form->end() ?>
  </nav>
