<?php
include '../vendor/autoload.php';
use Myclass\Config;
use Medoo\Medoo;
use Myclass\Token;

header("Access-Control-Allow-Origin:*");

$config = Config::instance();
if(!isset($_GET['phone'])){
	echo json_encode($config['reponse']['error']['Bad Request']);
	die();
}

extract($_GET);
$preg = preg_match('/^1[\d]{10}$/',$phone);
if(!$preg){
	echo json_encode(array_merge($config['reponse']['error']['Bad Request'],['data'=>'手机号码不正确']));
	die;
}

$mysql = new Medoo($config['mysql']);

$token = Token::instance();

$has = $mysql->has('user',['user_id'=>$phone]);

$jwt = $token->setToken($phone);

if($has){
	echo json_encode(array_merge($config['reponse']['success'],['token'=>$jwt]));
	die;
}


$nickname = str_replace(substr($phone,5,8),'****', $phone);

$insert = $mysql->insert('user',['user_id'=>$phone,'nickname'=>$nickname]);
if($insert){
	echo json_encode(array_merge($config['reponse']['success'],['token'=>$jwt]));
}else{
	echo json_encode($config['reponse']['Server Error']);
}


?>