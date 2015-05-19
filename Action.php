<?php

//数据库设计问题   偶合度太高 一个用户对一个房间  房间开放 要退房，无法初始化一点原始数据
require_once("DB_driver.php");


//useraction
class useraction
{
	public $userdao;
	public function __construct()
	{
		//设计有问题  不需要参数
		$this->userdao=new userdao("localhost","root","");
	}
	
	public function select()
	{   
		$this->userdao->select(100);
	}

	public function update()
	{
		$this->userdao->update(new user(1,2,3,4,5,6));
	}
	
	public function insert()
	{
		//接受数据  过滤转移数据  
		$name="";
		if(isset($_POST['name']))
		{
			$name=$_POST['name'];
			$sex=$_POST['sex'];
			$number=$_POST['number'];
			$identi=$_POST['identi'];
			$factor=$_POST['factor'];
		}
		$id="";  
		$this->userdao->add(new user($id,$name,$sex,$number,$identi,$factor));
	   //页面跳转 ??
	   //header("Location:http://127.0.0.1/aa/lsroom/transcation.php?name=$name");
	}
}

//lsrootaction
class lease_roomAction
{
	public $lease_roomdao;
	public function __construct()
	{
		//设计有问题  不需要参数
		$this->lease_roomdao=new lease_roomdao("localhost","root","");
	}
	public function select()
	{   
	   $this->lease_roomdao->select(100);
	   //$a->select();
	}

	public function update()
	{
		$this->lease_roomdao->update(new leaseroom(1,2,3));
	}

	public function insert()
	{
		if(isset($_POST['room']))
		{
			$id=$_POST['room'];
			$price=$_POST['price'];
		}
		$isempty=1;
		$this->lease_roomdao->add(new leaseroom($id,$price,$isempty));
	}
}


class transcationAction
{
	public $transcationdao;
	public function __construct()
	{
		//设计有问题  不需要参数
		$this->transcationdao=new transcationdao("localhost","root","");
	}
	
	//什么也没有实现
	public function update()
	{
		$this->transcationdao->update(new transaction(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15));
	}


	//查找是否有空房子
	public function testone()
	{
		$sql="SELECT `ID` FROM `lease_room` WHERE `isempty`=1";
		$array=$this->transcationdao->sqllist($sql);
		echo  json_encode($array);
	}

	//空闲列表
	public function testlist()
	{
		//$sql="SELECT `ID` FROM `lease_room` WHERE `isempty`=1;";
		//$sql="SELECT `ID` FROM `lease_room` WHERE `isempty`=0";
		//查询租出的的房间
		//根据房间 查处最近的房间号  SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=103 or `room_id`=105;
		//SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=103 union SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=105 ;
		//最前的id
		//"SELECT max(`ID`) FROM `transaction_mother` group by `room_id` and `room_id`"
		$sql="SELECT `room_id`,curdate(),`End_time`,datediff(`End_time`,curdate()) as remain  FROM 

		`transaction_mother` where `ID` in (SELECT max(`ID`) FROM `transaction_mother` group by `room_id`)  and `room_id` in (SELECT `ID` FROM `lease_room` WHERE `isempty`=0)";


		$array=$this->transcationdao->sqllist($sql);
		echo  json_encode($array);
	}
	
