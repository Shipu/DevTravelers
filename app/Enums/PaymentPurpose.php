<?php

namespace App\Enums;

interface PaymentPurpose
{
    const EVENT    = 1;
    const DONATION = 2;
    const EXPENSE  = 3;
    const OTHER    = 4;
}
