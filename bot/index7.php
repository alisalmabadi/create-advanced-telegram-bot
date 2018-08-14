<?php
ob_start();
define('API_KEY','[*BOTTOKEN*]');
function salavat($method,$datas=[]){
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
$update = json_decode(file_get_contents('php://input'));
if(isset($update->callback_query)){
    $callbackMessage = 'بروزرسانی شد';
    var_dump(salavat('answerCallbackQuery',[
        'callback_query_id'=>$update->callback_query->id,
        'text'=>$callbackMessage
    ]));
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
    $add = $update->callback_query->data+1;
    $rem = $update->callback_query->data-1;
    var_dump(
        salavat('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>($add)." : Tedad Salavat haye shoma\n",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    [
                        ['text'=>"شمارش کن",'callback_data'=>"$add"]
                    ],
                    [
                        ['text'=>"دستم خورد یکی پاک کن",'callback_data'=>"$rem"]
                    ]
                ]
            ])
        ])
    );
}else{
    var_dump(salavat('sendMessage',[
        'chat_id'=>$update->message->chat->id,
        'text'=>"تعداد صلوات های فعلی شما صفر میباشد",
        'reply_markup'=>json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>"شروع شمارش",'callback_data'=>'-1']
                ]
            ]
        ])
    ]));
}
file_put_contents('log',ob_get_clean());