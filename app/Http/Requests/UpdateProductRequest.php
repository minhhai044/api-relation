<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pro_name'       => ['required', Rule::unique(Product::class)->ignore($this->route('product'))],
            'pro_image'      => 'nullable|image|max:2048',
            'pro_price'      => 'required',
            'pro_description'=> 'nullable',
            'pro_quantity'   => 'required|integer',
            'pro_active'     => ['required', Rule::in([0, 1])],
            'category_id'    => 'required',

            'gallaries'     => 'nullable|array',
            'gallaries.*'   => 'nullable|image|max:2048',

            'tags'            =>  'required|array',
            'tags.*'          =>  'required|integer',
        ];
    }
}
