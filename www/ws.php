<?php
include 'vendor/autoload.php';
use Myclass\Config;
use Medoo\Medoo;
use Myclass\Token;
header('Access-Control-Allow-Origin: *');
class Ws {
    public $server;
    public $config;
    public $mysql;
    public function __construct() {
        $this->server = new Swoole\WebSocket\Server("0.0.0.0", 8812);
        $this->server->on('open', [$this, 'onOpen']);
        $this->server->on('message', [$this, 'onMessage']);
        $this->server->on('close', [$this, 'onClose']);
        $this->config = Config::instance();
        $this->mysql = new Medoo($this->config['mysql']);
        $this->token = Token::instance();
        $this->server->start();
    }

  
    public function onOpen($server,$request){
        // echo $request->fd."start\n";
        //获取最后一条信息和未读信息数量
        // if($id = $this->checkToken($server,$request)){
        //     if(!isset($request->get['sideId'])){
        //         $this->index($server,$request,$id);
        //     }else{
        //         $this->onOne($request,$id);
        //     } 
        // }
    }

   
    public function onMessage($server,$frame){
       //  // echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
       //  // $server->push($frame->fd, "this is server");
       // // echo $frame->data;
       // $msg = json_decode($frame->data,true);
       // // $thi->mysql->insert('message',['to_id'=>$to_id,'from_id'=>$from_id,'msg'=>$msg,'type'=>$type]);
       // // switch ($msg['type']) {
       // //     case 'text':
               
       // //         break;
           
       // //     default:
       // //         # code...
       // //         break;
       // // }

       // $sideInfo = $this->mysql->select('user',['fd_index','fd_on_one','connect_user_id'],['user_id'=>$msg['to_id']]);
       // if($sideInfo[0]['connect_user_id'] == $msg['from_id']){
       //      $server->push($sideInfo[0]['fd_on_one'],$frame->data);
       // }
       // // print_r($sideInfo);
     $opcode = $frame->opcode;
        if ($opcode == 0x08) {
            echo "Close frame received: Code {$frame->code} Reason {$frame->reason}\n";
        } else if ($opcode == 0x1) {
            echo "Text string\n";
        } else if ($opcode == 0x2) {
            echo "Binary data\n"; //
        } else {
            echo "Message received: {$frame->data}\n";
        }
    }

    
    public function onClose($server,$fd){
        // $mysql = new Medoo($config['mysql']);
        // $user_id = $mysql->
        echo $fd."close\n";
        $pdo = new \PDO("mysql:host={$this->config['mysql']['server']};dbname={$this->config['mysql']['database_name']};charset={$this->config['mysql']['charset']}",$this->config['mysql']['username'],$this->config['mysql']['password']);
        $sql = "update user set fd_index=if(fd_index={$fd},0,fd_index),fd_on_one=if(fd_on_one={$fd},0,fd_on_one),connect_user_id=0 where fd_index={$fd} or fd_on_one={$fd}";
        $stmt = $pdo->prepare($sql);
        $res = $stmt->execute();
      
        // print_r($res);
    }

    /**
    * 检查token
    * @param $server
    * @param $request
    */

    public function checkToken($server,$request){
    	if(!isset($request->get['token'])){
			return false;
		}
    	$id = $this->token->getId($request->get['token']);
    	return $id;
    }


    public function index($server,$request,$id){

          $unread =  $this->mysql->query("select a.*,sum(a.is_read=0) as badge  from (select * from message where to_id={$id} order by id desc limit 0,900) as a group by a.from_id")->fetchAll(\PDO::FETCH_ASSOC);
        $update =  $this->mysql->update('user',['fd_index'=>$request->fd],['user_id'=>$id]);
          $server->push($request->fd,json_encode($unread));
        // 推送自己的昵称和头像给其他用户(自动添加好友)
            $selfInfo =  $this->mysql->select('user',['user_id','nickname','header'],['user_id'=>$id]);
            $selfInfo[0]['type'] = 'online';
            foreach ($server->connections as $fd) {
                if ($server->isEstablished($fd)) {
                    if($request->fd !== $fd){
                       $server->push($fd,json_encode($selfInfo)); 
                    }
                }
            }
    }


    public function onOne($request,$id){
         $this->mysql->update('user',['fd_on_one'=>$request->fd,'connect_user_id'=>$request->get['sideId']],['user_id'=>$id]);
    }


    public function saveText($to_id,$from_id,$msg,$type){
        
    }


}


new Ws();




?>