<?php
define('API_KEY','**TOKEN**');

function bot($method,$datas=[]){
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

    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = 'https://api.telegram.org/bot'.API_KEY.'/'.$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
$json = file_get_contents('php://input');
$update = json_decode(file_get_contents('php://input'));
var_dump($update);
$chat_id = $update->message->chat->id;
$user_id = $update->message->from->id;
$user_first_name = $update->message->from->first_name;
$user_last_name = $update->message->from->last_name;
$username = $update->message->from->username;
$msg_id = $update->message->message_id;
$msg_text = isset($update->message->text)?$update->message->text:'';
$text_msg = $update->message->text;
$data = $update->callback_query->data;
$inline_query = $update->inline_query;
$query_id = $inline_query->id;
$query = $inline_query->query;
$callback_id = $update->callback_query->id;
$callback_data = $update->callback_query->data;
function sendAction($chat_id, $action)
{bot('sendChataction',['chat_id'=>$chat_id,'action'=>$action]);}
function sendMessage($chat_id, $msg_text, $parse_mode, $message_id)
{bot('sendMessage',['chat_id'=>$chat_id,'text'=>$msg_text,'reply_to_message_id'=>$message_id,'parse_mode'=>$parse_mode]);}
function forward($chat_id, $from_chat_id, $message_id)
{bot('ForwardMessage',['chat_id'=>$chat_id,'from_chat_id'=>$from_chat_id,'message_id'=>$message_id]);}
function save($file_name,$txt_data)
{$myfile = fopen($file_name, "w") or die("Unable to open file!"); fwrite($myfile, "$txt_data"); fclose($myfile);}


bot('answerInlineQuery',[
  'inline_query_id'=>$query_id,
  'results'=>json_encode([[
  'thumb_url'=>'https://tlgur.com/d/ZgmYjYyg',
  'type'=>'article',
  'id'=>base64_encode(1),
  'title'=>'‌ ‍',
  'description'=>'❓❓❓❓',
  'input_message_content'=>['message_text'=>'❓❓❓❓','parse_mode'=>'markdown'],
  'reply_markup'=>[
  'inline_keyboard'=>[
  [['text'=>'خواندن/Read','callback_data'=>$query]]
  ]]]])]);

  bot('answerCallbackQuery',[
  'callback_query_id'=>$callback_id,
  'text'=>$callback_data,
  'show_alert'=>true
  ]);
  
  if ($msg_text == '/start')
  {
   bot('sendMessage',[
   'chat_id'=>$chat_id,
   'text'=>"سلام من ربات مخفی ساز متن هستم🔍\nدکمه زیر رو بزن و به جای متن متنت رو جایگزین کن.😃",
   'parse_mode'=>'HTML',
   'reply_markup'=>json_encode(['inline_keyboard'=>[
   [['text'=>'برو به حالت اینلاین','switch_inline_query'=>'متن']]
   
   ]])
   ]);
  }

?>