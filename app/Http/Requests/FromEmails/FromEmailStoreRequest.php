<?php

declare(strict_types=1);

namespace App\Http\Requests\FromEmails;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sendportal\Base\Facades\Sendportal;

class FromEmailStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'from_name' => ['required','max:255',],
            'from_email' => ['required', 'email', 'max:255','regex:/^[A-Za-z0-9\.]*@(uniurb)[.](it)$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => __('The tag name must be unique.'),
        ];
    }
}
