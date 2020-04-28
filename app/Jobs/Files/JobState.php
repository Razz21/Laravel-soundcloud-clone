<?php

namespace App\Jobs\Files;

use InvalidArgumentException;

class JobState
{
    const PENDING = 'pending';
    const PROCESSING = "processing";
    const SUCCESS = "success";
    const FAIL = "fail";
    const RETRY = 'retry';
    const DECLINED = 'declined';

    protected static $variants
    = [
        self::PENDING => 'pending',
        self::FAIL => 'fail',
        self::PROCESSING => 'processing',
        self::SUCCESS => 'success',
        self::RETRY => 'retry',
        self::DECLINED => 'retry',
    ];

    private $value;

    public function __construct(string $value)
    {
        if (!isset(self::$variants[$value])) {
            throw new InvalidArgumentException();
        }
        $this->value = $value;
    }
    public function value()
    {
        return $this->value;
    }

    public function name()
    {
        return self::$variants[$this->value];
    }
    public function isPending(): bool
    {
        return $this->value == self::PENDING;
    }

    public function isActive(): bool
    {
        return $this->value == self::PROCESSING;
    }

    public function isSuccess(): bool
    {
        return $this->value == self::SUCCESS;
    }

    public function isWaitRetry(): bool
    {
        return $this->value == self::RETRY;
    }

    public function isFinished(): bool
    {
        return in_array($this->value, [self::SUCCESS, self::FAIL]);
    }

    public function isDeclined(): bool
    {
        return $this->value == self::DECLINED;
    }

    public function __toString()
    {
        return $this->value;
    }
}
