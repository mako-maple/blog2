<?php
session_start();

require_once("../lib/Docomo.php");

use Docomo\Docomo;

$docomo = new Docomo();

try {
    $refreshToken = $_GET['refresh_token'];
    $token = $docomo->refreshToken($refreshToken);

    print_r($token);
    echo "<br />";

    echo '<a href="index.php">Go to Top page.</a>';
} catch (Exception $e) {
//    echo $e;
    echo $e->getErrorCode();
    echo $e->getErrorMessage();
}

?>
