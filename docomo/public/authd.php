<?php
session_start();

require_once("../lib/Docomo.php");

use Docomo\Docomo;

$docomo = new Docomo();

try {
        $token = $docomo->checkResponseAndGetToken();

        // サンプルソースでは取得したトークン、取得した利用者情報（Json形式）を表示します
        echo "Token: <br />";
        print_r($token);
        echo "<br />";

        $userDetails = $docomo->getUserDetails($token);

        $userInfo = (array)json_decode($userDetails);

        if(isset($userInfo['family_name']) && !is_null($userInfo['family_name'])){
            printf('Hello %s!<br />', $userInfo['family_name']);
        }
        echo "Result: <br/>";
        echo $userDetails;
        echo "<br />";
        echo '<a href="refreshtoken.php?refresh_token='. $token->refreshToken .'">Go to refresh token.</a><br />';
        echo '<a href="index.php">Go to Top page.</a>';
} catch (Exception $e) {
    // エラー情報の出力
    echo $e->getErrorCode();
    echo $e->getErrorMessage();
    $exception = $e->getPrevious();
    if (isset($exception)) {
        echo "<br/>";
        echo implode("<br />\n", exception_to_array($e));
    }
    echo "<br/>";
    echo $e->getTraceAsString();
}
function exception_to_array(Exception $e) {
    while ($e = $e->getPrevious()) {
        $errors[] = $e->getMessage();
    }
    return array_reverse($errors);
}
?>
