<?php 

declare(strict_types=1);
namespace App;
class Session{
	public function start():void{
		session_start();

	}
	public function setData(string $key, $value):void{
		$_SESSION[$key]=$value;

	}
	public function getData(string $key){
		return !empty($_SESSION[$key])?$_SESSION[$key]:null;
	}
	public function save():void{
		session_write_close();
	}
	public function flush(string $key){
		//get message
		$value=$this->getData($key);
		//clean message from session
		$this->unset($key);
		return $value;

	}
	public function unset(string $key):void{
		unset($_SESSION[$key]);
	}
}