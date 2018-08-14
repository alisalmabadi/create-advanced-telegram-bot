<?php
ob_start();
define('API_KEY','*TOKEN*');
$admin = *ADMIN*;
$update = json_decode(file_get_contents('php://input'));
$from_id = $update->message->from->id;
$name = $update->message->from->first_name;
$chat_id = $update->message->chat->id;
$chatid = $update->callback_query->message->chat->id;
$data = $update->callback_query->data;
$text = $update->message->text;
$message_id = $update->callback_query->message->message_id;
$message_id_feed = $update->message->message_id;
$time = file_get_contents("http://api.mgataplus.tk/Time");
$date = file_get_contents("http://api.mgataplus.tk/Date");
$jock = file_get_contents("http://api.mgataplus.tk/Jock");
$hadis = file_get_contents("http://Sherimusic.ir/hadis.php");
$fal = file_get_contents("https://apio.a7n.ir/falhafez");
$pass = file_get_contents("https://goldtm.teleagent.ir/passrandom");
$am = file_get_contents("http://Sherimusic.ir/midanid.php");
$al = file_get_contents("al.txt");
function coding($method,$datas=[]){
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
coding('sendMessage',[
    'chat_id'=>$chat_id,
    'text'=>"😅 سلام $name ! به ربات تفریحی همه کاره خوش آمدید.",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
      'inline_keyboard'=>[
	        [
	        ['text'=>'😂 جوک','callback_data'=>'jock'],['text'=>'🗒 فال حافظ','callback_data'=>'fal']
                ],
		[
		['text'=>'📜 حدیث','callback_data'=>'hadis'],['text'=>'❓آیا میدانید؟','callback_data'=>'am']
                ],
		[
		['text'=>'🎈 ساعت و تاریخ','callback_data'=>'td'],['text'=>'🎐 پسورد رندوم','callback_data'=>'pass']
                ],
		[
		['text'=>'ℹ️ راهنما','callback_data'=>'help'],['text'=>'👥 آمار ربات','callback_data'=>'amar']
		]
		 ]
		])
  ]);
}
elseif ($data == "menu") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"🌹😜 به منوی اصلی خوش آمدید ! یکی از دکمه های زیر را انتخاب کید.",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	        ['text'=>'😂 جوک','callback_data'=>'jock'],['text'=>'🗒 فال حافظ','callback_data'=>'fal']
                ],
		[
		['text'=>'📜 حدیث','callback_data'=>'hadis'],['text'=>'❓آیا میدانید؟','callback_data'=>'am']
                ],
		[
		['text'=>'🎈 ساعت و تاریخ','callback_data'=>'td'],['text'=>'🎐 پسورد رندوم','callback_data'=>'pass']
                ],
		[
		['text'=>'ℹ️ راهنما','callback_data'=>'help'],['text'=>'👥 آمار ربات','callback_data'=>'amar']
		]
		 ]
		])
  ]);
}
elseif ($data == "td") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"هم اکنون :
⏰ ساعت : $time
📆 تاریخ : $date",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 بروزرسانی 🔄','callback_data'=>'td']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "jock") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"$jock",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 دوباره 🔄','callback_data'=>'jock']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "hadis") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"$hadis",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 دوباره 🔄','callback_data'=>'hadis']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "am") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"$am",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 دوباره 🔄','callback_data'=>'am']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "pass") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"📶 پسورد شما : <code>$pass</code>",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 دوباره 🔄','callback_data'=>'pass']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "fal") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"$fal",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 دوباره 🔄','callback_data'=>'fal']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "amar") {
 $user = file_get_contents('members.txt');
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"👥تعداد کاربران :
<code>$member_count</code>",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
	 ['text'=>'🔄 بروزرسانی 🔄','callback_data'=>'amar']
         ],
		[
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif ($data == "help") {
  coding('editMessagetext',[
    'chat_id'=>$chatid,
	'message_id'=>$message_id,
    'text'=>"ℹ️ راهنما :

😂 جوک
⬅️ دریافت جوک

🗒 فال حافظ
⬅️ دریافت فال

📜 حدیث
⬅️ دریافت حدیث

❓آیا میدانید؟
⬅️ دریافت آیا میدانید

🎈 ساعت و تاریخ
⬅️ دریافت ساعت و تاریخ

🎐 پسورد رندوم
⬅️ دریافت پسورد رندوم

👥 آمار ربات
⬅️ دریافت آمار ربات",
    'parse_mode'=>'html',
   'reply_markup'=>json_encode([
     'inline_keyboard'=>[
	 [
		['text'=>'🔙 برگشت به منو اصلی','callback_data'=>'menu']
		]
		 ]
		])
  ]);
}
elseif(preg_match('/^\/([Ss]tats)/',$text) and $from_id == $admin){
    $user = file_get_contents('members.txt');
    $member_id = explode("\n",$user);
    $member_count = count($member_id) -1;
    coding('sendMessage',[
      'chat_id'=>$chat_id,
      'text'=>"تعداد کل اعضا: $member_count",
      'parse_mode'=>'html'
  ]);
}unlink("error_log");
$user = file_get_contents('members.txt');
    $members = explode("\n",$user);
    if (!in_array($chat_id,$members)){
      $add_user = file_get_contents('members.txt');
      $add_user .= $chat_id."\n";
     file_put_contents('members.txt',$add_user);
    }
	?>