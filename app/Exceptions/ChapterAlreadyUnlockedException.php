<?php

namespace App\Exceptions;

use Exception;

class ChapterAlreadyUnlockedException extends Exception
{
    protected $message = 'Chapter already unlocked';
}
