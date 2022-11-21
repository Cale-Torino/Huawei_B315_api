<?php
/**
 * curl.php
 *
 * Send POST & GET requests via cURL
 *
 * @category   cURL requests
 * @package    cURL
 * @author     C.A Torino
 * @version    V1.0.0
 * @since      2022 November 21
 **/
//http://127.0.0.1/b315/curl.php?data=POST&url=http://192.168.8.102/goform/sysReboot&postdata=data&cookie=data
//http://127.0.0.1/b315/curl.php?data=GET&url=http://192.168.8.102/goform/getHomePageInfo?random=0.7189040724881826&modules=loginAuth,wifiRelay&cookie=data

//http://192.168.8.1/api/user/state-login
//http://192.168.8.1/api/webserver/SesTokInfo
//http://192.168.8.1/api/user/challenge_login
//http://localhost/b315/index2.html

$data = filter_input(INPUT_GET, 'data', FILTER_SANITIZE_URL);
$data = urldecode($data);

$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
$url = urldecode($url);

$postdata = filter_input(INPUT_GET, 'postdata', FILTER_SANITIZE_URL);
$postdata = urldecode($postdata);

$cookie = filter_input(INPUT_GET, 'cookie');
$cookie = base64_decode($cookie);

$tokinfo = filter_input(INPUT_GET, 'tokinfo');
$tokinfo = base64_decode($tokinfo);

//$url = "http://192.168.8.1/api/user/state-login";

// $headers = array(
//     "Content-Type: application/json", application/x-www-form-urlencoded
//     "Authorization: Bearer e281ac2e7fb08803bcec63cac529507882dd1eeb"
// );

// $postdata = json_encode(array(
//     "username" => "admin",
//     "firstnonce" => "3aa02875ef55bebf3218d5cfee83b467854896b3fc8ea92ec3b6e174037cea78",
//     "mode"=>"1"
//   ));

//foo=bar;baz=foo
//$cookie = "SessionID=UM4ONXDhBOHOfMHrMRm5859o7nslK+IM8iKSjYzHuzRqpeqZH62OOBlc31+Gk/6Nqh3rp6frYI0Q50Q7S1zeCl4elJrXd0KRDxdGR7WpmkiUGrSMiWT9JPMDPhPR6fQG";

$header1 = array();
$header2 = array();

function HandleHeaderLine($curl, $headerLine)
{
  global $header1;
  global $header2;
  if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $headerLine, $c) == 1)
  {
    $header1["SetCookie"] = $c[1];
  }
  if (preg_match('/^__RequestVerificationToken:\s*([^;]*)/mi', $headerLine, $c) == 1)
  {
    $header2["RequestVerificationToken"] = $c[1];
  }
  return strlen($headerLine);
}

if($data == "POST")
{
    cURLPOST($url,$postdata,$cookie,$tokinfo); 
}
else if($data == "GET")
{
    cURLGET($url,$cookie);
}
else
{
    echo json_encode(array(
        "Error" => "1",
        "Message" => "No request provided"
      ));
}

//https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/25118032#25118032

function cURLGET($url,$cookie){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_COOKIE => $cookie,
    CURLOPT_TIMEOUT => 0,
    //CURLOPT_HEADERFUNCTION => "HandleHeaderLine",
    CURLOPT_FOLLOWLOCATION => false,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/x-www-form-urlencoded;charset=UTF-8"
    ),
  ));
  
  $response = curl_exec($curl);
  
  curl_close($curl);
  echo $response;
}

function cURLPOST($url,$postdata,$cookie,$tokinfo){
  global $header1;
  global $header2;

$curl = curl_init();
//$postdata = json_encode($data);
curl_setopt_array($curl, array(
  CURLOPT_URL => $url,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_COOKIE => $cookie,
  CURLOPT_POSTFIELDS => $postdata,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_HEADERFUNCTION => "HandleHeaderLine",
  CURLOPT_FOLLOWLOCATION => false,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
    "__RequestVerificationToken: $tokinfo"
  ),
));

$response = curl_exec($curl);

curl_close($curl);

if($header1)
{
  $string = preg_replace('~[\r\n]+~', '', $header1);
  $j = json_encode($string);
  header("SetCookie: $j");
}
if($header2)
{
  $string = preg_replace('~[\r\n]+~', '', $header2);
  $j = json_encode($string);
  header("RequestVerificationToken: $j");
}
echo $response;
} 