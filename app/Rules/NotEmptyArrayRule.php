<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * 数组不能包含false, 0, null等空值
 */
class NotEmptyArrayRule extends BaseRule {
    /**
     * 数组不能包含false, 0, null等空值
     *
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value) {
        if (!is_array($value) || empty($value)) {
            return false;
        }

        foreach ($value as $item) {
            if (empty($item)) {
                return  false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message() {
        return ':attribute ' .__('lang.no_null');
    }
}
