<?php
namespace Myclass;
@include '../vendor/autoload.php';
use Myclass\Config;
use Firebase\JWT\JWT;

class Token{
	public static $_instance;
	public $config;

	public function __construct(){
		$this->config = Config::instance();
	}

	public static function instance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}


	public function setToken($id){
		$payload = $this->config['jwt']['payload'];
		$payload['uid'] = $id;
		$payload['exp'] = time() + 9000;
		$jwt = JWT::encode($payload, $this->config['jwt']['key']);
		return $jwt;
	}

	public function getId($token){
		$decoded = JWT::decode($token, $this->config['jwt']['key'], array('HS256'));
		$decoded_array = (array) $decoded;
		return $decoded_array['uid'];
	}

}



?>