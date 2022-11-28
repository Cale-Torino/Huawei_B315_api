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

# Index
- Summary
- Index
- Usage Examples
- Endpoint API Calls
    - /api/webserver/SesTokInfo
    - /api/user/state-login
    - /api/user/challenge_login
    - /api/user/authentication_login
    - /api/device/control

- GET /api/global/module-switch
- GET /api/wlan/wifi-feature-switch
- GET /api/device/usb-tethering-switch
- GET /config/pcassistant/config.xml
- GET /config/global/languagelist.xml
- GET /api/monitoring/converged-status
- GET /api/user/white_list_switch
- POST /api/app/privacynoticeinfo (application/x-www-form-urlencoded)
- GET /api/webserver/publickey
- GET /config/deviceinformation/config.xml
- GET /config/webuicfg/config.xml
- GET /api/user/state-login
- GET /api/pin/status
- GET /api/monitoring/status
- GET /api/online-update/autoupdate-config
- POST /api/host/info (application/x-www-form-urlencoded)
- GET /api/wlan/basic-settings
- GET /api/dialup/mobile-dataswitch
- GET /api/redirection/homepage
- GET /api/net/current-plmn
- GET /api/monitoring/traffic-statistics
- GET /api/webserver/SesTokInfo
- POST /api/user/challenge_login (application/x-www-form-urlencoded)
- POST /api/user/authentication_login (application/x-www-form-urle
- GET /api/user/state-login
- GET /api/webserver/token
- GET /api/dhcp/settings
- GET /config/global/config.xml
- GET /config/lan/config.xml
- GET /api/user/state-login
- GET /config/global/config.xml
- GET /api/global/module-switch
- GET /api/cradle/status-info
- GET /api/pin/status
- GET /api/pin/simlock
- GET /api/device/basic_information
- GET /config/lan/config.xml
- GET /html/home.html

# Usage Examples


# Endpoint API Calls

## 1. SesTokInfo

`Get SesTokInfo` [*Get the SesTokInfo of the router*]

-------------------

### Calling Parameters (Input)
| Parameter  |  Mode  | Description  | example values  |
| :------------ | :------------ | :------------ | :------------ |
|`NULL`      |NULL |NULL      |NULL |

### Interface Address

http://192.168.8.1/api/webserver/SesTokInfo

### Request Method

- HTTP 
- GET

### Response Parameters (Output)
| Parameter  |  Mode  | Description  | example values  |
| :------------ | :------------ | :------------ | :------------ |
|`null`            |string      |Https        |Cape Town                   |
|`null`            |string      |Https        |1                           |
|`null`            |string      |Https        |null   |
|`null`            |string      |Https        |null   |

### Example:

- Returned data: 
   - data
- Example: 
   - data
   - data

### Response Result Example
```JSON

```

---
---
---
