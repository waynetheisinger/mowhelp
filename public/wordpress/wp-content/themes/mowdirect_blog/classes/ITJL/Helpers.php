<?php

namespace ITJL_BLOG;

Helpers::start();

class Helpers {

    public static $debug_mode;

    public static function start() {
        self::$debug_mode = ITJL_BLOG_DEBUG_MODE;
    }

    public static function disable_debug_mode() {
        self::$debug_mode = false;
    }

    public static function enable_debug_mode() {
        self::$debug_mode = true;
    }

    public static function get_error_response($message, $data = []) {
        return ['error' => true, 'message' => $message, 'data' => $data];
    }

    public static function get_sucess_response($message, $data = []) {
        return ['error' => false, 'message' => $message, 'data' => $data];
    }

    public static function get_api_error_response($type, $message, $data = array()) {
        $function_name = debug_backtrace()[1]['function'];
        if ($type == '') {
            $code = $function_name . '_error';
        } else {
            $code = $type . '_error';
        }

        $result = array(
            'code' => $code,
            'data' => isset($data) ? $data : '',
            'message' => $message
        );
        return $result;
    }

    public static function get_api_success_response($type, $message, $data = array()) {
        $function_name = debug_backtrace()[1]['function'];
        if ($type == '') {
            $code = $function_name . '_success';
        } else {
            $code = $type . '_success';
        }


        $result = array(
            'code' => $code,
            'data' => isset($data) ? $data : '',
            'message' => $message
        );
        return $result;
    }

    public static function print_object($object, $should_i_die = false) {
        if (self::$debug_mode === false) {
            return;
        }
        echo '<pre>';
        print_r($object);
        echo '</pre>';
        if ($should_i_die === true) {
            die('<br/><br/>Stopping Execution after printing the object.....');
        }
    }

    public static function dump_object($object, $should_i_die = false) {
        if (self::$debug_mode === false) {
            return;
        }

        echo '<pre>';
        var_dump($object);
        echo '</pre>';
        if ($should_i_die === true) {
            die('<br/><br/>Stopping Execution after dumpping the object.....');
        }
    }

    public static function stack_trace() {

        if (self::$debug_mode === false) {
            return;
        }

        echo '<pre>';
        print_r(debug_backtrace());
        echo '</pre>';
        die('<br/><br/>Stopping Execution after printing the object.....');
    }

    // Register Custom Post Type
    public static function register_custom_post_type($name, $slug, $supports = []) {

        if (empty($name) || empty($slug)) {
            return false;
        }

        if (empty($supports)) {
            $supports = array('title', 'editor', 'excerpt', 'thumbnail', 'page-attributes');
        }

        $labels = array(
            'name' => __($name, THEME_NAME),
            'singular_name' => __($name, THEME_NAME),
            'menu_name' => __($name, THEME_NAME),
            'parent_item_colon' => __('Parent Item:', THEME_NAME),
            'all_items' => __('All ' . $name, THEME_NAME),
            'view_item' => __('View ' . $name, THEME_NAME),
            'add_new_item' => __('Add New ' . $name, THEME_NAME),
            'add_new' => __('Add New', THEME_NAME),
            'edit_item' => __('Edit ' . $name, THEME_NAME),
            'update_item' => __('Update ' . $name, THEME_NAME),
            'search_items' => __('Search ' . $name, THEME_NAME),
            'not_found' => __($name . ' Not found', THEME_NAME),
            'not_found_in_trash' => __($name . ' Not found in Trash', THEME_NAME),
        );
        $args = array(
            'label' => __($name, THEME_NAME),
            'description' => __('Description', THEME_NAME),
            'labels' => $labels,
            'supports' => $supports,
            'hierarchical' => true,
            'public' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'show_in_admin_bar' => true,
            'menu_position' => 8,
            'can_export' => true,
            'has_archive' => true,
            'exclude_from_search' => false,
            'publicly_queryable' => true,
            'capability_type' => 'post',
        );
        add_theme_support('post-thumbnails');
        register_post_type($slug, $args);
        return true;
    }

    public static function get_countries_options($selected_country = "") {

        $options = '';
        $selected = '';
        $countries = array
            (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );

        foreach ($countries as $code => $country_name) {
            if ($selected_country === $code) {
                $selected = 'selected="selected"';
                $options .= '<option value="' . $code . '" ' . $selected . ' >' . $country_name . '</option>';
            } else {
                $options .= '<option value="' . $code . '" >' . $country_name . '</option>';
            }
        }
        return $options;
    }

    public static function check_get_current_user_roles($role) {
        $roles = self::get_current_user_roles();
        
        if(in_array($role, $roles)){
            return true;
        } else {
            return false;
        }
    }
    
    public static function get_current_user_roles() {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $roles = (array) $user->roles;
            return $roles; // This returns an array
            // Use this to return a single value
            // return $roles[0];
        } else {
            return array();
        }
    }

}
