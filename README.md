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
