<?php

use App\Helpers\CouponGenerator;

include 'App/Helpers/CouponGenerator.php';

$generator = new CouponGenerator();
$tokenLength = 16;
for ($i = 0; $i < 10; $i++) {
$voucherNum = $generator->generate($tokenLength);
echo $voucherNum.PHP_EOL;

}

