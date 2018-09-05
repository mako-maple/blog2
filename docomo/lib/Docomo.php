<?php

namespace Docomo;

use League\OAuth2\Client\Provider\Docomo as Dcm;

require ("vendor/autoload.php");
class Docomo extends \League\OAuth2\Client\Provider\Docomo {
    public function __construct($target = null) {
        if($target == null){
            ///$file = '/rp/conf/rplib.conf';
            $file = '../conf/rplib.conf';
        } else {
            $file = $target;
        }
        Dcm::__construct ($file);
    }
    public function getAuthorizationUrl($options = []) {
        $authUrl = parent::getAuthorizationUrl ( $options );

        $_SESSION ['oauth2state'] = $this->state . ";" . $this->nonce;

        return $authUrl;
    }
    public function getVerificationKey() {
        if (isset ( $_SESSION ['oauth2state'] )) {
            return $_SESSION ['oauth2state'];
        }
        return null;
    }
    public function checkResponseAndGetToken($target = []) {
        $get_error = isset ( $_GET ['error'] ) ? $_GET ['error'] : null;
        $get_state = isset ( $_GET ['state'] ) ? $_GET ['state'] : null;

        $verificationKey = $this->getVerificationKey ();

        $target = array (
                'error' => $get_error,
                'state' => $get_state,
                'session' => $verificationKey
        );

        return parent::checkResponseAndGetToken ( $target );
    }
    public function refreshToken($r_token = null) {
        $refresh_token = [
                'refresh_token' => $r_token
        ];
        $grant = new \League\OAuth2\Client\Grant\RefreshToken ();

        return $this->refleshToken ( $grant, $refresh_token );
    }
}
