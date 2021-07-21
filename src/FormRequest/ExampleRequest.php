<?php

namespace Bulbadev\Autodoc\FormRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @_description
 * Method shows all user's deals.
 *
 * @filters.type Filter by type deal
 * @filters.amount Filter by amount
 * @filters.price Filter by price
 * @filters.created_at Filter by created date
 * @filters.product_id Filter by product id
 */
class ExampleRequest extends FormRequest
{
    public function rules()
    {
        return [
            'filters.type'       => Rule::in(['buy', 'sell']),
            'filters.amount'     => ['numeric'],
            'filters.price'      => ['numeric'],
            'filters.created_at' => ['date_format:Y-m-d'],
            'filters.product_id' => ['integer'],
        ];
    }
}