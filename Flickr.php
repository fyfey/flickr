<?php

class Flickr
{
    const KEY = '';
    const SECRET = '';

    public function request($baseURI, $method, $params)
    {
        $sig = $this->buildSignature($baseURI, $method,  $params);

        echo $sig;
    }

    protected function buildSignature($baseURI, $method, $params)
    {
        $r = array();
        ksort($params);
        foreach ($params as $key => $value) {
            $r[] = sprintf('%s=%s', $key, rawurlencode($value));
        }
        $compositeKey = rawurlencode(Flickr::KEY) . '&' . rawurlencode(Flickr::SECRET);
        $body = $method . '&' . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));

        return base64_encode(hash_hmac('sha1', $body, $compositeKey, true));
    }
}

$flickr = new Flickr();

$flickr->request('https://www.flickr.com/services/oauth/request_token', 'GET', array(
    'oauth_nonce' => time(),
    'oauth_timestamp' => time(),
    'oauth_consumer_key' => Flickr::KEY,
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_version' => 1.0,
    'oauth_callback' => 'http://www.example.com'
));

