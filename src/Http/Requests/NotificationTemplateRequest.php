<?php

namespace Bagoesz21\LaravelNotification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required',
            'title' => 'required',
            'message' => '',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'laravel-notification::notification.template.name',
            'title' => 'laravel-notification::notification.template.title',
            'message' => 'laravel-notification::notification.template.message',
        ];
    }
}
