<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidCardsRule implements Rule
{
    protected $availableCards = [];
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->availableCards = ['2','3','4','5','6','7','8','9','10','J','Q','K','A'];
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
        $playerCards = explode(" ", $value);
        
        foreach($playerCards as $card) {
            if(!in_array($card, $this->availableCards)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid card entered. Valid cards are: ' . implode(",", $this->availableCards);
    }
}
