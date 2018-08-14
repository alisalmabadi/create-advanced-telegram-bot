<?php
define('BOT_TOKEN', '**TOKEN**');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
class curl
{
    private $curl_obj;
    public function __construct()
    {
        if(!function_exists('curl_init'))
        {
            echo 'ERROR: Install CURL module for php';
            exit();
        }
        $this->init();
    }
    public function init()
    {
        $this->curl_obj = curl_init();
    }
    public function request($url, $method = 'GET', $params = array(), $opts = array())
    {
        $method = trim(strtoupper($method));
        // default opts
        $opts[CURLOPT_FOLLOWLOCATION] = true;
        $opts[CURLOPT_RETURNTRANSFER] = 1;
        $opts[CURLOPT_SSL_VERIFYPEER] = true;
        $opts[CURLOPT_CAINFO] = "cacert.pem";
        if($method==='GET')
	{
		$url .= "?".$params;
		$params = http_build_query($params);
	}
        elseif($method==='POST')
        {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
        }
        $opts[CURLOPT_URL] = $url;
	curl_setopt_array($this->curl_obj, $opts);
        $content = curl_exec($this->curl_obj);
        if ($content===false) echo 'Ошибка curl: ' . curl_error($this->curl_obj);
        return $content;
    }
    public function close()
    {
        if(gettype($this->curl_obj) === 'resource')
            curl_close($this->curl_obj);
    }
    public function __destruct()
    {
        $this->close();
    }
}
function collect_file($fileurl){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $fileurl);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.xcontest.org");
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$result = curl_exec($ch);
		curl_close($ch);
		return($result);
}
function write_to_file($text,$new_filename){
		$fp = fopen($new_filename, 'w');
		fwrite($fp, $text);
		fclose($fp);
}
function sendPhoto($fileurl,$c,$chat_id)
{
		$method = 'sendPhoto';
		$filename = "images/".uniqid().".png";
		$temp_file_contents = collect_file($fileurl);
		write_to_file($temp_file_contents,$filename);
		if(class_exists('CURLFile')) $cfile = new CURLFile($filename);
		else $cfile = "@".$filename;
		$params = array
		(
			'chat_id' => $chat_id,
			'photo' => $cfile,
			'reply_to_message_id' => null,
			'reply_markup' => null
		);
		$r = $c->request(API_URL.$method, 'POST', $params);
		$j = json_decode($r, true);
		if($j) print_r($j);
		else echo $r;
		unlink($filename);
}
function sendLocalPhoto($filename,$c,$chat_id)
{
		$method = 'sendPhoto';
		/*$filename = "images/".uniqid().".png";
		$temp_file_contents = collect_file($fileurl);
		write_to_file($temp_file_contents,$filename);*/
		if(class_exists('CURLFile')) $cfile = new CURLFile($filename);
		else $cfile = "@".$filename;
		$params = array
		(
			'chat_id' => $chat_id,
			'photo' => $cfile,
			'reply_to_message_id' => null,
			'reply_markup' => null
		);
		$r = $c->request(API_URL.$method, 'POST', $params);
		$j = json_decode($r, true);
		if($j) print_r($j);
		else echo $r;
		//unlink($filename);
}
//VkParser
function parseUrl($url,$usePrefix=true)
{
	if ($usePrefix)
	{
			$pos = strpos($url, 'vk.com/');
			if ($pos===false) return false;
	}
	$pos = strpos($url, 'wall');
	if ($pos===false) return false;
	else return	substr($url,$pos+4-strlen($url));
}
//HELLOBOT
function apiRequestWebhook($method, $parameters) {
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
  $parameters["method"] = $method;
  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}
