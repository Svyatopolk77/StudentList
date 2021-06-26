<?php  

declare(strict_types=1);
namespace App;
// use App\Student;
class Auth{
	private  $database;
	private $studentmodel;
	private $session;
	public function __construct(){
		$this->database=new Database();
		$this->studentmodel=new Studentmodel();
		$this->session=new Session();

	}
	
	public function signIn(array $data):bool{
		$user=$this->studentmodel->getStudentByName($data['name'],$data['surname']);
		if (empty($data['name'])) {
			throw new AuthException('Имя обязательно');
		}
		if (empty($data['surname'])) {
			throw new AuthException('Фамилия обязательна');
		}
		if (empty($data['password'])) {
			throw new AuthException('Пароль обязателен');
		}
		if (empty($user)) {
			throw new AuthException('Пользователь не найден');
		}
		if(!password_verify($data['password'], $user['password'])){
			throw new AuthException('Неверный пароль');
		}
	
	
		return true;
		}

	public function signUp(array $data):bool{
		$user=$this->studentmodel->getStudentByName($data['name'],$data['surname']);

		if (empty($data['name'])) {
			throw new AuthException('Имя обязательно');
		}
		if (empty($data['surname'])) {
			throw new AuthException('Фамилия обязательна');
		}
		if (empty($data['password'])) {
			throw new AuthException('Пароль обязателен');
		}
		if (strlen($data['password'])<6) {
			throw new AuthException('Длина пароля должна быть больше или равна 6');

		}
		if (empty($data['email'])) {
			throw new AuthException('Email обязателен');
		}
		if(!empty($user)){
			throw new AuthException('Такой пользователь уже существует');
		}
		if(!empty($this->studentmodel->getStudentByEmail($data['email']))){
			throw new AuthException('Email должен быть уникальным');
		}
		// try {
			
		// 	$this->studentmodel->saveStudent($data['name'],$data['surname'],password_hash($data['password'], PASSWORD_DEFAULT),$data['sex'],$data['groupnum'],$data['email'],$data['egescore']);
		// } catch (Exception $e) {
		// 	throw new AuthException('Непредвиденная ошибка');
		// }
		return true;
	}
}