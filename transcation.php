<html>




<script type="text/javascript">

function show()
{

document.getElementById('new').style.display="";
}

function hide()
{
document.getElementById('new').style.display="none";
}


function show2()
{

document.getElementById('general').style.display="";
}

function hide2()
{
document.getElementById('general').style.display="none";
}


function show3()
{

document.getElementById('out').style.display="";
}

function hide3()
{
document.getElementById('out').style.display="none";
}




</script>

<div style="position:absolute;top:200px;left:350px;width:600px;height:400px;background-color:#ffeeee;">


�½�����
<input name="name" type="button" onClick="show();" value="��ʾ">
<input name="name" type="button" onClick="hide();" value="����">


��ͨ����
<input name="name" type="button" onClick="show2();" value="��ʾ">
<input name="name" type="button" onClick="hide2();" value="����">


�˳�����
<input name="name" type="button" onClick="show3();" value="��ʾ">
<input name="name" type="button" onClick="hide3();" value="����">







<div  id="new"  style="display: none" >

<form action="transcationAction.php" method="post">
<input type="hidden" name="type" value="1">
�û�<input type="txt" name="user"  value="<?php  

if(isset($_GET['name']))
echo $_GET['name'];

?>"><br>
����<input type="txt" name="room"><br>
��ʼʱ��<input type="txt" name="start"><br>


<input type="submit" value="�ύ">

</form>




</div>
<div  id="general"  style="display: none">
��ͨ����


<form action="transcationAction.php" method="post">

����<input type="txt" name="room2"><br>
�ϸ���ˮ<input type="txt" name="water_cost_last"><br>
<input type="hidden" name="type" value="2">
�ϸ��µ�<input type="txt" name="electric_cost_last"><br>

<input type="submit" value="�ύ">

</form>

</div >
<div id="out"  style="display: none">
�˳�����

<form  action="transcationAction.php" method="post">
����<input type="txt" name="room3"><br>

�ϸ���ˮ<input type="txt" name="water_cost_last"><br>
<input type="hidden" name="type" value="3">
�ϸ��µ�<input type="txt" name="electric_cost_last"><br>
<input type="submit" value="�ύ">
</form>
</div>





<br>
<br>
<br>
<a href="index.html">������ҳ</a><br>
<div>


</html>