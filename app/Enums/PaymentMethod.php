<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = "cash";
    case GCASH = "gcash";
    case MAYA = "maya";
    case BANK_TRANSFER = "bank_transfer";
    case OTHER = "other";
}
