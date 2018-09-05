<?php
session_start();

require_once("../lib/Docomo.php");

use Docomo\Docomo;

$docomo = new Docomo();

$authUrl = $docomo->getAuthorizationUrl();
echo "verificationKey = ".$docomo->getVerificationKey()."<br/>";

header("HTTP/1.1 302 Moved Temporary");
header("Location: ".$authUrl);

?>
