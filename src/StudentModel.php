<?php 
declare(strict_types=1);

namespace App;

class StudentModel{
	
	private $db;
	public function __construct(){
		$this->db=new Database();

	}
	public function saveStudent($name,$surname,$password,$sex,$groupnum,$email,$egescore):void{
		
		$statement= $this->db->connect->prepare("INSERT INTO students (name,surname,password,sex,groupnum,email,egescore) VALUES (:name,:surname,:password,:sex,:groupnum,:email,:egescore)");
		$statement->execute([
			'name'=>$name,
			'surname'=>$surname,
			'password'=>password_hash($password,PASSWORD_DEFAULT),
			'sex'=>$sex,
			'groupnum'=>$groupnum,
			'email'=>$email,
			'egescore'=>$egescore
		]);
	}
	public function getStudentByName($name,$surname){
		$statement= $this->db->connect->prepare("SELECT * FROM students WHERE name=:name AND surname=:surname");
		$statement->execute([
			'name'=>$name,
			'surname'=>$surname
		]);
		$user=$statement->fetch();
		return ($user);
	}
	public function getStudentByEmail($email){
		$statement= $this->db->connect->prepare("SELECT * FROM students WHERE email=:email");
		$statement->execute([
			'email'=>$email
		]);
		$user=$statement->fetch();
		return $user;
	}
	public function getStudentById($id):array{
		$statement= $this->db->connect->prepare("SELECT * FROM students WHERE id=:id");
		$statement->execute([
			'id'=>$id
		]);
		$user=$statement->fetch();
	
		return $user;
	}
	public function getAllStudents(int $page=1, int $limit=5,string $order='egescore',string $direction='DESC'):array{
		$statement=$this->db->connect->prepare("SELECT * FROM students ORDER BY ".$order." ".$direction." LIMIT ".($page-1)*$limit.",".$limit);
		$statement->execute();
		return $statement->fetchAll();
	}
	public function getStudentsByAll(int $page=1, int $limit=5,string $order='egescore',string $direction='DESC',$val):array{
		if (!empty($val)) {
			
		
			$query="WHERE 
			 name LIKE :name
			 OR surname LIKE :surname 
			 OR email LIKE :email";
			$statement=$this->db->connect->prepare("SELECT * FROM students ".$query." ORDER BY ".$order." ".$direction." 
				LIMIT ".($page-1)*$limit.",".$limit);
			$statement->execute([
				'name'=>'%'.$val.'%',
				'surname'=>'%'.$val.'%',
				'email'=>'%'.$val.'%',
			]);
			return $statement->fetchAll();
		}
		
	}
	public function updateStudent($id,$name,$surname,$password,$sex,$groupnum,$email,$egescore):void{
		$statement=$this->db->connect->prepare("UPDATE students SET name=:name,surname=:surname,password=:password,sex=:sex,groupnum=:groupnum,email=:email,egescore=:egescore WHERE id=:id");
		$statement->execute([
			'name'=>$name,
			'surname'=>$surname,
			'password'=>password_hash($password,PASSWORD_DEFAULT),
			'sex'=>$sex,
			'groupnum'=>$groupnum,
			'email'=>$email,
			'egescore'=>$egescore,
			'id'=>$id
		]);
	}
}