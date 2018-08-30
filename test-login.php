<?php

require_once "Auth/OpenID/Consumer.php";
require_once "Auth/OpenID/FileStore.php";

session_start();

/**
 * realmの取得.
 *
 * @return realm値.
 */
function getRealm() {
    $realm = "http://jtv.cmnow.net:22280/rp/";
    return $realm;
}

/**
 * returnToの取得.
 *
 * @return returnTo値.
 */
function getReturnTo() {
    $returnTo = "http://jtv.cmnow.net:22280/rp/test-login.php";
    return $returnTo;
}

/**
 * エラー画面表示（indexにエラーメッセージ表示）.
 *
 * @param msg エラーメッセージ.
 */
function errorExit($msg) {
    $errMsg = $msg;
    include 'index.php';
    exit(0);
}

/**
 * RP(Consumer)のマネージャ取得.
 *
 * @param msg エラーメッセージ.
 * @return RPマネージャ.
 */
function &getConsumer() {
    // TODO 環境に合わせディレクトリを変更してください
    $store_path = "/tmp/_php_consumer_store";
    if (!file_exists($store_path) && !mkdir($store_path)) {
        errorExit("認証情報格納領域の確保が失敗");
    }
    $store = new Auth_OpenID_FileStore($store_path);
    // マネージャを取得
    $consumer = & new Auth_OpenID_Consumer($store);
    return $consumer;
}

/**
 * ユーザ属性情報要求クエリの生成.
 *
 * @param openid OpenID.
 * @param nonce Nonce.
 * @return 要求クエリ文字列.
 */
function makeQuery($openid, $nonce) {
    $query = "ver=1.0";
    $query.= "&openid=";
    $query.= urlencode($openid);
    $query.= "&nonce=";
    $query.= urlencode($nonce);
    $query.= "&GUID=";
    $query.= "&UA=";
    return $query;
}

/**
 * ユーザ属性情報応答の解析.
 *
 * @param responseMessage 応答メッセージ.
 * @return 解析結果.
 */
function parseResponse($responseMessage) {
    $resultdata = array();
    // responceメッセージを改行で分割する
    $userInfo = explode("\r\n", $responseMessage);
    foreach ($userInfo as $line) {
        // ユーザ属性情報のパラメータ名と一致するか確認する
        $match = preg_match("/^(ver|result|GUID|UA):.*/", $line);
        if ($match == 1) {
            // パラメータ名と値をコロンで分割する
            $s = preg_split("/:/", $line);
            $key = $s[0];
            $val = trim($s[1]);
            $resultdata[$key] = $val;
        }
    }
    return $resultdata;
}

/**
 * ユーザ属性情報取得.
 *
 * @param openid OpenID.
 * @param nonce Nonce.
 * @return 属性情報配列.
 */
function getUserAttribute($openid, $nonce) {
    error_log("[rp] getUserAttribute start.");
    $url = "https://i.mydocomo.com/api/imode/g-info";
    $url.= "?";
    $url.= makeQuery($openid, $nonce);
    // サーバ間通信で属性情報を取得する
    $client = curl_init($url);
    curl_setopt($client, CURLOPT_RETURNTRANSFER, 1);
    $cv = curl_version();
    if (is_array($cv)) {
        $curl_user_agent = 'curl/' . $cv['version'];
    } else {
        $curl_user_agent = $cv;
    }
    curl_setopt($client, CURLOPT_USERAGENT, Auth_OpenID_USER_AGENT . ' ' . $curl_user_agent);
    $responseMessage = curl_exec($client);
    $code = curl_getinfo($client, CURLINFO_HTTP_CODE);
    $errmsg = curl_error($client);
    curl_close($client);
    // 応答メッセージのHTTPステータスコードを検証する
    if ($code != '200') {
        error_log("[rp] getUserAttribute Error. HTTP_code=" . $code . " msg=" . $errmsg);
    }
    // ユーザ属性情報応答の解析
    $result = parseResponse($responseMessage);
    $result["verified.openid"] = $openid;
    error_log("[rp] getUserAttribute end.");
    return $result;
}

/**
 * 認証要求.
 */
function doAuthRequest() {
    error_log("[rp] doAuthRequest start.");
    $docomo_op_identifier = "https://i.mydocomo.com";
    $consumer = getConsumer();
    // SHA256のみでネゴシエーションする
    $order[] = array('HMAC-SHA256', 'DH-SHA256');
    $consumer->consumer->negotiator = & new Auth_OpenID_SessionNegotiator($order);
    // 認証要求を開始する
    // ディスカバリとアソシエーションの確立を行う
    $auth_request = $consumer->begin($docomo_op_identifier);
    if (!$auth_request) {
        errorExit("認証要求が失敗.");
    }
    // OpenID 1(リダイレクト送信)か確認する
    if ($auth_request->shouldSendRedirect()) {
        // OpenID 1 の場合は未サポート
        errorExit("認証要求がOpenID 1なので未サポート.");
    } else {
        // 自動POST画面を生成する
        $realm = getRealm();
        $returnTo = getReturnTo();
        $form_id = 'openidparam';
        $form_html = $auth_request->htmlMarkup($realm, $returnTo, false, array('id' => $form_id));
        // 自動POST画面の生成が正常か確認する
        if (Auth_OpenID::isFailure($form_html)) {
            errorExit("画面自動生成エラー: " . $form_html->message);
        } else {
            // 自動POST画面を出力
            print $form_html;
        }
    }
    error_log("[rp] doAuthRequest end.");
}

/**
 * 認証アサーション検証.
 */
function doVerifyResponse() {
    error_log("[rp] doVerifyResponse start.");
    // マネージャを取得する
    $consumer = getConsumer();
    // 認証応答を検証する
    $return_to = getReturnTo();
    $response = $consumer->complete($return_to);
    // 認証応答の検証結果を確認する
    $status = $response->status;
    switch ($status) {
        case Auth_OpenID_CANCEL:
            error_log("[rp] CANCEL.");
            errorExit("CANCEL.");
        break;
        case Auth_OpenID_FAILURE:
            error_log("[rp] FAILURE.");
            errorExit("FAILURE.");
        break;
        case Auth_OpenID_SUCCESS:
            error_log("[rp] SUCCESS.");
        break;
    }
    // OpenIDを取得する
    $openid = $response->getDisplayIdentifier();
    // noceを取得する
    $nonce = $response->message->getArg(Auth_OpenID_OPENID2_NS, 'response_nonce');
    // ユーザ属性情報を取得する
    $attr = getUserAttribute($openid, $nonce);
    // TODO 認証後の処理を実装してください
    error_log("[rp] doVerifyResponse end.");
}

/**
 * メイン処理.
 */
function main() {
    error_log("[rp] main start.");
    // OpenID認証応答の場合
    // パラメータ名のピリオドはアンダースコアに変換(openid.mode -> openid_mode)
    if ((isset($_POST['openid_mode']) and ($_POST['openid_mode'] == "id_res")) || 
        (isset($_GET['openid_mode'] ) and ($_GET['openid_mode']  == "id_res"))) {
        doVerifyResponse();
    }

    // 画面からSSOの要求があった場合
    else if ((isset($_POST['loginop']) and ($_POST['loginop'] == "docomo")) || 
               (isset($_GET['loginop'] ) and ($_GET['loginop']  == "docomo"))) {
        doAuthRequest();
    }

    // その他
    else {
        include "index.php";
    }
}

//メインを実行
main();

