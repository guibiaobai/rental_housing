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


新建订单
<input name="name" type="button" onClick="show();" value="显示">
<input name="name" type="button" onClick="hide();" value="隐藏">


普通订单
<input name="name" type="button" onClick="show2();" value="显示">
<input name="name" type="button" onClick="hide2();" value="隐藏">


退出订单
<input name="name" type="button" onClick="show3();" value="显示">
<input name="name" type="button" onClick="hide3();" value="隐藏">







<div  id="new"  style="display: none" >

<form action="transcationAction.php" method="post">
<input type="hidden" name="type" value="1">
用户<input type="txt" name="user"  value="<?php  

if(isset($_GET['name']))
echo $_GET['name'];

?>"><br>
房间<input type="txt" name="room"><br>
开始时间<input type="txt" name="start"><br>


<input type="submit" value="提交">

</form>




</div>
<div  id="general"  style="display: none">
普通订单


<form action="transcationAction.php" method="post">

房间<input type="txt" name="room2"><br>
上个月水<input type="txt" name="water_cost_last"><br>
<input type="hidden" name="type" value="2">
上个月电<input type="txt" name="electric_cost_last"><br>

<input type="submit" value="提交">

</form>

</div >
<div id="out"  style="display: none">
退出订单

<form  action="transcationAction.php" method="post">
房间<input type="txt" name="room3"><br>

上个月水<input type="txt" name="water_cost_last"><br>
<input type="hidden" name="type" value="3">
上个月电<input type="txt" name="electric_cost_last"><br>
<input type="submit" value="提交">
</form>
</div>





<br>
<br>
<br>
<a href="index.html">返回首页</a><br>
<div>


</html>