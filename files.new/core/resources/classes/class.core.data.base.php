<?php
class CoreDataBase
{
	var $SchemaDB	= 'testing,public';
	var $PortDB 	= 3306;
	var $AfectedRows;
	var $StreamConnection;
	var $Error;
	var $LastQuery;
	
	public function Connect($UserDB='roller', $PasswordDB='Oraprod1810', $DataBase='roller2', $ServerDB='127.0.0.1',$TypeDB='Mysql'){
		
		$this->UserDB 		= $UserDB;
		$this->PasswordDB	= $PasswordDB;
		$this->DataBase		= $DataBase;
		$this->ServerDB		= $ServerDB;
		$this->TypeDB		= $TypeDB;
		
		$this->StartConnection();
		if(!$this->StreamConnection){
			$this->Error = "No ha sido posible conectarse a la base de datos. Error en la conexi&oacute;n: ".$this->ConnectionError();
			return false;
		}else{
			if(!$this->SelectDB()){
				$this->Error = "No ha sido posible conectarse a la base de dato. Error al seleccionar la base de datos: ".$this->ConnectionError();
				return false;
			}else{
				return true;
			}
		}
	}
	public function SelectDB()
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				return mysqli_select_db($this->StreamConnection,$this->DataBase)? true : false;
			break;
			case "Postgress":
				return pg_query($this->StreamConnection, "set search_path to ".$this->SchemaDB)? true : false;
			break;
			case "Oracle":
				return true;
			break;
		}
	}
	public function ConnectionError()
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				return mysqli_error($this->StreamConnection);
			break;
			case "Postgress":
				return pg_last_error();
			break;
			case "Oracle":
				$Error = oci_error($this->StartConnection());
				return $Error['message'];
			break;
		}
	}
	public function StartConnection()
	{
		switch($this->TypeDB)
        {
        	case "Mysql":
            	$this->StreamConnection = mysqli_connect($this->ServerDB,$this->UserDB,$this->PasswordDB,$this->DataBase,$this->PortDB);
			break;
			case "Postgress":
				$this->StreamConnection = pg_connect("host=".$this->ServerDB." port=5432 dbname=".$this->DataBase." user=".$this->UserDB." password=".$this->PasswordDB);
			break;
			case "Oracle":
            	$this->StreamConnection = oci_connect($this->UserDB, $this->PasswordDB, $this->ServerDB."/".$this->DataBase);
            break;
		}
	}
	public function GetInsertId()
	{
		switch($this->TypeDB)
        {
			case "Mysql":
            	return $this->StreamConnection->insert_id;
			break;
		}
	}
	public function Disconnect()
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				mysqli_close($this->StreamConnection);
			break;
			case "Postgress":
				pg_close($this->StreamConnection);
			break;
			case "Oracle":
				oci_close($this->StreamConnection);
			break;
		}
	}
	public function GetQuery($Operation,$Table='',$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		return $this->QueryBuild($Operation,$Table,$Fields,$Where,$Order,$GroupBy,$Limit);
	}
	protected function ExecQuery($Query)
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				$Result = mysqli_query($this->StreamConnection,$Query);
				if(!$Result) $this->GetError(mysqli_error($this->StreamConnection));
				return $Result;
			break;
		}
	}
	public function Insert($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		$Query	= $this->QueryBuild("INSERT",$Table,$Fields,$Where,$Order,$GroupBy,$Limit);
		$this->ExecQuery($Query);
		return $this->GetInsertId();
	}
	
	public function Update($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		$Query	= $this->QueryBuild("UPDATE",$Table,$Fields,$Where,$Order,$GroupBy,$Limit);
		$this->ExecQuery($Query);
		return $this->AffectedRows();
	}
	
	public function Delete($Table,$Where)
	{
		$Query	= $this->queryBuild("DELETE",$Table,$Where);
		$this->ExecQuery($Query);
		return $this->AffectedRows();
	}
	public function Select($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		$Query	= $this->QueryBuild("SELECT",$Table,$Fields,$Where,$Order,$GroupBy,$Limit);
		switch($this->TypeDB)
		{
			case "Mysql":
				$Query	= $this->ExecQuery($Query);
				$Data	= array();
				while($Data[]=mysqli_fetch_assoc($Query)){}
				array_pop($Data);
				$Data = Core::Utf8EncodeArray($Data);
				return $Data;
			break;
		}
	}
	public function SelectRow($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		$Query	= $this->QueryBuild("SELECT",$Table,$Fields,$Where,$Order,$GroupBy,$Limit);
		switch($this->TypeDB)
		{
			case "Mysql":
				$Query	= $this->ExecQuery($Query);
				while($Data[]=mysqli_fetch_row($Query)){}
				array_pop($Data);
				$Data = Core::Utf8EncodeArray($Data);
				return $Data;
			break;
		}
	}
	public function NumRows($Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		$Query	= $this->QueryBuild("SELECT",$Table,$Fields,$Where,$Order,$GroupBy,$Limit);
		switch($this->TypeDB)
		{
			case "Mysql":
				$Result = mysqli_num_rows($this->ExecQuery($Query));
				return $Result;
			break;
		}
	}
	public function AffectedRows()
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				return $this->AfectedRows = mysqli_affected_rows($this->StreamConnection);
			break;
		}
	}
	public function TableData($Table)
	{
		$Query = $this->QueryBuild('DATA',$Table);
		// return $this->ExecQuery($Query);
		switch($this->TypeDB)
		{
			case "Mysql":
				$Query	= $this->ExecQuery($Query);
				$Data	= array();
				while($Data[]=mysqli_fetch_assoc($Query)){}
				array_pop($Data);
				$Data = Core::Utf8EncodeArray($Data);
				return $Data;
			break;
		}
	}
	public function QueryBuild($Operation,$Table,$Fields='',$Where='',$Order='',$GroupBy='',$Limit='')
	{
		if($Fields)
			$Fields	= utf8_decode($Fields);
		if($Where)
			$Where	= utf8_decode($Where);
		switch(strtolower($Operation))
		{
			case "select":
				$Query = $this->SelectBuild($Table,$Fields,$Where,$Order,$GroupBy,$Limit);
			break;
			case "insert":
				$Query = $this->InsertBuild($Table,$Fields,$Where);
			break;
			case "update":
				$Query = $this->UpdateBuild($Table,$Fields,$Where);
			break;
			case "delete":
				$Query = $this->DeleteBuild($Table,$Fields);
			break;
			case "data":
				$Query = $this->DataBuild($Table);
			break;
			default:
				$Query = $Operation;
			break;
		}
		$this->LastQuery = $Query;
		return $Query;
	}
	public function SelectBuild($Table,$Fields,$Where='',$Order='',$GroupBy='',$Limit='')
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				$Fields	= $Fields ? $Fields : '*';
				$Where	= $Where ? ' WHERE '.$Where : '';
				$GroupBy= $GroupBy ? ' GROUP BY '.$GroupBy : '';
				$Order	= $Order ? ' ORDER BY '.$Order : '';
				$Limit	= $Limit ? ' LIMIT '.$Limit : '';
				return 'SELECT '.$Fields.' FROM '.$Table.$Where.$GroupBy.$Order.$Limit;
			break;
		}
	}
	public function InsertBuild($Table,$Fields,$Values)
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				return 'INSERT INTO '.$Table.' ('.$Fields.')VALUES('.$Values.')';
			break;
		}
	}
	public function UpdateBuild($Table,$Values,$Where)
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				$Where	= $Where ? ' WHERE '.$Where : '';
				return 'UPDATE '.$Table.' SET '.$Values.$Where;
			break;
		}
	}
	public function DeleteBuild($Table,$Where)
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				$Where	= $Where ? ' WHERE '.$Where : '';
				return 'DELETE FROM '.$Table.$Where;
			break;
		}
	}
	public function DataBuild($Table)
	{
		switch($this->TypeDB)
		{
			case "Mysql":
				return 'DESCRIBE '.$Table; //SHOW COLUMNS FROM table;
			break;
		}
	}
	
	protected function GetError($Error)
	{
		$CreatorID = $_SESSION['user_id']?$_SESSION['user_id']:"0";
		echo $this->LastQuery();
		$this->Insert('core_log_error',"error,type,description,created_by,creation_date","'".addslashes($Error)."','MySQL','".addslashes($this->LastQuery())."',".$CreatorID.",NOW()");
		
		echo $Error;
	}
	
	public function LastQuery()
	{
		return $this->LastQuery;
	}
	public function LastError()
	{
		return $this->Error;
	}
}
?>
