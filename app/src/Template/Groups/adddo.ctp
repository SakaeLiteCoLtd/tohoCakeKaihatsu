<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlgroupmenu;//myClassフォルダに配置したクラスを使用
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlgroupmenu = new htmlgroupmenu();
 $htmlgroup = $htmlgroupmenu->Groupmenus();
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>
<?php
     echo $htmlgroup;
?>

<?= $this->Form->create($Groups, ['url' => ['action' => 'index']]) ?>
<br><br><br>

<nav class="large-3 medium-4 columns">
    <fieldset>
      <legend><strong style="font-size: 15pt; color:red"><?= __('グループ新規登録') ?></strong></legend>
      <br>
        <table>
          <tbody class='sample non-sample'>
            <tr><td style="border:none"><strong style="font-size: 13pt; color:red"><?= __($mes) ?></strong></td></tr>
          </tbody>
        </table>
        <br>

        <table>
          <tr>
            <td width="280"><strong>グループ名</strong></td>
        	</tr>
          <tr>
            <td><?= h($this->request->getData('name_group')) ?></td>
        	</tr>
        </table>

        <table>
          <tr>
            <td width="280"><strong>取り扱い可能メニュー</strong></td>
        	</tr>

        <?php for($i=0; $i<$this->request->getData('num_menu'); $i++): ?>

          <tr>
            <td><?= h($this->request->getData('menu_name'.$i)) ?></td>
          </tr>

      <?php endfor;?>

    </table>

    </fieldset>

    <table>
      <tr>
        <tbody class='sample non-sample'>
        <td style="border-style: none;"><div><?= $this->Form->submit('グループメニュートップへ戻る', array('name' => 'top')); ?></div></td>
      </tbody>
    </tr>
    </table>

    <?= $this->Form->end() ?>
  </nav>
