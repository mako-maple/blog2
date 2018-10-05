<?php
// Please set Docomo OpenID Connect parameters.
$openid = array(
'client_id' => 'sample_client',
'client_secret' => '12345678901234567890123456789012',
'redirect_uri' => 'https://your.site.domain/path/to/authed',
'scopes' => ['openid', 'offline_access','suid','uid','guid', 'profile1', 'delivery1','accountid','accountid_n','sbscrbsts','sbscrbsts_n','dpoint_card','dpoint_card_n','dpoint_card_all','dpoint_card_all_n','dpoint_number','dpoint_number_n'],
);

// If your rp site exists inside proxy, set value of 'proxy' to 'true' and set proxy options.
// And if your proxy do not set userid/password, comment out userid/password options.
$proxy = array(
'proxy' => false,
'protocol' => '',
'host' => '',
'port' => '',
'userid' => '',
'password' => '',
);

$api = array(
'auth_uri' => 'https://id.smt.docomo.ne.jp/cgi8/oidc/authorize',
'token_uri' => 'https://conf.uw.docomo.ne.jp/token',
'info_uri' => 'https://conf.uw.docomo.ne.jp/userinfo',
);

// Please don't touch this code.
if(!$proxy['proxy']){
$proxy = [];
}
