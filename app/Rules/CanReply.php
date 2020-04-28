<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CanReply implements Rule
{
    protected $parameters;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->parameters = func_get_args();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return \DB::table($this->parameters[0])->where($this->parameters[1], $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Can not reply for this comment.';
    }
}
