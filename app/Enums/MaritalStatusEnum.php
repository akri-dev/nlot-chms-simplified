<?php

namespace App\Enums;

enum MaritalStatusEnum: string
{
    case SINGLE = 'Single';
    case MARRIED = 'Married';
    case WIDOWED = 'Widowed';
    case DIVORCED_SEPARATED_ANNULLED = 'Divorced/Separated/Annulled';
}