<?php
define('BOT_TOKEN', '293426288:AAFJm-rEN3Tx60j4aVo0-sdoGh_CZ06R7QY');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

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
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
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
  $boolean = file_get_contents('booleans.txt');
  $booleans= explode("\n",$boolean);
  $admin = 119810931;
  $message_id = $message['message_id'];
  $rpto = $message['reply_to_message']['forward_from']['id'];
  $chat_id = $message['chat']['id'];
  $txxxtt = file_get_contents('msgs.txt');
  $pmembersiddd= explode("-!-@-#-$",$txxxtt);
  if (isset($message['photo'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>馃毇
Get Out Of Here Idiot馃枙
--------------------------------
卮賲丕 丿乇 賱蹖爻鬲 爻蹖丕賴 賯乇丕乇 丿丕乇蹖丿 馃毇
賱胤賮丕 倬蹖丕賲 賳丿賴蹖丿馃枙" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
    $photo = $message['photo'];
    $photoid = json_encode($photo, JSON_PRETTY_PRINT);
    $photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
    $photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
    $pos = strpos($photoidd, '",\n');
    //$pphoto = strrpos($photoid,'",\n        \"file_size\": ',1);
    $pos = $pos -1;
    $substtr = substr($photoidd, 0, $pos);
    $caption = $message['caption'];
    if($caption != "")
    {
    apiRequest("sendphoto", array('chat_id' => $rpto, "photo" => $substtr,"caption" =>$caption));
    }
    else{
        apiRequest("sendphoto", array('chat_id' => $rpto, "photo" => $substtr));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "馃棧倬蹖丕賲 卮賲丕 丕乇爻丕賱 卮丿. " ,"parse_mode" =>"HTML"));
    
}  else if ($chat_id == $admin && $booleans[0] == "true") {
    
    $photo = $message['photo'];
    $photoid = json_encode($photo, JSON_PRETTY_PRINT);
    $photoidd = json_encode($photoid, JSON_PRETTY_PRINT); 
    $photoidd = str_replace('"[\n    {\n        \"file_id\": \"','',$photoidd);
    $pos = strpos($photoidd, '",\n');
    //$pphoto = strrpos($photoid,'",\n        \"file_size\": ',1);
    $pos = $pos -1;
    $substtr = substr($photoidd, 0, $pos);
    $caption = $message['caption'];
    
    
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			if($caption != "")
    {
    apiRequest("sendphoto", array('chat_id' => $membersidd[$y], "photo" => $substtr,"caption" =>$caption));
    }
    else{
        apiRequest("sendphoto", array('chat_id' => $membersidd[$y], "photo" => $substtr));
    }
			
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "馃摝 倬蹖丕賲 卮賲丕 亘賴  ".$memcout." 賲禺丕胤亘 丕夭爻丕賱 卮丿.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('馃棧 Send To All'),array('鈿擄笍 Help','馃懃 Members','鉂� Blocked Users'),array("Settings 鈿�")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
    }
  }
    if (isset($message['video'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1],"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>馃毇
Get Out Of Here Idiot馃枙
--------------------------------
卮賲丕 丿乇 賱蹖爻鬲 爻蹖丕賴 賯乇丕乇 丿丕乇蹖丿 馃毇
賱胤賮丕 倬蹖丕賲 賳丿賴蹖丿馃枙" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $video = $message['video']['file_id'];
    $caption = $message['caption'];
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    if($caption != "")
    {
    apiRequest("sendvideo", array('chat_id' => $rpto, "video" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendvideo", array('chat_id' => $rpto, "video" => $video));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>"馃棧倬蹖丕賲 卮賲丕 丕乇爻丕賱 卮丿. ","parse_mode" =>"HTML"));
    
}
else if ($chat_id == $admin && $booleans[0] == "true") {
    $video = $message['video']['file_id'];
    $caption = $message['caption'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			if($caption != "")
    {
    apiRequest("sendvideo", array('chat_id' => $membersidd[$y], "video" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendvideo", array('chat_id' => $membersidd[$y], "video" => $video));
    }
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "馃摝 倬蹖丕賲 卮賲丕 亘賴  ".$memcout." 賲禺丕胤亘 丕夭爻丕賱 卮丿.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('馃棧 Send To All'),array('鈿擄笍 Help','馃懃 Members','鉂� Blocked Users'),array("Settings 鈿�")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
    }
  }
   if (isset($message['sticker'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1] ,"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>馃毇
Get Out Of Here Idiot馃枙
--------------------------------
卮賲丕 丿乇 賱蹖爻鬲 爻蹖丕賴 賯乇丕乇 丿丕乇蹖丿 馃毇
賱胤賮丕 倬蹖丕賲 賳丿賴蹖丿馃枙" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $sticker = $message['sticker']['file_id'];
   
    apiRequest("sendsticker", array('chat_id' => $rpto, "sticker" => $sticker));
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" =>"馃棧倬蹖丕賲 卮賲丕 丕乇爻丕賱 卮丿. " ,"parse_mode" =>"HTML"));
    
}

 else if ($chat_id == $admin && $booleans[0] == "true") {
       $sticker = $message['sticker']['file_id'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){
			//apiRequest("sendMessage", array('chat_id' => $membersidd[$y], "text" => $texttoall,"parse_mode" =>"HTML"));
			
			    apiRequest("sendsticker", array('chat_id' => $membersidd[$y], "sticker" => $sticker));

			
			
		}
		$memcout = count($membersidd)-1;
	 	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "馃摝 倬蹖丕賲 卮賲丕 亘賴  ".$memcout." 賲禺丕胤亘 丕夭爻丕賱 卮丿.
.","parse_mode" =>"HTML",'reply_markup' => array(
        'keyboard' => array(array('馃棧 Send To All'),array('鈿擄笍 Help','馃懃 Members','鉂� Blocked Users'),array("Settings 鈿�")),
        'one_time_keyboard' => true,
        'selective' => true,
        'resize_keyboard' => true)));
         $addd = "false";
    	file_put_contents('booleans.txt',$addd); 
}
  }
  
  
  
  if (isset($message['document'])) {
      
      if ( $chat_id != $admin) {
    	
    	$txt = file_get_contents('banlist.txt');
$membersid= explode("\n",$txt);

$substr = substr($text, 0, 28);
	if (!in_array($chat_id,$membersid)) {
		apiRequest("forwardMessage", array('chat_id' => $admin,  "from_chat_id"=> $chat_id ,"message_id" => $message_id));
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $pmembersiddd[1],"parse_mode" =>"HTML"));	
}else{
  
apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "<b>You Are Banned</b>馃毇
Get Out Of Here Idiot馃枙
--------------------------------
卮賲丕 丿乇 賱蹖爻鬲 爻蹖丕賴 賯乇丕乇 丿丕乇蹖丿 馃毇
賱胤賮丕 倬蹖丕賲 賳丿賴蹖丿馃枙" ,"parse_mode" =>"HTML"));	

}
    }
    else if($rpto !="" && $chat_id==$admin){
   $video = $message['document']['file_id'];
    $caption = $message['caption'];
    //apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => $video ,"parse_mode" =>"HTML"));
    if($caption != "")
    {
    apiRequest("sendDocument", array('chat_id' => $rpto, "document" => $video,"caption" =>$caption));
    }
    else{
        apiRequest("sendDocument", array('chat_id' => $rpto, "document" => $video));
    }
	apiRequest("sendMessage", array('chat_id' => $chat_id, "text" => "馃棧倬蹖丕賲 卮賲丕 丕乇爻丕賱 卮丿. " ,"parse_mode" =>"HTML"));
    
}
 else if ($chat_id == $admin && $booleans[0] == "true") {
    $video = $message['document']['file_id'];
		$ttxtt = file_get_contents('pmembers.txt');
		$membersidd= explode("\n",$ttxtt);
		for($y=0;$y<count($membersidd);$y++){

    apiRequest("sendDocument", array('chat_id' => $membersidd[$y], "document" => $video));
    
			
			
		}
		$me
