<?php
header("Content-type: application/xrds+xml;charset=UTF-8");
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
?>
<xrds:XRDS
xmlns:xrds="xri://$xrds"
xmlns:openid="http://openid.net/xmlns/1.0"
xmlns="xri://$xrd*($v*2.0)">
<XRD>
<Service priority="0">
<Type>http://specs.openid.net/auth/2.0/return_to</Type>
<URI>http://jtv.cmnow.net:22280/rp/test-login.php</URI>
</Service>
</XRD>
</xrds:XRDS>
※ XRD
