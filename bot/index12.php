<?php
define('API_KEY','**TOKEN**');
$admin = **ADMIN**;
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
$update = json_decode(file_get_contents('php://input'));
$chat_id = $update->message->chat->id;
$text = $update->message->text;
$from = $update->message->from->id;
$step = file_get_contents('step.txt');
  if(preg_match('/^([Hh]ttp|[Hh]ttps)(.*)/',$text)){
    $short = file_get_contents('http://yeo.ir/api.php?url='.$text);
    roonx('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"لینک شما کوتاه شد:\n".$short."\n ",
      'parse_mode'=>'HTML'
    ]);
  }
  if(preg_match('/^\/([sS]tart)/',$text) and $from == $admin){
	  roonx('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"😎 سلام !
🔸لطفا لینک خود را برای کوتاه کردن ارسال کنید.

📢 مثال :
<code>http://google.com</code>
برای دیدن آمار /stats بزنید.(فقط ادمین)",
      'parse_mode'=>'HTML'
    ]);
  }
if(preg_match('/^\/([Ss]tats)/',$text) and $from == $admin){
    $user = file_get_contents('users.txt');
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
    roonx('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"<b>تعداد کل اعضا</b> : <b>$member_count</b>",
      'parse_mode'=>'HTML'
    ]);
}
$user = file_get_contents('users.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('users.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('users.txt',$add_user);
    }
	?>