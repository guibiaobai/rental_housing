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
		//����js����
		die();
	}
	if($type==4)
	{
	   $p1->testlist();
		//����js����
		die();
	}
}

$p1->testadd();



?>