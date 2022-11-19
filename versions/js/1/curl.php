<?php
//C:\Software\xampp\htdocs\Tuya\ELAC_testToken.php
//http://127.0.0.1/b315/curl.php?data=GET

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

$headers = array();

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
        "Message" => "No Command provided"
      ));
}

function xml_encode(mixed $value=null, string $key="root", SimpleXMLElement $parent=null){
  if(is_object($value)) $value = (array) $value;
  if(!is_array($value)){
      if($parent === null){   
          if(is_numeric($key)) $key = 'item';             
          if($value===null) $node = new SimpleXMLElement("<$key />");//$node = new SimpleXMLElement("<$key />");
          else              $node = new SimpleXMLElement("<$key>$value</$key>");
      }
      else{
          $parent->addChild($key, $value);
          $node = $parent;
      }
  }
  else{
      $array_numeric = false;
      if($parent === null){ 
          if(empty($value)) $node = new SimpleXMLElement("<$key />");//$node = new SimpleXMLElement("<$key />");
          else              $node = new SimpleXMLElement("<$key></$key>");
      }
      else{
          if(!isset($value[0])) $node = $parent->addChild($key);
          else{
              $array_numeric = true;
              $node = $parent;
          }
      }
      foreach( $value as $k => $v ) {
          if($array_numeric) xml_encode($v, $key, $node);
          else xml_encode($v, $k, $node);
      }
  }       
  return $node;
}

//https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request/25118032#25118032
function HandleHeaderLine($curl,$header_line){
  //$header = explode(':', $header_line);
  //$result = "";
  //foreach ($header as $value) {
    //$result .= $value;
  //}
  //echo $result;
  // echo json_encode(array(
  //   "result" => "1",
  //   "Message" => $header_line
  // ));
  global $headers;
  $headers[] = $header_line;
  //echo $header_line; // or do whatever
  return strlen($header_line);
  //return $header_line;
}
//cUrl Get function
function cURLGET($url,$cookie){
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_COOKIE => $cookie,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_HEADERFUNCTION => "HandleHeaderLine",
    CURLOPT_FOLLOWLOCATION => true,
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
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_HTTPHEADER => array(
    "Content-Type: application/x-www-form-urlencoded;charset=UTF-8",
    "__RequestVerificationToken: $tokinfo"
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
//global $headers;
//$xml_element = xml_encode($headers, "headerline");
//echo $xml_element->asXML();
}