<?php 
declare(strict_types=1);

namespace App;
use PDO;
use InvalidArgumentException;

class Database {

	
	public $connect;
	public $configparser;
	public function __construct()
	{
		try {
			$this->configparser=new ConfigParser();
			$this->dbsettings=$this->configparser->getConfigArray()['db_config'];
			$this->connect=new PDO($this->dbsettings['dsn'],$this->dbsettings['user'],$this->dbsettings['password']);
			$this->connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
			$this->connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);			
		} catch (PDOException $e) {
			throw new InvalidArgumentException('db error: '.$e->getMessage());
		}
	}


}