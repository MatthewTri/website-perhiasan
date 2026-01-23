<?php

namespace App\Http\Requests;

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
            'name' => 'required|max:255',
            'slug' => [
                'required',
                Rule::unique('products', 'slug')->ignore($this->product->id)
            ],
            'description' => 'nullable|string',
            'price_idr' => 'required|numeric|min:0',
            'price_usd' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',

            'colors' => 'nullable|array',
            'colors.*.id' => 'nullable|exists:product_colors,id',
            'colors.*.name' => 'required_with:colors|string|max:100',
            'colors.*.hex_code' => 'required_with:colors|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'deleted_colors' => 'nullable|string',

            'sizes' => 'nullable|array',
            'sizes.*.id' => 'nullable|exists:product_sizes,id',
            'sizes.*.size_name' => 'required_with:sizes|string|max:50',

            'specifications' => 'nullable|array',
            'specifications.*.id' => 'nullable|exists:product_specifications,id',
            'specifications.*.section_title' => 'required_with:specifications|string|max:255',
            'specifications.*.items' => 'required_with:specifications|array|min:1',
            'specifications.*.items.*' => 'required|string|max:500',
            'deleted_specifications' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Images must be jpeg, png, jpg, or gif.',
            'images.*.max' => 'Each image must be less than 2MB.'
        ];
    }
}
