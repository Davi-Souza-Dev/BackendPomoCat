<?php

namespace App\Enums;

enum FocusSessionStatus: string
{
    case COMPLETED = 'completed';
    case INTERRUPTED = 'interrupted';
}
