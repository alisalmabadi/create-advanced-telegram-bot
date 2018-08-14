<?php

ob_start();

define('[*BOTTOKEN*]');

function makeHTTPRequest($method,$datas=[]){

$url = "https://api.telegram.org/bot".API_KEY."/".$method;

$ch = curl_init();

curl_setopt($ch,CURLOPT_URL,$url);

curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($datas));

$res = curl_exec($ch);

if(curl_error($ch)){

var_dump(curl_error($ch));

}else{

return json_decode($res);

}

}

// Fetching UPDATE

$update = json_decode(file_get_contents('php://input'));

if(isset($update->callback_query)){

$callbackMessage = 'زمان بروز آوری شد';

var_dump(makeHTTPRequest('answerCallbackQuery',[

'callback_query_id'=>$update->callback_query->id,

'text'=>$callbackMessage

]));

$chat_id = $update->callback_query->message->chat->id;

$message_id = $update->callback_query->message->message_id;

$tried = $update->callback_query->data+1;

var_dump(

makeHTTPRequest('editMessageText',[

'chat_id'=>$chat_id,

'message_id'=>$message_id,

'text'=>($tried)." بار تا کنون زمان را بروز کردید ✅ \n 🔱 هم اکنون : \n".date('d M y - h:i:s'),

'reply_markup'=>json_encode([

'inline_keyboard'=>[

[

['text'=>"🌀 بروز آوری 🌀",'callback_data'=>"$tried"]

]

]

])

])

);

}else{

var_dump(makeHTTPRequest('sendMessage',[

'chat_id'=>$update->message->chat->id,

'text'=>" ربات نمایش زمان , @robot_besaz \n 🔱 هم اکنون : \n ".date('d M y - h:i:s'),

'reply_markup'=>json_encode([

'inline_keyboard'=>[

[

['text'=>"🌀 بروز آوری 🌀",'callback_data'=>'1']

]

]

])

]));

}

file_put_contents('log',ob_get_clean());