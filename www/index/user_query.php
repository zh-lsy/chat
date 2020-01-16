<?php
include '../vendor/autoload.php';
use Myclass\Config;
use Medoo\Medoo;
use Myclass\Token;

header('Access-Control-Allow-Origin: *');
$config = Config::instance();
if(empty($_GET['token'])){
	echo json_encode($config['reponse']['error']['Bad Request']);
	die;
}
$token = Token::instance();
try{

$id = $token->getId($_GET['token']);

}catch(Exception $e){
	echo $e->getMessage();
	die;
}

$mysql = new Medoo($config['mysql']);
$userInfo = $mysql->select('user','*');
echo json_encode($userInfo);


?>