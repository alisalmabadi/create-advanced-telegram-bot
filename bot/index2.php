<?php
define('API_KEY','[*BOTTOKEN*]');
//----######------
function makereq($method,$datas=[]){
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
//##############=--API_REQ
function apiRequest($method, $parameters) {
  if (!is_string($method)) {
    error_log("Method name must be a string\n");
    return false;
  }
  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    error_log("Parameters must be an array\n");
    return false;
  }
  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = "https://api.telegram.org/bot".API_KEY."/".$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
//----######------
//---------
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
//=========
$chat_id = $update->message->chat->id;
$message_id = $update->message->message_id;
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$lastname = $update->message->from->last_name;
$username = $update->message->from->username;
$forward_from = $update->message->forward_from;
$from_first_name = $forward_from->first_name;
$from_last_name = $forward_from->last_name;
$from_username = $forward_from->username;
$fro_id = $forward_from->id;
$textmessage = isset($update->message->text)?$update->message->text:'';
$reply = $update->message->reply_to_message->forward_from->id;
$stickerid = $update->message->reply_to_message->sticker->file_id;
$chanell = '@CreateBotCh';
//-------
function SendMessage($ChatId, $TextMsg)
{
 makereq('sendMessage',[
'chat_id'=>$ChatId,
'text'=>$TextMsg,
'parse_mode'=>"MarkDown"
]);
}
function SendSticker($ChatId, $sticker_ID)
{
 makereq('sendSticker',[
'chat_id'=>$ChatId,
'sticker'=>$sticker_ID
]);
}
function Forward($KojaShe,$AzKoja,$KodomMSG)
{
makereq('ForwardMessage',[
'chat_id'=>$KojaShe,
'from_chat_id'=>$AzKoja,
'message_id'=>$KodomMSG
]);
}
//===========
 if($textmessage == '/start')
{
SendMessage($chat_id,"*Firstname* : `$name`\n*Lastname* : `$lastname`\n*Username* : `$username`\n*Id* : `$chat_id`");
}
elseif ($textmessage == '/Creator')
{
SendMessage($chat_id,"ساخته شده توسط @CreateAllBot 🎨");
Forward($chat_id,$chanell,4);
}
elseif ($textmessage == '/creator')
{
SendMessage($chat_id,"ساخته شده توسط @CreateAllBot 🎨");
Forward($chat_id,$chanell,4);
}
else
{
SendMessage($chat_id,"*Firstname* : `$from_first_name`\n*Lastname* : `$from_last_name`\n*Username* : `$from_username`\n*Id* : `$fro_id`");
}
?>