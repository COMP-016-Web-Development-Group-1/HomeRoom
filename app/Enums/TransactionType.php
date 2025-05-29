<?php

namespace App\Enums;

enum TransactionType: string
{
    case RENT = 'rent';
    case DEPOSIT = 'deposit';
    case FEE = 'fee';
    case REFUND = 'refund';
    case OTHER = 'other';
}
