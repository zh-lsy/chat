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
// $message = $mysql->select('message','*', [
// 	"OR" => [
// 		"AND" => [
// 			"to_id" => $id,
// 			"from_id" => $_GET['side']
// 		],
// 		"AND" => [
// 			"to_id" => $_GET['side'],
// 			"from_id" => $id
// 		]
// 	]
// ]);
$message = $mysql->query("select * from message where (to_id={$id} and from_id={$_GET['side']}) or (to_id={$_GET['side']} and from_id={$id})")->fetchAll(\PDO::FETCH_ASSOC);
echo json_encode($message);


?>