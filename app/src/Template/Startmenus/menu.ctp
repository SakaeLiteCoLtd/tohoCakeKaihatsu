<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlloginmenu;//myClassフォルダに配置したクラスを使用
 $htmlloginmenu = new htmlloginmenu();
 $htmllogin = $htmlloginmenu->Loginmenu();
?>
<?php
     echo $htmllogin;
?>

<nav class='large-3 medium-4 columns' id='actions-sidebar' style='width:20%; background-color: #afeeee ;width:20%; position: fixed;top: 0px; left:0%'>
    <ul class='side-nav'>

      <div>
            <br>
            <font size='5'>　総合メニュー</font>
            <br><br>

          <?php for($k=0; $k<count($arrMenus); $k++): ?>
            <font size='4'>　・</font><a><font size='4' color=black><?= $this->Html->link(__($arrMenus[$k].'トップ'), ['controller' => $arrController[$k], 'action' => 'index']) ?></font></a>
            <br><br>
          <?php endfor;?>

          <br><br><br><br><br><br><br><br>
          <br><br><br><br><br><br><br><br>
          <br><br><br><br><br><br><br><br>
          <br><br><br><br><br><br><br><br>

    </div>
  </ul>
</nav>
