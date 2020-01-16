<?php
include '../vendor/autoload.php';
use Myclass\Config;
use Medoo\Medoo;
use Myclass\Token;

 header('Access-Control-Allow-Origin: *');
$tempName = $_FILES['file']['name'];
$extension = pathinfo($tempName)['extension'];
$config = Config::instance();
$token = Token::instance();

$allow = ['jpg','jpeg'];

if(!in_array($extension, $allow)){
	echo json_encode($config['reponse']['error']['Bad Request']);
	die;
}


$id = $token->getId($_POST['jwt']);

$imgName = uniqid() .'.'. $extension;
if(move_uploaded_file($_FILES['file']['tmp_name'], __DIR__.'/../upload/'.$imgName)){
	$mysql = new Medoo($config['mysql']);
	$update = $mysql->update('user',['header'=>$imgName],['user_id'=>$id]);
	if($update){
		echo json_encode(array_merge($config['reponse']['success'],['imgname'=>$imgName]));
	}
}






?>