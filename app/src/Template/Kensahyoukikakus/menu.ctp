<?php header("X-FRAME-OPTIONS: DENY");//クリックジャッキング対策?>
<?php
 use App\myClass\menulists\htmlkensahyoukadoumenu;//myClassフォルダに配置したクラスを使用
 $htmlkensahyoukadoumenu = new htmlkensahyoukadoumenu();
 $htmlkensahyoukadou = $htmlkensahyoukadoumenu->kensahyoukadoumenus();
 $htmlkensahyoumenu = $htmlkensahyoukadoumenu->kensahyoumenus();
?>
<?php
//メニューのレイアウト変更210601
?>

<?php
$this->layout = false;
echo $this->Html->css('kensahyou');
?>

<table class='sample hesdermenu'>
  <tbody>
    <td style='border: none;align: left'>
      <font size='4'>　　</font><a href='/Kensahyoukadous' /><font size='4' color=black>メニュートップ</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukadous/kensahyoumenu' /><font size='4' color=black>検査表関係</font></a>
      <font size='4'>　>>　</font><a href='/Kensahyoukikakus/menu' /><font size='4' color=black>検査規格</font></a>
    </td>
  </tbody>
</table>

<br><br><br>

<?php
  //   echo $htmlkensahyoukadou;
?>

<?php
  //   echo $htmlkensahyoumenu;
?>
<table>
  <tr>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subtouroku.gif',array('width'=>'145','height'=>'50','url'=>array('action'=>'addformpre')));?></td>
    <td style='border: none'>　　</td>
    <td style='border: none'><?php echo $this->Html->image('/img/menus/subyobidashi.gif',array('width'=>'145','height'=>'50','url'=>array('action'=>'kensakupre')));?></td>
  </tr>
</table>
