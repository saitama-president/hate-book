<?php
//リクエスト

$POST_DATA = array(
    'USER' => 'ユーザ名',
    'PWD' => 'パスワード',
    'SIGNATURE' => 'APIトークン',
    'METHOD' => 'SetExpressCheckout',
    'VERSION' => 124,
    'PAYMENTREQUEST_0_AMT' => 10000,
    'PAYMENTREQUEST_0_CURRENCYCODE' => 'JPY',
    'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
    'cancelUrl' => 'キャンセル時の遷移先を指定',
    'returnUrl' => '成功時の遷移先を指定',
    'PAYMENTREQUEST_0_SHIPTONAME' => '田中太郎',
    'PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE' => '日本'
);

$curl = curl_init("https://api-3t.sandbox.paypal.com/nvp");
curl_setopt($curl, CURLOPT_POST, TRUE);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($POST_DATA));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

$output = curl_exec($curl);
$token = substr($output, 6, 22); // Token取得

$redirect_url = "https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=" . $token;
header("Location: " . $redirect_url); // 取得したTokenをセットし、PaｙPalへリダイレクト

return;


//コールバック

$url = $_SERVER['HTTP_REFERER'];
$url = parse_url($url);
if (strpos($url['host'], 'paypal.com') === false) {
    return; // paypal以外のリダイレクト
}

// paypalからのリダイレクト時のクエリパラメータより、Tokenなどを取得
$token = $_GET[token];
$payer_id = $_GET[PayerID];

$POST_DATA = array(
    'USER' => 'ユーザ名',
    'PWD' => 'パスワード',
    'SIGNATURE' => 'APIトークン',
    'METHOD' => 'DoExpressCheckoutPayment',
    'VERSION' => 124,
    'PAYMENTREQUEST_0_PAYMENTACTION' => 'Sale',
    'PAYMENTREQUEST_0_AMT' => 10000,
    'PAYMENTREQUEST_0_CURRENCYCODE' => 'JPY',
    'TOKEN' => $token,
    'PAYERID' => $payer_id
);
$curl = curl_init("https://api-3t.sandbox.paypal.com/nvp");
curl_setopt($curl, CURLOPT_POST, TRUE);
curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($POST_DATA));
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);

$output = curl_exec($curl);
$arr = explode('&', $output);

$txn_id = str_replace("PAYMENTINFO_0_TRANSACTIONID=", "", $arr[9]);
$txn_id = str_replace("&=", "", $txn_id); // トランザクションID

$payment_status = str_replace("PAYMENTINFO_0_PAYMENTSTATUS=", "", $arr[17]);
$payment_status = str_replace("&=", "", $payment_status); // ステータス

$error_code = str_replace("PAYMENTINFO_0_ERRORCODE=", "", $arr[23]);
$error_code = str_replace("&=", "", $error_code); // エラーコード

// 以下、後続処理続行