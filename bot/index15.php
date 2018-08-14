<?php
define('API_KEY','**TOKEN**');
function onyx($method,$datas=[]){
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
function rp($Number){
$Rand = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $Number); 
 return $Rand; 
}
$update = json_decode(file_get_contents('php://input'));
$text = $update->message->text;
$chat_id = $update->message->chat->id;
$inlineqt = $update->inline_query->query;
$inlineqid = $update->inline_query->id;
if($text == "/start"){
    onyx('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"سلام دوست من
        
        دستورات ربات :
        <code>
        /code [متن]
        /bold [متن]
        /italic [متن]
        </code>
        فرمت های متن :
        <code>
        [متن](لینک)
        
        *متن بلد*
        
        _متن ایتالیک_
        
        ```متن کد```
        </code>
        
        فرمت های اینلاین
        <code>
        کارکتر شمار , متن بلد, متن ایتالیک, متن کد, ساخت بارکد:
        @userbot [متن]
        
        ساخت پسورد رندم :
        @userbot [عدد]
        </code>",
        'parse_mode'=>"HTML",
        'reply_markup'=>json_encode(['inline_keyboard'=>[
            [['text'=>'Switch Inline','switch_inline_query'=>'']]
        ]])
    ]);
}elseif (preg_match('/^\/([Bb]old)/',$text)){
    $strbold = str_replace("/bold","",$text);
    onyx('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"<b>".$strbold."</b>",
        'parse_mode'=>"HTML"
    ]);
}elseif (preg_match('/^\/([Ii]talic)/',$text)){
    $stritalic = str_replace("/italic","",$text);
    onyx('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"<i>".$stritalic."</i>",
        'parse_mode'=>"HTML"
    ]);
}elseif (preg_match('/^\/([Cc]ode)/',$text)){
    $strcode = str_replace("/code","",$text);
    onyx('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>"<code>".$strcode."</code>",
        'parse_mode'=>"HTML"
    ]);
}else{
    onyx('sendMessage',[
        'chat_id'=>$chat_id,
        'text'=>$text,
        'parse_mode'=>"Markdown"
    ]);
}
$strlen = mb_strlen($inlineqt, 'utf8');
if($inlineqt == ""){
    onyx('answerInlineQuery',[
        'inline_query_id'=>$update->inline_query->id,
        'switch_pm_parameter'=>'',
        'switch_pm_text'=>"راهنما"
    ]);
}elseif(is_numeric($inlineqt) == "true"){
  onyx('answerInlineQuery',[
        'inline_query_id'=>$update->inline_query->id,
        'switch_pm_parameter'=>'',
        'switch_pm_text'=>"راهنما",
        'results'=>json_encode([[
            'type'=>'article',
            'id'=>base64_encode(rand(5,555)),
            'title'=>'Random Pass',
            'input_message_content'=>['parse_mode'=>'HTML','message_text'=>"پسورد شما :
            ".rp($inlineqt)],
            'reply_markup'=>['inline_keyboard'=>[
                [['text'=>'پسورد ساز','url'=>'https://telegram.me/shsbw828nnkooss']],
                [['text'=>'Switch Inline','switch_inline_query'=>'12']]
             ]]
        ]])
    ]);
}else{ 
onyx('answerInlineQuery',[
        'inline_query_id'=>$update->inline_query->id,    
        'switch_pm_parameter'=>'',
        'switch_pm_text'=>"راهنما",
        'results'=>json_encode([[
            'type'=>'article',
            'id'=>base64_encode(rand(5,555)),
            'title'=>'Charcter  📝',
            'input_message_content'=>['parse_mode'=>'HTML','message_text'=>"تعداد کارکتر 📝:
            $strlen"],
            'reply_markup'=>['inline_keyboard'=>[
                [['text'=>'کارکتر شمار','url'=>'https://telegram.me/2y27w7hsidiskosss']],
                [['text'=>'Switch Inline','switch_inline_query'=>'Message']]
             ]]
        ],[
            'type'=>'article',
            'id'=>base64_encode(rand(5,555)),
            'title'=>'Bold',
            'input_message_content'=>['parse_mode'=>'HTML','message_text'=>"<b>$inlineqt</b>"]
        ],[
            'type'=>'article',
            'id'=>base64_encode(rand(5,555)),
            'title'=>'Italic',
            'input_message_content'=>['parse_mode'=>'HTML','message_text'=>"<i>$inlineqt</i>"]
        ],[
            'type'=>'article',
            'id'=>base64_encode(rand(5,555)),
            'title'=>'Code',
            'input_message_content'=>['parse_mode'=>'HTML','message_text'=>"<code>$inlineqt</code>"]
        ],[
            'type'=>'photo',
            'id'=>base64_encode(rand(5,555)),
            'title'=>'QR Code',
            'photo_url'=>"https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=$inlineqt&format=jpg",
            'thumb_url'=>"https://api.qrserver.com/v1/create-qr-code/?size=400x400&data=$inlineqt&format=jpg",
            'description'=>"$inlineqt",
            'caption'=>"
            متن : $inlineqt",
            'reply_markup'=>['inline_keyboard'=>[
                [['text'=>'بارکد ساز','url'=>'https://telegram.me/hwjsb282h2isnw8sbskoss']],
                [['text'=>'Switch Inline','switch_inline_query'=>'ْMessage']]
             ]]
        ]])
    ]);
}