<?php
/**
 * Webhook for Time Bot- Facebook Messenger Bot
 * User: adnan
 * Date: 24/04/16
 * Time: 3:26 PM
 */
$data = json_decode(file_get_contents('php://input'), true);
print_r($data);
$send = 0;
$access_token = "EAAEL1WUHT3QBAKzrCPLym8Y28foozzzam1u3hn9rrMad5uATK2N01Bcp5y7wh2vHaRhUvAjxlfj3lD9G4VN98ZBeFKLoUkp7nNHZCnmm7C866pOi2uqTykbSiUs8y720mSoMhNs4mqyiZBsUz45It3HcbFZBd1niqhiN2YgkVQZDZD";
$verify_token = "sitel_bot_1224";
$hub_verify_token = null;
if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    $hub_verify_token = $_REQUEST['hub_verify_token'];
}
if ($hub_verify_token === $verify_token) {
    echo $challenge;
}
$input = json_decode(file_get_contents('php://input'), true);
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];
$message = $input['entry'][0]['messaging'][0]['message']['text'];
$postback = isset($input['entry'][0]['messaging'][0]['postback']['payload']) ? $input['entry'][0]['messaging'][0]['postback']['payload']: '' ;
$message_to_reply = '';
/**
 * Some Basic rules to validate incoming messages
 */
if(preg_match('[Estado Popayan]', strtolower($message))) {
    // Make request to Time API
    $date = date('Y/m/d H:i:s');
    $result = $date;
    if($result != '') {
        $message_to_reply = $result;
    }
}
else if (preg_match('[id]', strtolower($message))) {
    // Make request to Time API
    $message_to_reply = $sender;
}

if($data["id"]==1){
    $message_to_reply = "El enlace SITEL_RX_PPY-MN ha caído";
    $sender = "1307847875995434";
    $send = 1;
}
switch($postback) {
    
      case 'Popayan':
        $message_to_reply = 'You clicked Help button';
        $send = 1;
        break;
        
      case 'Platrado':
        $message_to_reply = 'You clicked Latest Post button';
        $send = 1;
        break;
    
    
}
//API Url
$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$access_token;
//Initiate cURL.
$ch = curl_init($url);
//The JSON data.
$jsonData = '{
    "recipient":{
        "id":"'.$sender.'"
    },
    "message":{
        "text":"'.$message_to_reply.'"
    }
}';
//Encode the array into JSON.
$jsonDataEncoded = $jsonData;
//Tell cURL that we want to send a POST request.
curl_setopt($ch, CURLOPT_POST, 1);
//Attach our encoded JSON string to the POST fields.
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonDataEncoded);
//Set the content type to application/json
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
//Execute the request
if(!empty($input['entry'][0]['messaging'][0]['message']) || $send==1){
    $result = curl_exec($ch);
}
?>

