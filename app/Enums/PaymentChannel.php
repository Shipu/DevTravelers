<?php

namespace App\Enums;

interface PaymentChannel
{
    const DIRECT_TO_HOST = 1;
    const BKASH = 2;
    const ROKET = 3;
    const CASH = 4;
}
