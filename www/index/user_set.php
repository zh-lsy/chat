<?php
include '../vendor/autoload.php';
use Myclass\Config;
use Medoo\Medoo;
use Myclass\Token;

header('Access-Control-Allow-Origin: *');


if(empty($_GET['token'])){
	echo json_encode($config['reponse']['error']['Bad Request']);
	die;
}
$config = Config::instance();
$token = Token::instance();
$id = $token->getId($_GET['token']);
$mysql = new Medoo($config['mysql']);
$userInfo = $mysql->select('user',['nickname','header'],['user_id'=>$id]);
echo json_encode(array_merge($config['reponse']['success'],$userInfo[0]));


?>