function exec_curl_request($handle) {
  curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true);
  curl_setopt($handle, CURLOPT_CAINFO, "cacert.pem");
  $response = curl_exec($handle);
  //$content = curl_exec($this->curl_obj);
        //if ($content===false) echo 'Ошибка curl: ' . curl_error($this->curl_obj);
  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    //file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id=106129214&text='.curl_error($this->curl_obj));
    echo 'Ошибка curl: ' . curl_error($this->curl_obj);
    error_log("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }
  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);
  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(10);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      error_log("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }
  return $response;
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
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = API_URL.$method.'?'.http_build_query($parameters);
  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  return exec_curl_request($handle);
}
function apiRequestJson($method, $parameters) {
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
  $parameters["method"] = $method;
  $handle = curl_init(API_URL);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
  return exec_curl_request($handle);
}
function processMessage($message) {
  // process incoming message
  $message_id = $message['message_id'];
  $chat_id = $message['chat']['id'];
  if (isset($message['text'])) {
    // incoming text message
    $text = $message['text'];
    if (strpos($text, "/start") === 0) {
      apiRequestJson("sendMessage", array('chat_id' => $chat_id, "text" => 'Hello', 'reply_markup' => array(
        'keyboard' => array(array('Hello', 'Hi')),
        'one_time_keyboard' => true,
        'resize_keyboard' => true)));
    } else if ($text === "Hello" || $text === "Hi") {
      apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'Nice to meet you'));
    } else if (strpos($text, "/stop") === 0) {
      // stop now
    } else {
      apiRequestWebhook("sendMessage", array('chat_id' => $chat_id, "reply_to_message_id" => $message_id, "text" => 'Cool'));
    }
  } else {
    apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => 'I understand only text messages'));
  }
}
function addInlineResult($resultId,$title,$message_text)
{
	global $results;
	$input_message_content=array("message_text"=>$message_text);
	$results[]=array(
            "type" => "article",
            "id" => $resultId,
            "title" => $title,
            //"message_text" => $message_text
            "input_message_content"=>$input_message_content
          );
    return $results;
}
//CALCUBOT
function prepareStringForReturn($value)
{
	$value	= str_replace('+','%2B',$value);
	$value	= str_replace(' ','%20',$value);
	return $value;
}
function strExists($value, $string)
{
    foreach ((array) $value as $v) {
        if (false !== strpos($string, $v)) return true;
    }
}
//START
$content = file_get_contents("php://input");
$update = json_decode($content, true);
if (!$update) exit;
//PERSONAL OR GROUP MESSAGE
if (isset($update["message"]))
{
		//processMessage($update["message"]);
		$chat	= $update["message"]['chat']['id'];
		$user	= $update["message"]['from']['id'];
		//$c = new curl();
		$message	= $update["message"]['text'];
		$message = strtolower($message);
if (substr($message, 0, 3)=="/--")
{
	$currentTime	= time()-3600;
	$pos = strpos($message, "+");
	$yy		= ($pos<17)?date('y',$currentTime):substr($message, 15, 2);
	$mmmm	= ($pos<14)?date('m',$currentTime):substr($message, 12, 2);
	$dd		= ($pos<11)?date('d',$currentTime):substr($message, 9, 2);
	$hh		= ($pos<8)?date('H',$currentTime):substr($message, 6, 2);
	$mm		= ($pos<5)?date('i',$currentTime):substr($message, 3, 2);
	$utcUser= mktime(0+$hh, 0+$mm, 0, 0+$mmmm, 0+$dd, 2000+$yy);
	$utc	= 3600+$utcUser;
	$AnswerText	= "/alert ".$utc.' '.substr($message, $pos+1);
	file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.'&text='.$AnswerText);
}
if ($message=='/start@calcubot'||$message=='/start')
	{
	$AnswerText	= "سلام به ربات ماشین حساب خوش آمدید محاسبات خود را ارسال کنید تا ربات حل کند.";
	$AnswerText	= str_replace('###','%0a',$AnswerText);
	file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.'&text='.$AnswerText);
	}
$crop=0;
if (substr($message,0,3)=='/cl') $crop=3;
if (substr($message,0,12)=='/cl@calcubot') $crop=12;
$source = substr($message,$crop);
//file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.'&text=source:'.$crop);
$source	= str_replace(' ','',$source);
if (!strExists("round(", $source)&&!strExists("rand(", $source)&&!strExists("max(", $source)&&!strExists("min(", $source)&&!strExists("hypot(", $source)&&!strExists("fmod(", $source)&&!strExists("base_convert(", $source)&&!strExists("atan2(", $source)&&!strExists("pow(", $source)&&!strExists("log(", $source)) $source	= str_replace(',','.',$source);
$badRequest	= mb_strlen($source)>255;
if (strExists("^", $source))
{
	$badRequest	= true;
	file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.prepareStringForReturn('&text=^ is wrong symbol. Use pow(a,b)'));
}
if (strExists("sleep", $source))
{
	$badRequest	= true;
	file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.'&text=please%20dont%20hack%20me.%20i%20just%20try%20to%20make%20world%20better)');
}
if (!$badRequest&&($crop||($chat==$user&&substr($message,0,1)!='/')))
	{
	if (!strExists("$", $source)&&!strExists("while", $source))
		{
		//saveToLog($source,$user);
		$result	= 0;
		if (eval('$result = '.$source.';')===false) $badRequest=true;
		else
			{
			$source = ' = '.$source;
			file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.'&text='.$result.prepareStringForReturn($source));
			}
		}
