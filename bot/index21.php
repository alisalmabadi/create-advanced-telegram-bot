<?php
ob_start();
//@Roonx_Team
define('API_KEY','**TOKEN**');
$update = json_decode(file_get_contents('php://input'));
$from_id = $update->message->from->id;
$chat_id = $update->message->chat->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$text = $update->message->text;
$message_id = $update->callback_query->message->message_id;
$message_id_feed = $update->message->message_id;
$fal = file_get_contents("https://apio.a7n.ir/falhafez/index.php");
function roonx($method,$datas=[]){
    $url = "https://api.telegram.org/bot".API_KEY."/".$method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
    $res = curl_exec($ch);
    if(curl_error($ch)){
        var_dump(curl_error($ch));
    }else{
        return json_decode($res);
    }
}
if(preg_match('/^\/([Ss]tart)/',$text)){
roonx('sendMessage',[
    'chat_id'=>$chat_id,
    'text'=>"سلام به ربات فال حافظ خوش آمدی(:
    اول نیت کن بعد روی دکمه بزن
    ساخته شده توسط @CreateAllBot",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
      'inline_keyboard'=>[
          [
     ['text'=>'نیت کردم','callback_data'=>'fal']
          ]
        ]
		])
  ]);
}
if(preg_match('/^\/([Cc]reator)/',$text)){
roonx('sendMessage',[
    'chat_id'=>$chat_id,
    'text'=>"ساخته شده توسط @CreateAllBot",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
      'inline_keyboard'=>[
          [
     ['text'=>"ربات بسازید🤖",'url'=>"https://telegram.me/CreateAllBot"]
          ]
        ]
		])
  ]);
}
elseif ($data == "fal") {
  roonx('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>$fal,
    'parse_mode'=>'html',
    'reply_markup'=>json_encode([
      'inline_keyboard'=>[
        [
          ['text'=>'دوباره','callback_data'=>'fal']
        ],
	  [
		['text'=>'برگردیم اول ◀','callback_data'=>'menu']
      ]
      ]
    ])
  ]);
 }
elseif ($data == "menu") {
  roonx('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"سلام به ربات فال حافظ خوش آمدی(:
اول نیت کن بعد روی دکمه بزن
ساخته شده توسط @CreateAllBot",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
      'inline_keyboard'=>[
          [
     ['text'=>'نیت کردم','callback_data'=>'fal']
          ]
      ]
    ])
  ]);
 }
//@Roonx_Team
	?>