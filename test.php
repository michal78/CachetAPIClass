<?php
## Just proof of concept
require_once 'cachet.class.php';
echo '<pre>';

$token = 'TheRandomTokenFromYourProfile';
$host = 'your.cachethost.tld';

$cachet = new cachet($host, $token, false);
$components = $cachet->components();

echo '</pre>';