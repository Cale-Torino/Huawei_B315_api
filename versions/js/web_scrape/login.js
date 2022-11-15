function login(destnation, callback, redirectDes) {
    clearHeartBeatTimer();
    var name = $.trim($('#username').val());
    var psd = $('#password').val();
    var valid = validateInput(name, psd);
    if (!valid) {
        restartHeartBeatTimer();
        return;
    }
    refreshToken();
    if (true == g_scarm_login) {
        if ($.isArray(g_requestVerificationToken)) {
            if (g_requestVerificationToken.length <= 0) {
                setTimeout(function() {
                    if (g_requestVerificationToken.length > 0) {
                        login(destnation, callback, redirectDes);
                    }
                }, 50)
                restartHeartBeatTimer();
                return;
            }
        }
        var scram = CryptoJS.SCRAM();
        var firstNonce = scram.nonce().toString();
        var firstPostData = {
            username: name,
            firstnonce: firstNonce,
            mode: RSA_LOGIN_MODE
        };
        var firstXml = object2xml('request', firstPostData);
        saveAjaxData('api/user/challenge_login', firstXml, function($xml) {
            var ret = xml2object($xml);
            if (ret.type == 'response') {
                g_scarm_salt = CryptoJS.enc.Hex.parse(ret.response.salt);
                var iter = ret.response.iterations;
                var finalNonce = ret.response.servernonce;
                var authMsg = firstNonce + "," + finalNonce + "," + finalNonce;
                var saltPassword = scram.saltedPassword(psd, g_scarm_salt, iter);
                saltPassword = saltPassword.toString();
                var clientKey = scram.clientKey(CryptoJS.enc.Hex.parse(saltPassword));
                clientKey = clientKey.toString();
                var serverKey = scram.serverKey(CryptoJS.enc.Hex.parse(saltPassword));
                serverKey = serverKey.toString();
                var clientProof = scram.clientProof(psd, g_scarm_salt, iter, authMsg);
                clientProof = clientProof.toString();
                var finalPostData = {
                    clientproof: clientProof,
                    finalnonce: finalNonce
                };
                var finalXml = object2xml('request', finalPostData);
                saveAjaxData('api/user/authentication_login', finalXml, function($xml) {
                    ret = xml2object($xml);
                    if (ret.type == 'response') {
                        var serverProof = scram.serverProof(psd, g_scarm_salt, iter, authMsg);
                        serverProof = serverProof.toString();
                        if (ret.response.serversignature == serverProof) {
                            var publicKey = ret.response.rsan;
                            var publicKeySignature = scram.signature(CryptoJS.enc.Hex.parse(publicKey), CryptoJS.enc.Hex.parse(serverKey));
                            publicKeySignature = publicKeySignature.toString();
                            if (ret.response.rsapubkeysignature == publicKeySignature) {
                                g_encPublickey.e = ret.response.rsae;
                                g_encPublickey.n = ret.response.rsan;
                                storagePubkey(g_encPublickey.n, g_encPublickey.e);
                                getAjaxData('api/user/state-login', function($xml) {
                                    restartHeartBeatTimer();
                                    var ret = xml2object($xml);
                                    if (ret.type == 'response') {
                                        if ('undefined' != typeof(ret.response.firstlogin)) {
                                            g_default_password_status = parseInt(ret.response.firstlogin, 10);
                                        }
                                        g_login_state = ret.response.State;
                                        $('#username_span').text(name);
                                        $('#username_span').show();
                                        $('#logout_span').text(common_logout);
                                        var passwordStr = $('#password').val();
                                        clearDialog();
                                        g_main_displayingPromptStack.pop();
                                        startLogoutTimer(redirectDes);
                                        if (g_default_password_status == 0) {
                                            if (current_href == 'quicksetup') {
                                                window.location.reload();
                                            } else if (current_href != 'pincoderequired' && current_href != 'pukrequired' && current_href != 'simlockrequired' && current_href != 'nocard' && current_href != 'cradleDisconnected' && current_href != 'commend') {
                                                gotoPageWithoutHistory("modifypassword.html");
                                            }
                                        } else {
                                            if (checkPWRemind(passwordStr) && g_restore_default_status != '1') {
                                                checkDialogFlag = true;
                                                showPWRemindDialog(destnation, callback);
                                            } else {
                                                loginSwitchDoing(destnation, callback);
                                            }
                                        }
                                    }
                                });
                            } else {
                                showErrorUnderTextbox('username', IDS_login_fialed_prompt);
                                $('#username').focus();
                                $('#username').val('');
                                $('#password').val('');
                            }
                        } else {
                            showErrorUnderTextbox('username', IDS_login_fialed_prompt);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        }
                    } else {
                        if (ret.error.code == ERROR_LOGIN_USERNAME_PWD_ORERRUN) {
                            showErrorUnderTextbox('password', IDS_login_username_password_input_overrun);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        } else if (ret.error.code == ERROR_LOGIN_USERNAME_PWD_WRONG) {
                            showErrorUnderTextbox('password', IDS_login_username_password_wrong);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        }
                    }
                });
            } else {
                if (ret.error.code == ERROR_LOGIN_USERNAME_PWD_ORERRUN) {
                    showErrorUnderTextbox('password', IDS_login_username_password_input_overrun);
                    $('#username').focus();
                    $('#username').val('');
                    $('#password').val('');
                } else if (ret.error.code == ERROR_LOGIN_USERNAME_PWD_WRONG) {
                    showErrorUnderTextbox('password', IDS_login_username_password_wrong);
                    $('#username').focus();
                    $('#username').val('');
                    $('#password').val('');
                } else if (ret.error.code == ERROR_LOGIN_ALREADY_LOGIN) {
                    showErrorUnderTextbox('password', common_user_login_repeat);
                    $('#username').focus();
                    $('#username').val('');
                    $('#password').val('');
                }
            }
        });
    } else {
        if ($.isArray(g_requestVerificationToken)) {
            if (g_requestVerificationToken.length > 0) {
                if (g_password_type == '4') {
                    psd = base64encode(SHA256(name + base64encode(SHA256($('#password').val())) + g_requestVerificationToken[0]));
                } else {
                    psd = base64encode($('#password').val());
                }
            } else {
                setTimeout(function() {
                    if (g_requestVerificationToken.length > 0) {
                        login(destnation, callback, redirectDes);
                    }
                }, 50)
                restartHeartBeatTimer();
                return;
            }
        } else {
            psd = base64encode($('#password').val());
        }
        var request = {
            Username: name,
            Password: psd,
            password_type: g_password_type
        };
        if (valid) {
            var xmlstr = object2xml('request', request);
            saveAjaxData('api/user/login', xmlstr, function($xml) {
                log.debug('api/user/login successed!');
                restartHeartBeatTimer();
                var ret = xml2object($xml);
                if (isAjaxReturnOK(ret)) {
                    $('#username_span').text(name);
                    $('#username_span').show();
                    $('#logout_span').text(common_logout);
                    var passwordStr = $('#password').val();
                    clearDialog();
                    g_main_displayingPromptStack.pop();
                    startLogoutTimer(redirectDes);
                    if (checkPWRemind(passwordStr) && g_restore_default_status != '1') {
                        checkDialogFlag = true;
                        showPWRemindDialog(destnation, callback);
                    } else {
                        loginSwitchDoing(destnation, callback);
                    }
                } else {
                    if (ret.type == 'error') {
                        clearAllErrorLabel();
                        if (ret.error.code == ERROR_LOGIN_PASSWORD_WRONG) {
                            showErrorUnderTextbox('password', system_hint_wrong_password);
                            $('#password').val('');
                            $('#password').focus();
                        } else if (ret.error.code == ERROR_LOGIN_ALREADY_LOGIN) {
                            showErrorUnderTextbox('password', common_user_login_repeat);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        } else if (ret.error.code == ERROR_LOGIN_USERNAME_WRONG) {
                            showErrorUnderTextbox('username', settings_hint_user_name_not_exist);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        } else if (ret.error.code == ERROR_LOGIN_USERNAME_PWD_WRONG) {
                            showErrorUnderTextbox('password', IDS_login_username_password_wrong);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        } else if (ret.error.code == ERROR_LOGIN_USERNAME_PWD_ORERRUN) {
                            showErrorUnderTextbox('password', IDS_login_username_password_input_overrun);
                            $('#username').focus();
                            $('#username').val('');
                            $('#password').val('');
                        }
                    }
                }
            }, {
                enc: true
            });
        }
    }
}