<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

use ExpressionEngine\Library\CP\EntryManager\ColumnInterface;

class Tel_ft extends EE_Fieldtype implements ColumnInterface
{
    /**
     * @var string[]
     */
    public $info = [
        'name' => 'Telephone',
        'version' => '1.0.0',
    ];

    public function __construct()
    {
        ee()->lang->loadfile('tel');
        ee()->load->helper('array');
        parent::__construct();
    }

    /**
     * @param $data
     * @return string|true
     */
    public function validate($data)
    {
        ee()->lang->loadfile('fieldtypes');
        if ($data == '') {
            return true;
        }

        $data = preg_replace('[\D]', '', $data);
        $data = preg_replace('/[^0-9]/', '', $data);

        if (strlen($data) >= 10) {
            return true;
        }

        return lang('invalid_error_message');
    }

    public function install()
    {
        return [];
    }

    /**
     * @return string
     */
    public function display_global_settings()
    {
        $val = array_merge($this->settings, $_POST);

        $form = '';

        return $form;
    }

    public function save_global_settings()
    {
        return array_merge($this->settings, $_POST);
    }

    public function display_settings($data): array
    {
        $settings = [
            [
                'title' => 'placeholder',
                'desc' => 'placeholder_instructions',
                'fields' => [
                    'placeholder' => [
                        'name' => 'placeholder',
                        'type' => 'text',
                        'value' => element('placeholder', $data),
                    ],
                ],
            ],
        ];

        return ['field_options_tel' => [
            'label' => 'field_options',
            'group' => 'tel',
            'settings' => $settings,
        ],
        ];
    }

    /**
     * @param $data
     * @return array
     */
    public function save_settings($data)
    {
        return [
            'placeholder' => element('placeholder', $data)
        ];
    }

    /**
     * @param $data
     * @return string
     */
    public function display_field($data)
    {
        $field = array(
            'name' => $this->field_name,
            'value' => $data,
            'placeholder' => $this->settings['placeholder']
        );

        return form_input($field);
        //@todo we have to use an input field or else validation gets stuck and we cannot submit again
        //discuss with PT when able
        //$defaults = ['type' => 'tel', 'name' => $this->field_name, 'value' => $data];
        //return "<input " . _parse_form_attributes($data, $defaults) . " placeholder='". $this->settings['placeholder'] ."' />";
    }

    /**
     * @param $data
     * @param $params
     * @param $tagdata
     * @return array|string|string[]|null
     */
    public function replace_e164($data, $params = [], $tagdata = false)
    {
        return $this->replace_format($data, $params, $tagdata);
    }

    /**
     * @param $data
     * @param $params
     * @param $tagdata
     * @return string
     */
    public function replace_tel($data, $params = [], $tagdata = false)
    {
        $phone_number = preg_replace('[\D]', '', $data);
        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);
        return '<a href="tel:' . $phone_number . '">' . $data . '</a>';
    }

    /**
     * @param $data
     * @param $params
     * @param $tagdata
     * @return array|string|string[]|null
     */
    public function replace_format($data, $params = [], $tagdata = false)
    {
        $phone_number = $data;
        $phone_number = htmlspecialchars($phone_number);
        $phone_number = preg_replace('[\D]', '', $phone_number);

        $phone_number = preg_replace('/[^0-9]/', '', $phone_number);

        if (strlen($phone_number) > 10) {
            $country_code = substr($phone_number, 0, strlen($phone_number) - 10);
            $area_code = substr($phone_number, -10, 3);
            $next_three = substr($phone_number, -7, 3);
            $last_four = substr($phone_number, -4, 4);

            $phone_number = '+' . $country_code . ' (' . $area_code . ') ' . $next_three . '-' . $last_four;
        } elseif (strlen($phone_number) == 10) {
            $area_code = substr($phone_number, 0, 3);
            $next_three = substr($phone_number, 3, 3);
            $last_four = substr($phone_number, 6, 4);

            $phone_number = '(' . $area_code . ') ' . $next_three . '-' . $last_four;
        } elseif (strlen($phone_number) == 7) {
            $next_three = substr($phone_number, 0, 3);
            $last_four = substr($phone_number, 3, 4);

            $phone_number = $next_three . '-' . $last_four;
        }

        return $phone_number;
    }

    /**
     * @param $data
     * @return array|string|string[]|null
     */
    public function save($data)
    {
        $data = preg_replace('[\D]', '', $data);
        $data = preg_replace('/[^0-9]/', '', $data);
        return $data;
    }

    public function accepts_content_type($name)
    {
        return true;
    }
}
