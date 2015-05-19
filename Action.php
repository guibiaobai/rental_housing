<?php

//���ݿ��������   ż�϶�̫�� һ���û���һ������  ���俪�� Ҫ�˷����޷���ʼ��һ��ԭʼ����
require_once("DB_driver.php");


//useraction
class useraction
{
	public $userdao;
	public function __construct()
	{
		//���������  ����Ҫ����
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
		//��������  ����ת������  
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
	   //ҳ����ת ??
	   //header("Location:http://127.0.0.1/aa/lsroom/transcation.php?name=$name");
	}
}

//lsrootaction
class lease_roomAction
{
	public $lease_roomdao;
	public function __construct()
	{
		//���������  ����Ҫ����
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
		//���������  ����Ҫ����
		$this->transcationdao=new transcationdao("localhost","root","");
	}
	
	//ʲôҲû��ʵ��
	public function update()
	{
		$this->transcationdao->update(new transaction(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15));
	}


	//�����Ƿ��пշ���
	public function testone()
	{
		$sql="SELECT `ID` FROM `lease_room` WHERE `isempty`=1";
		$array=$this->transcationdao->sqllist($sql);
		echo  json_encode($array);
	}

	//�����б�
	public function testlist()
	{
		//$sql="SELECT `ID` FROM `lease_room` WHERE `isempty`=1;";
		//$sql="SELECT `ID` FROM `lease_room` WHERE `isempty`=0";
		//��ѯ����ĵķ���
		//���ݷ��� �鴦����ķ����  SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=103 or `room_id`=105;
		//SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=103 union SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=105 ;
		//��ǰ��id
		//"SELECT max(`ID`) FROM `transaction_mother` group by `room_id` and `room_id`"
		$sql="SELECT `room_id`,curdate(),`End_time`,datediff(`End_time`,curdate()) as remain  FROM 

		`transaction_mother` where `ID` in (SELECT max(`ID`) FROM `transaction_mother` group by `room_id`)  and `room_id` in (SELECT `ID` FROM `lease_room` WHERE `isempty`=0)";


		$array=$this->transcationdao->sqllist($sql);
		echo  json_encode($array);
	}
	
	public function testadd()
	{
		  $type=$_POST['type']; //��ȡ����
		 $id="";
		 $user_id="";
		 $room_id=""; 
		 $mes_time="";
		 $start_time="";
		 $end_time="";
		 $tv_clear_lamp_cost=15; //����ˮ���
		 $deposit=0;  //Ѻ��
		 //�ο�ģʽ2 �����ݿ��л�ȡ���� ˮ�� ���
		 
		// $water_cost_last=$_POST['water_cost_last']; //�����ϴ�ˮ��
		 //$electric_cost_last=$_POST['electric_cost_last'];//�����ϴε��
		 $water_cost="";
		 $electric_cost="";
		 $return_men="";
		 $last_recep_time="";
		 
		 if($type==1)
		 {
			//echo 'moshi1';
			if(isset($_POST['user']))
			{
				//�����û��� ��ȡid
				$user=$_POST['user'];
				$usersql="SELECT `id` FROM `user` WHERE `name`='$user'";
				$user_id=$this->transcationdao->sqlfind($usersql);		
				
				//��ȡ����
				$room_id=$_POST['room'];
				
				//��ȡ��ʼʱ��
				$start_time=$_POST['start'];
				
				//�жϷ����Ƿ�Ϊ��
				$room_idsql="SELECT `isempty` FROM `lease_room` WHERE `ID`=$room_id";
				$ismen=$this->transcationdao->sqlfind($room_idsql);
				
				//��ʼ��ˮ��
				$watchsql="SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `Water_cost_Last` in (SELECT max(`Water_cost_Last`) FROM `transaction_mother` group by `room_id`) and `room_id`=$room_id";
				$water_cost_last=$this->transcationdao->sqlfind($watchsql);	
					
					if($water_cost_last==null)
					{
						$water_cost_last=0;
					}
				
				//��ʼ�����
				$electricsql="SELECT `Electric_cost_Last` FROM `transaction_mother` WHERE `Electric_cost_Last` in (SELECT max(`Electric_cost_Last`) FROM `transaction_mother` group by `room_id`) and `room_id`=$room_id";
				$electric_cost_last=$this->transcationdao->sqlfind($electricsql);	
			
				if($electric_cost_last==null)
					{
						$electric_cost_last=0;
					}
			
				//ȡ����������δ���
				
				//��ηǿ� �޸�Ϊ�� ��������
				if($ismen==1)
				{
					$room_idsql="UPDATE `lease_room` SET `isempty`=0 WHERE `ID`=$room_id";
					$this->transcationdao->sqlquery($room_idsql);
				}else
				{
					echo "�ķ��������";
					die("����");
				}

				//������ʼʱ��
				$start_time=date($start_time);
				list($year,$month,$day)=explode("-",$start_time);
				
				//���㵽��ʱ��
				$end_time=date("Y-m-d", mktime(0,0,0,$month+1,$day-1,$year));
				
				//��ʼ��Ѻ��
				$deposit=100;
				
				//�����ϸ��µ�ˮ��
				$watersql="select ($water_cost_last-(SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*0";
				$water_cost=$this->transcationdao->sqlfind($watersql);   
				
				//�����ϸ��µ��
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
				//����id
				$room_id=$_POST['room2'];

				//���÷����ȡ�û�id
				$user_idsql="SELECT `user_id` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)";
				$user_id=$this->transcationdao->sqlfind($user_idsql);

				//��һ��β+1 ��ͨ�����Ŀ�ʼ
				$strat_timesql="select interval 1 day +(SELECT `End_time` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id))";
				$end_timesql="select interval -1 day+(select interval 1 month +(select interval 1 day +(SELECT `End_time` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id))))";
				$start_time=$this->transcationdao->sqlfind($strat_timesql);
				$end_time=$this->transcationdao->sqlfind($end_timesql);
				
				//�����ϸ���ˮ�ѵ��
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
				//����id
				$room_id=$_POST['room3'];
				
				//�жϷ����Ƿ�Ϊ�� ��ʾ����
				$room_idsql="SELECT `isempty` FROM `lease_room` WHERE `ID`=$room_id";
				$ismen=$this->transcationdao->sqlfind($room_idsql);
			
				if($ismen==0)
				{
					$room_idsql="UPDATE `lease_room` SET `isempty`=1 WHERE `ID`=$room_id";
					$this->transcationdao->sqlquery($room_idsql);
				}else{
					echo "�÷��仹û�г���";
					die("����");
				}
				
				//��ȡ�û�id
				$user_idsql="SELECT `user_id` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)";
				$user_id=$this->transcationdao->sqlfind($user_idsql);
				
				$start_time=0;
				$end_time=0;
				$tv_clear_lamp_cost=0;
				
				//����ˮ��
				$watersql="select ($water_cost_last-(SELECT `Water_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*1";
				$water_cost=$this->transcationdao->sqlfind($watersql);
				
				//������
				$electric="select ($electric_cost_last-(SELECT `Electric_cost_Last` FROM `transaction_mother` WHERE `ID`=(SELECT max(`ID`) FROM `transaction_mother` WHERE `room_id`=$room_id)))*1";
				$electric_cost=$this->transcationdao->sqlfind($electric);
			}
			
		 }
		 //���ִ��sql
		$this->transcationdao->add(new transaction($id,$user_id,$room_id,$type,$mes_time,$start_time,$end_time,$tv_clear_lamp_cost,$deposit,$water_cost_last,$electric_cost_last,$water_cost,$electric_cost,$return_men,$last_recep_time));
	
	}
}





?>