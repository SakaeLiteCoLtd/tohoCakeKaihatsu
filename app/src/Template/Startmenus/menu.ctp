<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>

<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; ;width:20%; position: fixed;top: 0px; left:0%'>
    <ul class='side-nav'>

      <div class='sample non-sample-div'>
            <br>
            <font size='5'>　総合メニュー</font>
            <br><br>

            <?php
/*
          <?php for($k=0; $k<count($arrMenus); $k++): ?>
            <font size='4'>　・</font><a><font size='4' color=black><?= $this->Html->link(__($arrMenus[$k].'トップ'), ['controller' => $arrController[$k], 'action' => 'index']) ?></font></a>
            <br><br>
          <?php endfor;?>
          "<br><br>\n".
*/
          ?>
          
          <?php if ($check_admin == 1): ?>

          <font size='4'>　・</font><a><font size='4' color=black><?= $this->Html->link(__("管理者用メニュートップ"), ['controller' => "accounts", 'action' => 'index']) ?></font></a>
            <br><br>

          <?php else :?>
          <?php endif; ?>

            <font size='4'>　・</font><a><font size='4' color=black><?= $this->Html->link(__("業務メニュートップ"), ['controller' => "staffs", 'action' => 'index']) ?></font></a>
            <br><br>
            <font size='4'>　・</font><a><font size='4' color=black><?= $this->Html->link(__("製造メニュートップ"), ['controller' => "Images", 'action' => 'index']) ?></font></a>
            <br><br>
            <font size='4'>　・</font><a><font size='4' color=black><?= $this->Html->link(__("メニュートップ"), ['controller' => "Kensahyoukadous", 'action' => 'index']) ?></font></a>

          <br><br><br><br><br><br><br><br>
          <br><br><br><br><br><br><br><br>
          <br><br><br><br><br><br><br><br>
          <br><br><br><br><br><br><br><br>

    </div>
  </ul>
</nav>
