<?php

namespace Bagoesz21\LaravelNotification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use BenSampo\Enum\Rules\EnumValue;
use Bagoesz21\LaravelNotification\Enums\DeliveryTimeStatus;
use Bagoesz21\LaravelNotification\Helpers\NotifHelper;
use Bagoesz21\LaravelNotification\Helpers\Helper;
use Bagoesz21\LaravelNotification\Config\NotifConfig;

class NotificationRequest extends FormRequest
{
    protected $channelLists = [];
    protected $notifConfig;

    protected $userNotif = [
        1 => [
            'name' => 'Semua User',
            'value' => 1
        ],
        2 => [
            'name' => 'Spesifik User',
            'value' => 2
        ],
        3 => [
            'name' => 'Kriteria User',
            'value' => 3
        ]
    ];

    protected $userCriteria = [
        [
            'name' => 'status user',
            'field' => 'userStatus',
        ],
        [
            'name' => 'tipe user',
            'field' => 'userType',
        ],
        [
            'name' => 'super admin',
            'field' => 'superAdmin',
        ],
        [
            'name' => 'user terverifikasi',
            'field' => 'verifiedUser',
        ],
        [
            'name' => 'hak akses',
            'field' => 'userRole',
        ]
    ];

    public function __construct()
    {
        $this->notifConfig = NotifConfig::make();
        $this->channelLists = Arr::pluck($this->notifConfig->get('channels'), 'value');
    }

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
        $rules = [];

        $notifRules = [
            'title' => 'required',
            'level' => '',
            'message' => [],
            'image' => '',
            'external_url' => '',
            'data' => '',
            // 'type' => 'required|in:' . $this->allowedNotifTypes()
        ];

        $userRules = [
            'notif' => 'required|in:1,2,3', //1= all, 2 = specific, 3 = criteria
            'lists' => ['required_if:users.notif,2', 'array'],
            'criteria' => ['exclude_unless:users.notif,3'] //dont validate if users.notif <> 3 (criteria)
        ];

        //if choose user notif criteria, ensure choose one filter criteria data / dont empty
        $userCriteriaRules = [];
        $userCriteriaFields = Arr::pluck($this->userCriteria, 'field');
        foreach($userCriteriaFields as $field){
            $userCriteriaRules[$field] = [
                'array',
                $this->requiredWithout($userCriteriaFields, $field, 'users.criteria')
            ];
        }

        $userCriteriaRules = Helper::appendArrayKey($userCriteriaRules, 'criteria');

        $channelRules = [
            'channels' => 'required|array|in:' . implode(",", $this->channelLists)
        ];
        $deliveryRules = [
            'status' => ['required', 'integer', new EnumValue(DeliveryTimeStatus::class, false)],
            'delivery_at' => ['exclude_if:delivery.status,0', 'required', 'date']
        ];

        $rules = array_merge(
            $rules,
            Helper::appendArrayKey($notifRules, 'notif'),
            Helper::appendArrayKey($userRules, 'users'),
            Helper::appendArrayKey($userCriteriaRules, 'users'),
            $channelRules,
            Helper::appendArrayKey($deliveryRules, 'delivery'),

        );
        //dd($rules);

        //create/store
        if (request()->method() == 'POST' || request()->is('*/create')) {
        }

        //edit/update (Patch/Put)
        if (request()->method() == 'PATCH') {
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'users.lists.required_if' => 'Bidang isian :attribute wajib diisi bila :other adalah spesifik user.',
            'users.criteria.required_if' => 'Bidang isian :attribute wajib diisi bila :other adalah kriteria user.'
        ];
    }

    public function attributes()
    {
        $attrs  = [];

        $notifAttrs = [
            'title' => 'judul notifikasi',
            'message' => 'pesan notifikasi',
            'image' => 'gambar notifikasi',
            'external_url' => 'url eksternal',
            'data' => 'data lainnya notifikasi',
        ];

        $userAttrs = [
            'notif' => 'kirim notifikasi user',
            'lists' => 'list notif ke user',
            'criteria' => 'kriteria user'
        ];

        $userCriteriaAttrs = appendArrayKey(
            Arr::pluck($this->userCriteria, 'name', 'field'),
        'criteria');

        $channelAttrs = [
            'channels' => 'kirim notifikasi via'
        ];

        $deliveryAttrs = [
            'status' => 'jadwal kirim notifikasi',
            'delivery_at' => 'waktu kirim notifikasi'
        ];

        $attrs = array_merge(
            $attrs,
            appendArrayKey($notifAttrs, 'notif'),
            appendArrayKey($userAttrs, 'users'),
            appendArrayKey($userCriteriaAttrs, 'users'),
            $channelAttrs,
            appendArrayKey($deliveryAttrs, 'delivery'),

        );
        //dd($attrs);
        return $attrs;
    }

    private function requiredWithout($fields, $exceptField, $arrayDot){
        $result = [];
        foreach($fields as $field){
            if($exceptField === $field){
                continue;
            }
            $result[] = $arrayDot . "." . $field;
        }
        if(empty($result))return;

        return "required_without_all:" . implode(",", $result);
    }

    private function allowedNotifTypes(){

        $listNotificationType = NotifHelper::getNotifModelClass()::listNotificationType();

        $filtered = Arr::where($listNotificationType, function ($value, $key) {
            return in_array($value['class'], ['GeneralNotif']);
        });
        return implode(",", Arr::pluck($filtered, "class"));
    }
}
