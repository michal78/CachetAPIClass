<?php
## Just proof of concept
require_once 'cachet.class.php';

$token = 'TheRandomTokenFromYourProfile';
$host = 'your.cachethost.tld';

$cachet = new cachet($host, $token, false); // Last parameter is debug true/false
$components = $cachet->components(); // Components as an PHP object
