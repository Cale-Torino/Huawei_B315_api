# Huawei B315 API

[<img src="img/logo.png" width="500"/>](img/logo.png)

---

*Developed by C.A Torino, TECHRAD Radical Technology*
* Links to TECHRAD ZA.
    * [Website](https://www.techrad.co.za)
    * [Tutorial website](https://tutorials.techrad.co.za)
    * [Support website](https://support.techrad.co.za)
    * [API](https://www.techrad.co.za/apisource/public/apps/fusio)

# Summary

API doc containing endpoints that can be called to leverage the Huawei B315 4G internet router.

These endpoints all run on the localhost `192.168.8.1` as an example.

- Usage examples: 
    - Automation scripts (run a routine)
    - Trigger events (if else etc.)
    - Automated logging (monitor specifics in your DIY script)
    - Send data to an endpoint that reaches out to the internet (reboot over internet securely)

  # XML data

Challenge_login post xml data:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
<username>admin</username>
<firstnonce>sdfgdsfgvcb54356yth687765jhgf</firstnonce>
<mode>4</mode>
</request>
```

Authentication_login post xml data:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
<clientproof>2134rtfegfdsgesr</clientproof>
<finalnonce>45645tgdfshgdfhdgfhghgfdh</finalnonce>
</request>
```

Control post xml data:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
<Control>1</Control>
</request>
```

Logout post xml data:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
<Logout>1</Logout>
</request>
```

# Index
- Summary
- XML data
- Index
- Usage Examples
- Endpoint API Calls
    - Example Login:
        - `GET` /api/webserver/SesTokInfo
        - `GET` /api/user/state-login
        - `POST` /api/user/challenge_login
        - `POST` /api/user/authentication_login
        - `POST` /api/device/control
    - List Of Endpoints:
        - GET
            - `GET` /api/global/module-switch
            - `GET` /api/wlan/wifi-feature-switch
            - `GET` /api/device/usb-tethering-switch
            - `GET` /config/pcassistant/config.xml
            - `GET` /config/global/languagelist.xml
            - `GET` /api/monitoring/converged-status
            - `GET` /api/user/white_list_switch
            - `GET` /api/webserver/publickey
            - `GET` /config/deviceinformation/config.xml
            - `GET` /config/webuicfg/config.xml
            - `GET` /api/user/state-login
            - `GET` /api/pin/status
            - `GET` /api/monitoring/status
            - `GET` /api/online-update/autoupdate-config
            - `GET` /api/wlan/basic-settings
            - `GET` /api/dialup/mobile-dataswitch
            - `GET` /api/redirection/homepage
            - `GET` /api/net/current-plmn
            - `GET` /api/monitoring/traffic-statistics
            - `GET` /api/webserver/SesTokInfo
            - `GET` /api/user/state-login
            - `GET` /api/webserver/token
            - `GET` /api/dhcp/settings
            - `GET` /config/global/config.xml
            - `GET` /config/lan/config.xml
            - `GET` /api/user/state-login
            - `GET` /config/global/config.xml
            - `GET` /api/global/module-switch
            - `GET` /api/cradle/status-info
            - `GET` /api/pin/status
            - `GET` /api/pin/simlock
            - `GET` /api/device/basic_information
            - `GET` /config/lan/config.xml
            - `GET` /html/home.html
        - POST
            - `POST` /api/app/privacynoticeinfo (application/x-www-form-urlencoded)
            - `POST` /api/host/info (application/x-www-form-urlencoded)
            - `POST` /api/user/challenge_login (application/x-www-form-urlencoded)
            - `POST` /api/user/authentication_login (application/x-www-form-urlencoded)


# Usage Examples

```PHP

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

$header1 = array();
$header2 = array();

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

```


# Endpoint API Calls

## 1. SesTokInfo

``GET` SesTokInfo` [*`GET` the SesTokInfo of the router*]

-------------------

### XML Parameters (Input)

| Parameter  | example values  |
| :------------ | :------------ |
|`null`      |null |

### Interface Address

http://192.168.8.1/api/webserver/SesTokInfo

### Request Method

- HTTP 
- `GET`

### XML Parameters (Output)

| Parameter  | example values  |
| :------------ | :------------ |
|`sesinfo`      |SessionID=kWIHa9RCZxuE15JthUABHA4gMdgczghN7QWAB0QMGSRRytwo8SbT+l8r3KIJbOVuZ1cxZmyWVYbUwe9AQrZtjzJCUHQLbJATiSgYJKkavkPhmyXBOod7FNTv4erfNcDz |
|`tokinfo`      |oTtQOa/OH2tMCZJ5Sph+xOEyI7yyudDI |

### Response Result Example
```xml
<?xml version="1.0" encoding="UTF-8"?>
<response>
<sesinfo>SessionID=kWIHa9RCZxuE15JthUABHA4gMdgczghN7QWAB0QMGSRRytwo8SbT+l8r3KIJbOVuZ1cxZmyWVYbUwe9AQrZtjzJCUHQLbJATiSgYJKkavkPhmyXBOod7FNTv4erfNcDz</sesinfo>
<tokinfo>oTtQOa/OH2tMCZJ5Sph+xOEyI7yyudDI</tokinfo>
</response>
```

---
---
---

## 2. state-login

``GET` state-login` [*`GET` the state-login of the router*]

-------------------

### XML Parameters (Input)

| Parameter  | example values  |
| :------------ | :------------ |
|`null`      |null |

### Interface Address

http://192.168.8.1/api/user/state-login

### Request Method

- HTTP 
- `GET`

### XML Parameters (Output)

| Parameter  | example values  |
| :------------ | :------------ |
|`state`                |0 |
|`username`             |admin |
|`password_type`        |4 |
|`firstlogin`           |1 |
|`extern_password_type` |1 |
|`accounts_number`      |1 |

### Response Result Example
```xml
<?xml version="1.0" encoding="UTF-8"?>
<response>
<state>0</state>
<username>admin</username>
<password_type>4</password_type>
<firstlogin>1</firstlogin>
<extern_password_type>1</extern_password_type>
<accounts_number>1</accounts_number>
</response>
```

---
---
---

## 3. challenge_login

``POST` challenge_login` [*`POST` the challenge_login credentials of the router*]

-------------------

### XML Parameters (Input) (Payload)

| Parameter  | example values  |
| :------------ | :------------ |
|`username`      |admin |
|`firstnonce`      |sdfgdsfgvcb54356yth687765jhgf |
|`mode`      |4 |

### Input Example
```xml
<?xml version="1.0" encoding="UTF-8"?>
<request>
<username>admin</username>
<firstnonce>sdfgdsfgvcb54356yth687765jhgf</firstnonce>
<mode>4</mode>
</request>
```

### Interface Address

http://192.168.8.1/api/user/challenge_login

### Headers
```
__RequestVerificationToken: kIvQ16Ih5iUSf89Ycdp7PCUcgI8Pj7QH

Cookie: SessionID=FLGiHt9EoYxZ+kP9nV6Rb3T+jAq4/g0QpTZMkVEwaOseOYqtRJXa0bZKYOQaTbF3xNH4vLFbX/nbDcM3l/DFcMf7FGh265/yixfj8xN4scFP0c796lQmphGY6mxYnyKU

Content-Type: application/x-www-form-urlencoded;charset=UTF-8
```

### Request Method

- HTTP 
- `POST`

### Response Parameters (Output)
| Parameter  | example values  |
| :------------ | :------------ |
|`salt`      |736e6c65353035745a48346b7a6b594b4d4f534a4231717132494f644d306f00 |
|`iterations`      |100 |
|`servernonce`      |bde7b68ace9cde355a5cba5205a0af223d0066c5362eaa1dbd156f6ca463445en8JaugYT0ifoJQYKK9JF6XVB4ugcAv53 |
|`modeselected`      |1 |


### Response Result Example
```xml
<?xml version="1.0" encoding="UTF-8"?>
<response>
<salt>736e6c65353035745a48346b7a6b594b4d4f534a4231717132494f644d306f00</salt>
<iterations>100</iterations>
<servernonce>bde7b68ace9cde355a5cba5205a0af223d0066c5362eaa1dbd156f6ca463445en8JaugYT0ifoJQYKK9JF6XVB4ugcAv53</servernonce>
<modeselected>1</modeselected>
</response>
```

---
---
---

## 0. SesTokInfo

``GET` SesTokInfo` [*`GET` the SesTokInfo of the router*]

-------------------

### Calling Parameters (Input)
| Parameter  |  Mode  | Description  | example values  |
| :------------ | :------------ | :------------ | :------------ |
|`NULL`      |NULL |NULL      |NULL |

### Input Example
```xml

```

### Interface Address

http://192.168.8.1/api/webserver/SesTokInfo

### Request Method

- HTTP 
- `GET`

### Response Parameters (Output)
| Parameter  |  Mode  | Description  | example values  |
| :------------ | :------------ | :------------ | :------------ |
|`null`            |string      |Https        |Cape Town                   |
|`null`            |string      |Https        |1                           |
|`null`            |string      |Https        |null   |
|`null`            |string      |Https        |null   |


### Response Result Example
```JSON

```

---
---
---