else
		{
		$badRequest	= true;
		}
	}
if ($badRequest) file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat.'&text=Bad%20request:%20'.prepareStringForReturn($source).'%0atype%20/help@CalcuBot');
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/*if (substr($text,0,14)=='/rp@vkReposter'||substr($text,0,3)=='/rp')
		{
			$postId	= parseUrl($text);
			if ($postId===false) file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text=bad request');
			else //receive images from url
			{
				$json = file_get_contents('https://api.vk.com/method/wall.getById?posts='.$postId);
				$action = json_decode($json, true);
				$topic	= str_replace("<br>","%0A",$action['response'][0]['text']);
				file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text='.$topic);
				$attachments	= $action['response'][0]['attachments'];
				for ($i=0;$i<count($attachments);$i++) {
					sendPhoto($attachments[$i]['photo']['src_big'],$c,$chat_id);
				}
			}
		}
		if ($text=='/example@vkReposter'||$text=="/example")
		{
			file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text=The response sent in personal chat');
			$chat_id	= $update["message"]['from']['id'];
			sendLocalPhoto("images/vkReposterExample0.png",$c,$chat_id);
			sendLocalPhoto("images/vkReposterExample1.png",$c,$chat_id);
			file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text=Then, @vkReposter sends the post title and a series of his images');
			sendLocalPhoto("images/vkReposterExample2.png",$c,$chat_id);
		}
		if ($text=='/help@vkReposterBot'||$text=='/help')
		{
			file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text=The response sent in personal chat');
			$chat_id	= $update["message"]['from']['id'];
			$AnswerText	= "برای کار کردن با ربات به صورت زیر عمل کنید
جمع:
عدد+عدد
تفریق:
عدد-عدد
ضرب:
عدد*عدد";
			file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text='.$AnswerText);
		}*/
		/*if (substr($text,0,17)=='/group@vkReposter'||$text=="/group") file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text='.$chat_id);
		$vkpos	= strpos($text, 'vk.com/');
		$spacepos	= strpos($text, ' ');
		file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text='.strpos($url, 'vk.com/'));//.$vkpos.";".$spacepos.";".substr($test,$spacepos));
		//message from personal chat, for redirect to grou[
		if ($vkpos!=FALSE&&$spacepos!=FALSE&&$vkpos>$spacepos)
		{
			$chat_id	= substr($text,$spacepos);
			file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text='.$text);
		}*/
		//else file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id.'&text=cmd not found');
}
//QUERY FROM USER
if (isset($update["inline_query"])) {
    $inlineQuery = $update["inline_query"];
    $queryId = $inlineQuery["id"];
    $source	= $inlineQuery["query"];
    $source	= strtolower($source);
    $results = array();
    $badRequest	= FALSE;
		$result	= 0;
		if (mb_strlen($source)>255) 							{addInlineResult("11","query should be shortly than 255 symbols",				"wrong query");$badRequest	= TRUE;}
		if (strpos($source,"^")!==false)						{addInlineResult("12","^ is wrong symbol. Use pow(a,b)",						"wrong query");$badRequest	= TRUE;}
		if (strpos($source,"sleep")!==false||strpos($source,"while")!==false||strpos($source,"$")!==false)	{addInlineResult("13","please dont hack me. i just try to make world better",	"wrong query");$badRequest	= TRUE;}
		if ($badRequest||eval('$result = '.$source.';')===false) addInlineResult("10","waiting for complete query","wrong query");
		else
			{
			$resultString	= strval($result);
			addInlineResult("1",$resultString,$resultString);
			addInlineResult("2",$resultString.' = '.$source,$resultString.' = '.$source);
			addInlineResult("3",$source.' = '.$resultString,$source.' = '.$resultString);
			}
      apiRequestJson
       (
       "answerInlineQuery", array(
        "inline_query_id" => $queryId,
        "results" => $results,
        "cache_time" => 1,
       )
      );
}
//CHOSEN INLINE RESULT
/*if (isset($update["chosen_inline_result"])) {
	//now disabled, because chat_id still uavialable in chosen_inline_result
	$chosen_inline_result=$update["chosen_inline_result"];
	//file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id."&text=chat: ".$chosen_inline_result["result_id"]);
	//file_get_contents('https://api.telegram.org/bot'.BOT_TOKEN.'/sendMessage?chat_id='.$chat_id."&text=link: ".$chosen_inline_result["query"]);
}*/
?>