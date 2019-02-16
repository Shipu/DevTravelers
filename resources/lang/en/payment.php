<?php

use App\Enums\PaymentChannel;
use App\Enums\PaymentPurpose;
use App\Enums\PaymentStatus;

return [
    'channel' => [
        PaymentChannel::DIRECT_TO_HOST => 'Direct to Host',
        PaymentChannel::BKASH          => 'Bkash',
        PaymentChannel::ROKET          => 'Roket',
    ],
    'statuses' => [
        PaymentStatus::CREATE    => 'Create',
        PaymentStatus::CONFIRMED => 'Confirmed',
    ],
    'purpose' => [
        PaymentPurpose::EVENT => 'Event',
        PaymentPurpose::DONATION => 'Donation',
        PaymentPurpose::EXPENSE => 'Expense',
        PaymentPurpose::OTHER => 'Other',
    ]
];

