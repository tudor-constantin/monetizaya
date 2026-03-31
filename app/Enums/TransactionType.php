<?php

declare(strict_types=1);

namespace App\Enums;

enum TransactionType: string
{
    case SUBSCRIPTION = 'subscription';
    case ONE_TIME = 'one_time';
    case REFUND = 'refund';
}
