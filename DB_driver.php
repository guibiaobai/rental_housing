<?

//�����������ݿ���ص��� ȫ�����ڹ�����
class rootDao 
{
	public $link; //��ʾ����
	public function __construct($localhost,$user,$password)
	{
		$this->link=mysql_connect($localhost,$user,$password);
		mysql_query("SET NAMES 'GBK'");
		mysql_select_db('myleasehome',$this->link) or die('count not<br>');
		//echo "���";
	}

	public function __destruct()
	{
		//echo is_null($this->link);
		//echo "134";
		mysql_close($this->link);
	}
}


//userģ��
class user
{
   public $id;
   public $name;
   public $sex;
   public $number;
   public $identi;
   public $factor;
   
   public function __construct($id,$name,$sex,$number,$identi,$factor)
   {
     $this->id=$id;
	 $this->name=$name;
	 $this->sex=$sex;
	 $this->number=$number;
	 $this->identi=$identi;
     $this->factor=$factor;
   }
   
}

//��ס��ģ��
class leaseroom
{
	public $id;
	public $price;
	public $isempty;

	public function __construct($id,$price,$isempty)
	{
		$this->id=$id;
		$this->price=$price;
		$this->isempty=$isempty;
	
	}

}

//����ģʽ
class transaction
{
	public $id;
	public $user_id;
	public $room_id;
	public $type;
	public $mes_time;
	public $start_time;
	public $end_time;
	public $tv_clear_lamp_cost;
	public $deposit;
	public $water_cost_last;
	public $electric_cost_last;
	public $water_cost;
	public $electric_cost;
	public $return_men;
	public $last_recep_time;
	
	public function __construct($a1,$a2,$a3,$a4,$a5,$a6,$a7,$a8,$a9,$a10,$a11,$a12,$a13,$a14,$a15)
	{
	$this->id=$a1;
	$this->user_id=$a2;
	$this->room_id=$a3;
	$this->type=$a4;
	$this->mes_time=$a5;
	$this->start_time=$a6;
	$this->end_time=$a7;
	$this->tv_clear_lamp_cost=$a8;
	$this->deposit=$a9;
	$this->water_cost_last=$a10;
	$this->electric_cost_last=$a11;
	$this->water_cost=$a12;
	$this->electric_cost=$a13;
	$this->return_men=$a14;
	$this->last_recep_time=$a15;
	}
}




//userdao
class userdao extends rootDAO
{
	public function __construct($localhost,$user,$password)
	{
		parent::__construct($localhost,$user,$password);
	}

	public function add(user $user)
	{
		$sql="INSERT INTO `user`(`id`, `name`, `sex`, `number`, `identi`, `factor`) VALUES (NULL,'$user->name', '$user->sex', '$user->number', '$user->identi', '$user->factor')";
		echo $sql;
		if(mysql_query($sql,$this->link))
		{
			echo "insert success";
		}else
		{
			echo "insert fail";
		}
	}
	
	//����idɾ��һ���û�
	public function del($id)
	{
		echo "del";
	}
	//����һ���û� ����һ���û�
	public function update(user $user)
	{
		echo "updata";
	}
	
	//����id ѡ��һ���û�
	public function select($id)
	{
		echo "select";
	}
	public function __destruct()
	{
		parent::__destruct();
	}

}

//lease_room
class lease_roomdao extends rootDAO
{
	public function __construct($localhost,$user,$password)
	{
		parent::__construct($localhost,$user,$password);
	}

	//����һ������
	public function add(leaseroom $newroom)
	{
		$sql="INSERT INTO  `myleasehome`.`lease_room` (`ID` ,`price` ,`isempty`)VALUES ('$newroom->id','$newroom->price','$newroom->isempty')";
		echo $sql;
		if(mysql_query($sql,$this->link))
		{
			echo "exucse susession";
		}else
		{
			echo  "excuse fial";
		}
	}
	
	//ɾ������ ����id����
	public function del($id)
	{
		echo "del";
	}

	//���·������Ϣ
	public function update(leaseroom $user)
	{
		echo "updata";
	}

	//����id���ط�����Ϣ
	public function select($id)
	{
		echo "select";
	}

	public function __destruct()
	{
		parent::__destruct();
	}
}




class transcationdao extends rootDAO
{
	public function __construct($localhost,$user,$password)
	{
		parent::__construct($localhost,$user,$password);
	}
	public function __destruct()
	{
		// echo "tradao close";
		// parent::__destruct();
	}

//����һ����¼
	public function add(transaction $ta)
	{
		$sql = "INSERT INTO  `myleasehome`.`transaction_mother` (\r\n`ID` ,\r\n`user_id` ,\r\n`room_id` ,\r\n`type` ,\r\n`mes_time` ,\r\n`Start_time` ,\r\n`End_time` ,\r\n`tv_clear_lamp_cost` ,\r\n`deposit` ,\r\n`Water_cost_Last` ,\r\n`Electric_cost_Last` ,\r\n`Water_cost` ,\r\n`Electric_cost` ,\r\n`Return_men` ,\r\n`Last_recep_time`\r\n)\r\nVALUES (\r\nNULL ,  '{$ta->user_id}',  '{$ta->room_id}', '{$ta->type}',  curdate(),  '{$ta->start_time}',  '{$ta->end_time}', \r\n '{$ta->tv_clear_lamp_cost}', '{$ta->deposit}', '{$ta->water_cost_last}',  '{$ta->electric_cost_last}',  '{$ta->water_cost}',\r\n '{$ta->electric_cost}',  '{$ta->return_men}',  '{$ta->last_recep_time}'\r\n)";
		echo $sql;
		if(mysql_query($sql,$this->link))
		{
			echo  "excute 1";
		}else
		{
			echo "faile";
		}
	}  

	//����idɾ��
	public function del($id){echo "del";}

	//����һ����¼
	public function update(transaction $user){echo "updata";}

	//����id ѡ��һ����¼
	public function select($id){echo "select";}

	//ȡ��һ�������һ��id
	public function sqlfind($sql)
	{
		$result=mysql_query($sql,$this->link);
		$row = mysql_fetch_array($result);
		return $row[0];
	}

	//��ȡ�޸ļ���	
	public function sqllist($sql)
	{
		$list=array();
		$i=0;
		$result=mysql_query($sql,$this->link);
		while($row=mysql_fetch_row($result))
		{
			$list[$i]=$row;
			$i++;
		}
		return $list;
	}
	
	//�޸�sql
	public function sqlquery($sql)
	{
		if(mysql_query($sql,$this->link))
		{
		}else
		{
			die("�޸�ʧ��");
		}
	}
}
