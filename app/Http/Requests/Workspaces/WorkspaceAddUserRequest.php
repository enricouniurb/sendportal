<?php

declare(strict_types=1);

namespace App\Http\Requests\Workspaces;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WorkspaceAddUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'max:255','regex:/^[A-Za-z0-9\.]*@(uniurb)[.](it)$/'],
            'role' => ['required', Rule::in(['member', 'owner'])]
        ];
    }
}
