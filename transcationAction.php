<?php

require_once("Action.php");
require_once("DB_driver.php");

$p1=new transcationAction();

if(isset($_POST['typetwo']))
{
	$type=$_POST['typetwo'];
	if($type==3)
	{
		$p1->testone();
		//返回js数据
		die();
	}
	if($type==4)
	{
	   $p1->testlist();
		//返回js数据
		die();
	}
}

$p1->testadd();



?>