	public function testadd()
	{
		  $type=$_POST['type']; //获取类型
		 $id="";
		 $user_id="";
		 $room_id=""; 
		 $mes_time="";
		 $start_time="";
		 $end_time="";
		 $tv_clear_lamp_cost=15; //常量水电费
		 $deposit=0;  //押金
		 //参考模式2 从数据库中获取上月 水费 电费
		 
		// $water_cost_last=$_POST['water_cost_last']; //结算上次水费
		 //$electric_cost_last=$_POST['electric_cost_last'];//结算上次电费
		 $water_cost="";
		 $electric_cost="";
		 $return_men="";
		 $last_recep_time="";
		 
		 if($type==1)
		 {
			//echo 'moshi1';
			if(isset($_POST['user']))
			{
				//根据用户名 获取id
				$user=$_POST['user'];
				$usersql="SELECT `id` FROM `user` WHERE `name`='$user'";
				$user_id=$this->transcationdao->sqlfind($usersql);		
				
				//获取房间
				$room_id=$_POST['room'];
				
				//获取开始时间
				$start_time=$_POST['start'];
				
				//判断房间是否为空
				$room_idsql="SELECT `isempty` FROM `lease_room` WHERE `ID`=$room_id";
				$ismen=$this->transcationdao->sqlfind($room_idsql);
				
				//初始化水费
				$watchsql="SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `Water_cost_Last` in (SELECT max(`Water_cost_Last`) FROM `transaction_mother` group by `room_id`) and `room_id`=$room_id";
				$water_cost_last=$this->transcationdao->sqlfind($watchsql);	
					
					if($water_cost_last==null)
					{
						$water_cost_last=0;
					}
				
				//初始化电费
				$electricsql="SELECT `Electric_cost_Last` FROM `transaction_mother` WHERE `Electric_cost_Last` in (SELECT max(`Electric_cost_Last`) FROM `transaction_mother` group by `room_id`) and `room_id`=$room_id";
				$electric_cost_last=$this->transcationdao->sqlfind($electricsql);	
			
				if($electric_cost_last==null)
					{
						$electric_cost_last=0;
					}
			
				//取出空数据如何处理
				
				//如何非空 修改为空 弹出错误
				if($ismen==1)
				{
					$room_idsql="UPDATE `lease_room` SET `isempty`=0 WHERE `ID`=$room_id";
					$this->transcationdao->sqlquery($room_idsql);
				}else
				{
					echo "改房间出租中";
					die("错误");
				}

				//解析开始时间
				$start_time=date($start_time);
				list($year,$month,$day)=explode("-",$start_time);
				
				//计算到期时间
				$end_time=date("Y-m-d", mktime(0,0,0,$month+1,$day-1,$year));
				
				//初始化押金
				$deposit=100;
				
				//计算上个月的水费
				$watersql="select ($water_cost_last-(SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*0";
				$water_cost=$this->transcationdao->sqlfind($watersql);   
				
				//计算上个月电费
				$electric="select ($electric_cost_last-(SELECT `Electric_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*0";
				$electric_cost=$this->transcationdao->sqlfind($electric);				
				
			}
		 }
		 if($type==2)
		 {
		 
			$water_cost_last=$_POST['water_cost_last'];
			$electric_cost_last=$_POST['electric_cost_last'];
			
			//echo 'moshi2';
			if(isset($_POST['room2']))
			{
				//房间id
				$room_id=$_POST['room2'];

				//利用房间获取用户id
				$user_idsql="SELECT `user_id` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)";
				$user_id=$this->transcationdao->sqlfind($user_idsql);

				//上一月尾+1 普通订单的开始
				$strat_timesql="select interval 1 day +(SELECT `End_time` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id))";
				$end_timesql="select interval -1 day+(select interval 1 month +(select interval 1 day +(SELECT `End_time` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id))))";
				$start_time=$this->transcationdao->sqlfind($strat_timesql);
				$end_time=$this->transcationdao->sqlfind($end_timesql);
				
				//计算上个月水费电费
				$watersql="select ($water_cost_last-(SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*1";
				$water_cost=$this->transcationdao->sqlfind($watersql);
				$electric="select ($electric_cost_last-(SELECT `Electric_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*1";
				$electric_cost=$this->transcationdao->sqlfind($electric);
			}
		 }
		 if($type==3)
		 {
		 	$water_cost_last=$_POST['water_cost_last'];
			$electric_cost_last=$_POST['electric_cost_last'];
			//echo 'moshi3';
			if(isset($_POST['room3']))
			{
				//房间id
				$room_id=$_POST['room3'];
				
				//判断房间是否为空 提示错误
				$room_idsql="SELECT `isempty` FROM `lease_room` WHERE `ID`=$room_id";
				$ismen=$this->transcationdao->sqlfind($room_idsql);
			
				if($ismen==0)
				{
					$room_idsql="UPDATE `lease_room` SET `isempty`=1 WHERE `ID`=$room_id";
					$this->transcationdao->sqlquery($room_idsql);
				}else{
					echo "该房间还没有出租";
					die("错误");
				}
				
				//获取用户id
				$user_idsql="SELECT `user_id` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)";
				$user_id=$this->transcationdao->sqlfind($user_idsql);
				
				$start_time=0;
				$end_time=0;
				$tv_clear_lamp_cost=0;
				
				//计算水费
				$watersql="select ($water_cost_last-(SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*1";
				$water_cost=$this->transcationdao->sqlfind($watersql);
				
				//计算电费
				$electric="select ($electric_cost_last-(SELECT `Electric_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*1";
				$electric_cost=$this->transcationdao->sqlfind($electric);
			}
			
		 }
		 //最后执行sql
		$this->transcationdao->add(new transaction($id,$user_id,$room_id,$type,$mes_time,$start_time,$end_time,$tv_clear_lamp_cost,$deposit,$water_cost_last,$electric_cost_last,$water_cost,$electric_cost,$return_men,$last_recep_time));
	
	}
}





?>