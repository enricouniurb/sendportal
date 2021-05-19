<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Sendportal\Base\Facades\Sendportal;
use Sendportal\Base\Http\Requests\SubscriberRequest as BaseSubscriberRequest;


/**
 * @property-read string $subscriber
 */
class SubscriberRequest extends BaseSubscriberRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('sendportal_subscribers', 'email')
                    ->ignore($this->subscriber, 'id')
                    ->where(static function (Builder $query) {
                        $query->where('workspace_id', Sendportal::currentWorkspaceId());
                    })
            ],
            'first_name' => [
                'max:255',
            ],
            'last_name' => [
                'max:255',
            ],
            'text1' => [
                'max:255',
            ],
            'text2' => [
                'max:255',
            ],
            'tags' => [
                'nullable',
                'array',
            ],
        ];
    }
}
