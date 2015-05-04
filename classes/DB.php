<?php
class DB{
	private static $_instance = null,
				   $_pdo,
				   $_query,
				   $_error = false,
				   $_results,
				   $_count = 0;
	
	private function __construct(){
		try{
			//construct new PDO object
			$this ->_pdo = new PDO('mysql:host='.Config::get('mysql/host').';dbname='.Config::get('mysql/db') , Config::get('mysql/username'), Config::get('mysql/password'));
			
		}catch (PDOException $e){
			die($e -> getMessage());
		}
	}
	
	public static function getInstance(){
		//if DB class is instantiated then return the instance or instantiate it and return it 
		if(!isset(self::$_instance)){
			self::$_instance = new DB();
		}
		
		return self::$_instance;
	}
	
	public function query($sql, $params = array()){
		$this->_error = false;
		if($this->_query = $this->_pdo -> prepare($sql)){
			$x=1;
			if(count($params)){
				foreach ($params as $param){
					$this->_query-> bindValue($x, $param);//this line binds the param value to the $x indexed question mark
					$x++;
				}
			}
			
			if ($this->_query -> execute()){
				$this->_results = $this->_query-> fetchAll(PDO::FETCH_OBJ);
				$this->_count= $this-> _query->rowCount();
			}else {
				$this->_error = true;
			}
		}
		
		return $this; //this return the current object
	}
	
	private function action($action,$table,$where = array()){
		if(count($where)===3){
			$operators = array('=','>','<','>=','<=');
			
			$field    = $where[0];
			$operator = $where[1];
			$value    = $where[2];
			
			if(in_array($operator, $operators)){
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";//this prepares the sql query string
				if(!$this->query($sql,array($value))->error()){
					return $this;//this return the current object
				}
			}
		}
		return false;
	}
	
	//get method this is used to get data from db
	public function get($table, $where){
		return $this->action('SELECT *', $table, $where);
	}
	//delete data from table
	public function delete($table, $where){
		return $this->action('DELETE', $table, $where);
	}
	//this inserts data to db
	public function insert($table, $fields = array()){
			$keys = array_keys($fields);
			$values = '';
			$x = 1;
				
			foreach ($fields as $field){
				$values .= '?';//adding question marks where we have to add parameter 
				if($x < count($fields)){
					$values .= ',';
				}
	
				$x++;
					
			}
							
			$sql = "INSERT INTO {$table} (`". implode('`,`', $keys)."`) VALUES ({$values})";
						
			if(!$this->query($sql,$fields)-> error()){
				return true;
			}				
		
		return false;
	}
	
	public function update($table, $id, $fields){
		$set ='';
		$x = 1;
		
		foreach ($fields as $name => $value){
			$set .= "{$name} = ?";//adding question marks where we have to add parameter 
			if($x<count($fields)){
				$set .= ',';
			}
			$x++;
		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
		
		if(!$this->query($sql,$fields)->error()){
			return true;//there is no error then return true
		}
		
		return false;
		
	}
	
	public function results(){
		return $this->_results;
	}
	
	public function first(){
		return $this->results()[0];//returns first row of the table
	}
	
	public function error(){
		return $this->_error;
	}
	
	public function count(){
		return $this-> _count;
	}
	
}
?>