<?php 
declare(strict_types=1);

namespace App;
class ConfigParser{
	private $confFile;
	public function __construct(){
		$this->confFile=file_get_contents(__DIR__.'/../config/config.json');
	}
	public function getConfigArray(){
		return json_decode($this->confFile,true);
	}
}