<?php
include "../lib/Docomo.php";

use Docomo\Docomo;

$docomo = new Docomo();
?>
<html>
<head>
<title>OAuth2 Sample Client</title>
</head>
<body>
<p><a href="goauthorize.php">認証・認可要求へ</a></p>
<p>
<?php
  echo $docomo->getAuthorizationUrl();
?>
</p>
</body>
</html>
