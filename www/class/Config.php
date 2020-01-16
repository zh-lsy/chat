<?php
namespace Myclass;


class Config implements \ArrayAccess{
	private static $_instance = null;

	private $config = [

		'reponse' => [
			'success' => ['statuscode' => 200,'data' => '请求成功'],
			'error' => [
				'Bad Request' => ['statuscode' => 400,'data' => '请求的数据错误'],
				'Server Error' => ['statuscode' => 500,'data' => '服务器内部错误']	
			]		
		],				

		'jwt' => [
			'key' => '&jGF75Nfcc(,^D',
			'payload' => ['iss' => 'lsy','exp' => '','sub' => 'http://chat.com']
		],

		'mysql' => [
			'database_type' => 'mysql',
    		'database_name' => 'chat',
    		'server' => '127.0.0.1',
    		'username' => 'lsy',
    		'password' => '123',
    		'charset' => 'utf8'
		]

	];

	public function __construct(){
		$this->config['jwt']['payload']['exp'] = time();
	}


	public static function instance(){
		if(!(self::$_instance instanceof self)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function offsetSet($offset, $value) {
        
    }

    public function offsetExists($offset) {
        
    }

    public function offsetUnset($offset) {
       
    }

    public function offsetGet($offset) {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }

	public function __clone(){

	}


}


?>
