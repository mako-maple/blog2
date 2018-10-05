<?php

header("X-XRDS-Location: http://jtv.cmnow.net/rp/test-xrds.php");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>index page.</title>
</head>
<body>
<?php
// TODO 環境に合わせてactionの値を修正してください

?>
<form action="http://jtv.cmnow.net:22280/rp/test-login.php" method="post">
<div>
ログイン
<input type="hidden" name="loginop" value="docomo" /><br />
<input type="submit" value="ログイン" /><br />
<?php
if (isset($errMsg)) {
    print "<div>エラー：$errMsg</div>";
}
?>
</div>
</form>
</html>
</body>
