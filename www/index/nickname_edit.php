<?php
include '../vendor/autoload.php';
use Myclass\Config;
use Medoo\Medoo;
use Myclass\Token;

 header('Access-Control-Allow-Origin: *');
if(!isset($_GET['token'])){
	echo json_encode($config['reponse']['error']['Bad Request']);
	die;
}

extract($_GET);
if(strlen($nickname)>12){
	echo json_encode(array_merge($config['reponse']['success'],['data'=>'昵称长度不能超过4个']));
	die;
}

$config = Config::instance();
$token = Token::instance();
$id = $token->getId($_GET['token']);
$mysql = new Medoo($config['mysql']);

$update = $mysql->update('user',['nickname'=>$nickname],['user_id'=>$id]);

if($update){
	echo  json_encode(array_merge($config['reponse']['success'],['nickname'=>$nickname])); 
}else{
	echo json_encode($config['reponse']['error']['Server Error']);
}




?>