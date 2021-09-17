<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="http://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>

$(document).ready(function() {
    $("#auto1").focusout(function() {
      var inputNumber = $("#auto1").val();
      var resultDivision = inputNumber + 1; 
      $("#auto2").val(resultDivision);
      $("#auto3").text(resultDivision);
    })
});

</script>

<br><br><br>

<br>
<table class="white">
<tr><td width="280"><strong>自動補完テスト１</strong></td></tr>
<td><?= $this->Form->input('name_menu1', array('type'=>'text', 'label'=>false, 'id'=>"auto1")) ?></td>
<tr><td width="280"><strong>自動補完テスト２</strong></td></tr>
<td><?= $this->Form->input('name_menu2', array('type'=>'text', 'label'=>false, 'id'=>"auto2")) ?></td>
<tr><td width="280"><strong>自動補完テスト３</strong></td></tr>
<td><div id="auto3"></div></td>
</table>
<br>
