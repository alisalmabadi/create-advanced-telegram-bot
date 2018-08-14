<?php
	define('API_KEY','**TOKEN**');
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
	//---------
	$update = json_decode(file_get_contents('php://input'));
	var_dump($update);
	//=========
	$chat_id = $update->message->chat->id;
	$message_id = $update->message->message_id;
	$from_id = $update->message->from->id;
	$name = $update->message->from->first_name;
	$contact = $update->message->contact;
	$cnumber = $update->message->contact->phone_number;
	$cname = $update->message->contact->first_name;
	
	$photo = $update->message->photo;
	$video = $update->message->video;
	$sticker = $update->message->sticker;
	$file = $update->message->document;
	$music = $update->message->audio;
	$voice = $update->message->voice;
	$forward = $update->message->forward_from;
	
	$username = $update->message->from->username;
	$textmessage = isset($update->message->text)?$update->message->text:'';
	$reply = $update->message->reply_to_message->forward_from->id;
	$stickerid = $update->message->reply_to_message->sticker->file_id;
	//------------
	$_sticker = file_get_contents("data/setting/sticker.txt");
	$_video = file_get_contents("data/setting/video.txt");
	$_voice = file_get_contents("data/setting/voice.txt");
	$_file = file_get_contents("data/setting/file.txt");
	$_photo = file_get_contents("data/setting/photo.txt");
	$_music = file_get_contents("data/setting/music.txt");
	$_forward = file_get_contents("data/setting/forward.txt");
	$_joingp = file_get_contents("data/setting/joingp.txt");
	//------------
	$admin = "**ADMIN**";
	$bottype = gold;
	$tb = No;
	$step = file_get_contents("data/".$from_id."/step.txt");
	$type = file_get_contents("data/".$from_id."/type.txt");
	$list = file_get_contents("data/blocklist.txt");
	//---Buttons----
	$btn1_name = file_get_contents("data/btn/btn1_name");
	$btn2_name = file_get_contents("data/btn/btn2_name");
	$btn3_name = file_get_contents("data/btn/btn3_name");
	$btn4_name = file_get_contents("data/btn/btn4_name");
	
	$btn1_post =  file_get_contents("data/btn/btn1_post");
	$btn2_post =  file_get_contents("data/btn/btn2_post");
	$btn3_post =  file_get_contents("data/btn/btn3_post");
	$btn4_post =  file_get_contents("data/btn/btn4_post");
	
	//-----------
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
	function save($filename,$TXTdata)
	{
	$myfile = fopen("data/".$filename, "w") or die("Unable to open file!");
	fwrite($myfile, "$TXTdata");
	fclose($myfile);
	}
	//===========
	if (strpos($list , "$from_id") !== false  ) {
		SendMessage($chat_id,"You Are Blocked!");
	}
	elseif(isset($update->callback_query)){
    $callbackMessage = 'آپدیت شد';
    var_dump(makereq('answerCallbackQuery',[
        'callback_query_id'=>$update->callback_query->id,
        'text'=>$callbackMessage
    ]));
    $chat_id = $update->callback_query->message->chat->id;
    $message_id = $update->callback_query->message->message_id;
    $data = $update->callback_query->data;
    //SendMessage($chat_id,"$data");
	
    if ($data == "sticker" && $_sticker == "✅") {
      save("setting/sticker.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>"⛔️",'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
    if ($data == "sticker" && $_sticker == "⛔️") {

	 save("setting/sticker.txt","✅");
	     var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>"✅",'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 
     if ($data == "video" && $_video == "✅") {
      save("setting/video.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>"⛔️",'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
     if ($data == "video" && $_video == "⛔️") {
   save("setting/video.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>"✅",'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 
    if ($data == "voice" && $_voice == "✅") {
      save("setting/voice.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>"⛔️",'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
    if ($data == "voice" && $_voice == "⛔️") {

	   save("setting/voice.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>"✅",'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
    if ($data == "file" && $_file == "✅") {
      save("setting/file.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>"⛔️",'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
    if ($data == "file" && $_file == "⛔️") {
	  save("setting/file.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>"✅",'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 
     if ($data == "photo" && $_photo == "✅") {
      save("setting/photo.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>"⛔️",'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
     if ($data == "photo" && $_photo == "⛔️") {
	 save("setting/photo.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>"✅",'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 
      if ($data == "music" && $_music == "✅") {
      save("setting/music.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>"⛔️",'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
      if ($data == "music" && $_music == "⛔️") {
	       save("setting/music.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>"✅",'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 
 
       if ($data == "forward" && $_forward == "✅") {
      save("setting/forward.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>"⛔️",'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
       if ($data == "forward" && $_forward == "⛔️") {

	 save("setting/forward.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>"✅",'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 
      if ($data == "joingp" && $_joingp == "✅") {
      save("setting/joingp.txt","⛔️");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>"⛔️",'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
      if ($data == "joingp" && $_joingp == "⛔️") {
	 save("setting/joingp.txt","✅");
    var_dump(
        makereq('editMessageText',[
            'chat_id'=>$chat_id,
            'message_id'=>$message_id,
            'text'=>"به تنظیمات روبات خوش آمدید.

 🚫 = قفل شده.
 ✅ = آزاد",
            'reply_markup'=>json_encode([
                'inline_keyboard'=>[
                    			[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>"✅",'callback_data'=>"joingp"]
					]
		
                ]
            ])
        ])
    );
 }
 //=========================
}
	
	elseif($textmessage == '')
	{
	//Check Kardan (Media)
	if ($contact  != null && $step== 'Set Contact' && $from_id == $admin) {
	save("profile/number.txt",$cnumber);
	save("profile/cname.txt",$cname);
	SendMessage($chat_id,"شماره ذخیره .
	*$cname *: `$cnumber`");
	}
	
	if ($photo != null) {
	if ($_photo == "⛔️") {
	SendMessage($chat_id,"Locked!");
	}
	else {
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		Forward($reply,$chat_id,$message_id); 
		
		}
	}
	}
	
	if ($sticker != null ) {
		if ($_sticker == "⛔️" && $from_id != $admin) {
	SendMessage($chat_id,"Locked!");
		}
	else {
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		Forward($reply,$chat_id,$message_id); 
		}
	}
	}
	
	if ($video != null) {
		if ($from_id != $admin && $_video == "⛔️") {
	SendMessage($chat_id,"Locked!");
		}
		else {
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		Forward($reply,$chat_id,$message_id); 
		}
	}
	}
	
	if ($music != null ) {
		if ($from_id != $admin && $_music == "⛔️") {
	SendMessage($chat_id,"Locked!");
	}
	else {
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		Forward($reply,$chat_id,$message_id); 
		}
	}
	}
	
	if ($voice != null) {
		if ($from_id != $admin && $_voice == "⛔️") {
	SendMessage($chat_id,"Locked!");
	}
	else {
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		Forward($reply,$chat_id,$message_id); 
		}
	}
	}
	
	if ($file != null ){
		if ($from_id != $admin && $_file == "⛔️") {
	SendMessage($chat_id,"Locked!");
		}
		
	}
	else {
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		Forward($reply,$chat_id,$message_id); 
		}
	}
	}
	elseif ($from_id != $chat_id) {
		
	SendMessage($chat_id,"من اجازه ورود به گروه را ندارم!
بای😐👋");
makereq('leaveChat',[
	'chat_id'=>$chat_id
	]);
	}
        
	elseif($textmessage == '🏠 برگشت به صفحه اصلی') {
	save($from_id."/step.txt","none");
	if ($type == "admin") {
	
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"🏠 به صفحه اصلی خوش آمدید :",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		}
    		else {
    		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"*Home : *",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"👤 پروفایل"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    	}
	}
	elseif ($step == 'set word') {
		save($from_id."/step.txt","set answer");
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"در جواب کلمه چه ارسال شود؟
مثال:
`سلام،خوبی؟`",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
				
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keybord'=>true
       		])
    		]));
			save("words/$textmessaage.txt","Tarif Nashode !");
			save("last_word.txt",$textmessage);
	}
	elseif ($step == 'set answer') {
		save($from_id."/step.txt","none");
		
		$last = file_get_contents("data/last_word.txt");
			$myfile2 = fopen("data/wordlist.txt", "a") or die("Unable to open file!");	
			fwrite($myfile2, "$last\n");
			fclose($myfile2);
			save("words/$last.txt","$textmessage");
		
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"`سیو شد.`
یک گزینه را انتخاب کنید :
			",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
				[
                   ['text'=>'اضافه کردن کلمه'],['text'=>'حذف کلمه']
                ],
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		
			
	}
	
	elseif($step == "del words") {
			unlink("data/words/$textmessage.txt");
			var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"`حذف شد.`
یک گزینه را انتخاب کنید :
			",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
				[
                   ['text'=>'اضافه کردن کلمه'],['text'=>'حذف کلمه']
                ],
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
			save($from_id."/step.txt","none");
	}
	
		elseif ($step== 'Forward' && $type == 'admin') {
			if ($forward != null) {
			$forward_id = file_get_contents("data/forward_id.txt");
			Forward($forward_id,$chat_id,$message_id);
			save($from_id."/step.txt","none");
			SendMessage($chat_id,"فروارد  شد !");
			}
			else {
				SendMessage($chat_id,"یک پیام را فروارد کنید !");
			}
		}
	elseif ($step== 'Set Age' && $type == 'admin') {
	
	save($from_id."/step.txt","none");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"◀️با موفقیت تغییر یافت.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                 [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		save("profile/age.txt","$textmessage");
	}
	
	elseif ($step== 'Set Name' && $type == 'admin') {
	save($from_id."/step.txt","none");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"◀️با موفقیت تغییر یافت.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                 [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		save("profile/name.txt","$textmessage");
	}
	
	elseif ($step== 'Set Bio' && $type == 'admin') {
	save($from_id."/step.txt","none");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"◀️با موفقیت تغییر یافت.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                 [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		save("profile/bio.txt","$textmessage");
	}
	elseif ($step== 'Send To All' && $type == 'admin') {
		SendMessage($chat_id,"`در حال ارسال پیام . . .`");
		save($from_id."/step.txt","none");
		$fp = fopen( "data/users.txt", 'r');
		while( !feof( $fp)) {
 			$users = fgets( $fp);
			SendMessage($users,$textmessage);
		}
		SendMessage($chat_id,"✅پیام شما به همه ی کاربران ربات ارسال گردید.");
		
	}
	elseif ($step== 'Edit Start Text' && $type == 'admin') {
		save($from_id."/step.txt","none");
		save("start_txt.txt",$textmessage);
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"◀️پیام استارت شما با موفقیت تغییر یافت.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                 [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($step== 'Edit Message Delivery' && $type == 'admin') {
		save($from_id."/step.txt","none");
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"◀️پیام پیشفرض شما با موفقیت تغییر یافت.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                 [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		save("pmsend_txt.txt",$textmessage);
	}
	
	elseif (file_exists("data/words/$textmessage.txt")) {
		SendMessage($chat_id,file_get_contents("data/words/$textmessage.txt"));
		
	}
	
	elseif ($textmessage == 'امکانات ویژه' && $from_id == $admin) {
		if ($bottype == 'gold') {
			var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"به بخش امکانات ویژه خوش امدید",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
				[
                   ['text'=>'🗣 تنظیمات پاسخ خودکار']
                ],
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
        }
		else {
			SendMessage($chat_id,"ربات شما رایگان است .");
		}
	}
	elseif ($textmessage == 'حذف کلمه' && $from_id == $admin) {
				save($from_id."/step.txt","del words");

		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"کلمه مورد نظر را برای حذف کردن وارد کنید :",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	elseif ($textmessage == '🗣 تنظیمات پاسخ خودکار' && $bottype == 'gold' && $from_id == $admin) {

		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"یکی از گزینه های زیر را انتحاب کنید :",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
				[
                   ['text'=>'اضافه کردن کلمه'],['text'=>'حذف کلمه']
                ],
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		
	}
	elseif ($textmessage == 'اضافه کردن کلمه' && $bottype == 'gold' && $from_id == $admin) {
				save($from_id."/step.txt","set word");
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"کلمه ای که میخواهید به آن پاسخ داده شود را ارسال نمایید
مثال :
`سلام`",
			'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
				
                 [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == '▶️ ویرایش پیام استارت' && $from_id == $admin) {
	$sttxt = file_get_contents("data/start_txt.txt");
	save($from_id."/step.txt","Edit Start Text");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"*تنظیم کردن متن استارت*
پیام استارت قبلی :
➖➖➖➖➖➖➖➖➖➖
`".$sttxt."`
➖➖➖➖➖➖➖➖➖➖
*لطفا* پیام استارت جدید را وارد کنید 😃",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == '⏸ ویرایش پیام پیشفرض' && $from_id == $admin) {
	$sttxt = file_get_contents("data/pmsend_txt.txt");
	save($from_id."/step.txt","Edit Message Delivery");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"*تنظیم کردن متن پیشفرض*
پیام پیشفرض قبلی :
➖➖➖➖➖➖➖➖➖➖
`".$sttxt."`
➖➖➖➖➖➖➖➖➖➖
*لطفا* پیام پیشفرض جدید را وارد کنید 😃",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == '⚙ تنظیمات' && $from_id == $admin) {
	
	var_dump(makereq('sendMessage',[
			'chat_id'=>$update->message->chat->id,
			'text'=>"به تنظیمات روبات خوش آمدید.
`
 🚫 = قفل شده.
 ✅ = آزاد"."`",
			'parse_mode'=>'MarkDown',
			'reply_markup'=>json_encode([
				'inline_keyboard'=>[
					[
						['text'=>"دسترسی استیکر",'callback_data'=>"sticker"],['text'=>$_sticker,'callback_data'=>"sticker"]
					],
					[
						['text'=>"دسترسی فیلم",'callback_data'=>"video"],['text'=>$_video,'callback_data'=>"video"]
					],
					[
						['text'=>"دسترسی ویس",'callback_data'=>"voice"],['text'=>$_voice,'callback_data'=>"voice"]
					],
					[
						['text'=>"دسترسی فایل",'callback_data'=>"file"],['text'=>$_file,'callback_data'=>"file"]
					],
					[
						['text'=>"دسترسی عکس",'callback_data'=>"photo"],['text'=>$_photo,'callback_data'=>"photo"]
					],
					[
						['text'=>"دسترسی آهنگ",'callback_data'=>"music"],['text'=>$_music,'callback_data'=>"music"]
					],
					[
						['text'=>"دسترسی فروارد",'callback_data'=>"forward"],['text'=>$_forward,'callback_data'=>"forward"]
					],
					[
						['text'=>"عضویت در گروه",'callback_data'=>"joingp"],['text'=>$_joingp,'callback_data'=>"joingp"]
					]
				]
			])
		]));
	
	}
	
	elseif ($textmessage == '👁 شماره ی من رو نشون بده' && $from_id == $admin) {
	$anumber = file_get_contents("data/profile/number.txt");
	$aname= file_get_contents("data/profile/cname.txt");
	makereq('sendContact',[
	'chat_id'=>$chat_id,
	'phone_number'=>$anumber,
	'first_name'=>$aname
	]);
	}
	elseif ($textmessage == 'سن' && $from_id == $admin) {
	save($from_id."/step.txt","Set Age");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"لطفا سن خود را وارد کنید!",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == 'نام' && $from_id == $admin) {
	save($from_id."/step.txt","Set Name");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"لطفا نام خو را وارد کنید!",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == 'درباره شما' && $from_id == $admin) {
	save($from_id."/step.txt","Set Bio");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"لطفا بیوگرافی خود را وارد کنید!",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == '☎️  تنظیمات کانتکت' && $from_id == $admin) {
	save($from_id."/step.txt","Set Contact");
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"یک گزینه را انتخاب کنید.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🌐 تنظیم شماره تلفن' , 'request_contact' => true]
                ],
              	[
                   ['text'=>'👁 شماره ی من رو نشون بده']
                ],
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	
	elseif ($textmessage == '👥 آمار' && $from_id == $admin) {
	$usercount = -1;
	$fp = fopen( "data/users.txt", 'r');
	while( !feof( $fp)) {
    		fgets( $fp);
    		$usercount ++;
	}
	fclose( $fp);
	SendMessage($chat_id,"👥 تعداد اعضای ربات : `".$usercount."`");
	}
	
	elseif ($textmessage == '⚫️ لیست سیاه' && $from_id == $admin) {
	$usercount = -1;
	$fp = fopen( "data/blocklist.txt", 'r');
	while( !feof( $fp)) {
    		$line = fgets( $fp);
    		$usercount ++;	
			
			if ($line == '') {
				$usercount = $usercount-1;
			}
	}
	fclose( $fp);
	SendMessage($chat_id,"⚫️ تعداد اعضای بلاک شده ربات : `".$usercount."`");
	}
	
	elseif ($textmessage == 'ارتقا ربات' && $from_id == $admin) {
	$text = "
ربات شما ویژه است🏆";
	SendMessage($chat_id,$text);
	}
	
	elseif ($textmessage == '⚓️ راهنما' && $from_id == $admin) {
	$text = "
	سلام

- این ربات جهت راحتی شما و پشتیبانی از ربات،کانال،گروه یا حتی وبسایت شما ساخته شده است

- نوشته شده به زبان *PHP*

- برنامه نویس ها : @JokerBlackCity

برای مشاهده ی دستورات از دکمه های زیر استفاده کنید 👇

Copy Right 2017 ©
	";
	
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>$text,
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🔰 دستورات"],['text'=>"🔰 دکمه ها"]
                ],
                [ 
                 ['text'=>"🏠 برگشت به صفحه اصلی"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
	}
	elseif ($textmessage == '👤 پروفایل') {
		if ($from_id == $admin) {
	var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"پروفایل خود را مدیریت کنید.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"نام"],['text'=>"سن"],['text'=>"درباره شما"]
                ],
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		}
		else {
			$name = file_get_contents("data/profile/name.txt");
			$age = file_get_contents("data/profile/age.txt");
			$bio = file_get_contents("data/profile/bio.txt");
			$protxt = "";
			if ($name == '' && $age == '' && $bio == '') {
				$protxt = "📭 پروفایل `خالی` است . . . !";
			}
			if ($name != '') {
				$protxt = $protxt."\n👤 نام : ".$name;
			}
			
			if ($age != '') {
				$protxt = $protxt."\n🎓 سن : ".$age;
			}
			
			if ($bio != '') {
				$protxt = $protxt."\nℹ️ درباره : ".$bio;
			}
			SendMessage($chat_id,$protxt);
		}
	}
	elseif ($textmessage == '🔰 دستورات' && $from_id == $admin) {
	$text = " 
	🔰دستورات

- برای پاسخ با پیام های کاربران روی ان ها ریپلای کنید و پیام خود را ارسال کنید.

+ لیست دستورات

  */ban :* 
 روی پیام رپلای کنید و  ban/ را ارسال کنید
 برای اضافه کردن کاربر به لیت سیاه


  */unban :* 
 روی پیام رپلای کنید و  unban/ را ارسال کنید
 برای پاک کردن کاربر از لیست سیاه

 
  */forward :* 
 روی پیام رپلای کنید و  forward/ را ارسال کنید
 جهت فروارد کردن پیام برای کاربر 
 ابتدا روی شخس ریپلای کنید و forward/ را ارسال کنید و بعد پیام مورد نظرتان را اینجا فروارد کنید


  */share :*  
 روی پیام رپلای کنید و  share/ را ارسال کنید
 برای شیر کردن کانتکت(شماره شما) [شما ابتدا باید از بخش تنظیمات کانتکت شماره ی خود را ثبت کنید]
	";
	SendMessage($chat_id,$text);
	}
	
	elseif ($textmessage == '🔰 دکمه ها' && $from_id == $admin) {
	$text = "
	🔰دکمه ها

+ Buttons List

  🗣 پیام همگانی :
  ارسال پیام به اعضا و گروه ها.

  ⚙ تنظیمات :
  تنظیمات ربات.

  ▶️ ویرایش پیام استارت :
  ویرایش پیام استارت ربات شما.

  ⏸ ویرایش پیام پیشفرض :
  ویرایش پیام پیشفرض ربات شما.

  👥 آمار :
  مشاهده ی تعداد اعضا و گروه ها.

  ⚫️ لیست سیاه :
  مشاهده ی لیست سیاه.

  ☎️  تنظیمات کانتکت :
  تنظیمات شماره ی شما.

  👤 پروفایل :
  تنظیمات پروفایل شما.
	";
	SendMessage($chat_id,$text);
	}
	
	elseif($textmessage == '/start')
	{
		$txt = file_get_contents("data/start_txt.txt");
		//==============
		if ($type == "admin") {
		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"به روبات خودتون خوش آومدین.",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"🗣 پیام همگانی"],['text'=>"⚓️ راهنما"],['text'=>"⚙ تنظیمات"]
                ],
                [
                   ['text'=>"▶️ ویرایش پیام استارت"],['text'=>"⏸ ویرایش پیام پیشفرض"]
                ],
                [
                   ['text'=>"👥 آمار"],['text'=>"ارتقا ربات"],['text'=>"⚫️ لیست سیاه"]
                ],
                [
                   ['text'=>"☎️  تنظیمات کانتکت"],['text'=>"👤 پروفایل"],['text'=>"امکانات ویژه"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		}
    		else {
    		if ($tb != "No") {

    		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>$txt."\n\n*Create Your Bot *@CrPvRoBot",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"👤 پروفایل"]
				],
                [
				   ['text'=>"🚀 ارسال شماره شما",'request_contact' => true],['text'=>"⚓️ ارسال مکان شما",'request_location' => true]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		}
    		else {
    		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>$txt,
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>"👤 پروفایل"]
				],
                [
				   ['text'=>"🚀 ارسال شماره شما",'request_contact' => true],['text'=>"⚓️ ارسال مکان شما",'request_location' => true]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		}
    		}
		//==============
		$users = file_get_contents("data/users.txt");
		if (strpos($users , "$chat_id") !== false)
		{ 
		
		}
		else { 
			$myfile2 = fopen("data/users.txt", "a") or die("Unable to open file!");	
			fwrite($myfile2, "$from_id\n");
			fclose($myfile2);
			mkdir("data/".$from_id);
			save($from_id."/type.txt","member");
			save($from_id."/step.txt","none");
		     }
	}
	elseif ($reply != null && $from_id == $admin) {
		if ($textmessage == '/share') {
		$anumber = file_get_contents("data/profile/number.txt");
		$aname= file_get_contents("data/profile/cname.txt");
		makereq('sendContact',[
		'chat_id'=>$reply,
		'phone_number'=>$anumber,
		'first_name'=>$aname
		]);
		SendMessage($chat_id,"ارسال شد .");
		}
		elseif ($textmessage == '/forward') {
		SendMessage($chat_id,"پیام خود را فروارد کنید !");	
		save($from_id."/step.txt","Forward");
		save("forward_id.txt","$reply");
		}
		elseif ($textmessage == '/ban') {
			$myfile2 = fopen("data/blocklist.txt", "a") or die("Unable to open file!");	
			fwrite($myfile2, "$reply\n");
			fclose($myfile2);
			SendMessage($chat_id,"*User Banned!*");
			SendMessage($reply,"*You Are Banned!*");
		}
		elseif ($textmessage == '/unban') {
			
			$newlist = str_replace($reply,"",$list);
			save("blocklist.txt",$newlist);
			SendMessage($chat_id,"*User UnBanned!*");
			SendMessage($reply,"*You Are UnBanned!*");
		}
		else {
	SendMessage($reply ,$textmessage);
	SendMessage($chat_id,"پیام ارسال شد .");	
		}
	}
	
	elseif ($textmessage == '/creator' && $bottype != "gold") {
    		var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"ساخته شده توسط @CrPhpBot",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'inline_keyboard'=>[
                [
                   ['text'=>'Create Your Bot','url'=>"http://t.me/CrPhpBot"]
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
    		
	}
	elseif ($forward != null && $_forward == "⛔️") {
		SendMessage($chat_id,"Locked!");
	}
	elseif (strpos($textmessage , "/toall") !== false  || $textmessage == "🗣 پیام همگانی") {
		if ($from_id == $admin) {
			save($from_id."/step.txt","Send To All");
				var_dump(makereq('sendMessage',[
        	'chat_id'=>$update->message->chat->id,
        	'text'=>"پیام خود را ارسال کنید!",
		'parse_mode'=>'MarkDown',
        	'reply_markup'=>json_encode([
            	'keyboard'=>[
                [
                   ['text'=>'🏠 برگشت به صفحه اصلی']
                ]
            	],
            	'resize_keyboard'=>true
       		])
    		]));
		}
		else {
			SendMessage($chat_id,"شما ادمین نیستید !");
		}
	}
	else
	{
		if ($from_id != $admin) {
		$txt = file_get_contents("data/pmsend_txt.txt");
		SendMessage($chat_id,$txt);
		Forward($admin,$chat_id,$message_id); 
		}
		else {
		SendMessage($chat_id,"🔸یافت نشد !");
		}
	}
	
	
	?>