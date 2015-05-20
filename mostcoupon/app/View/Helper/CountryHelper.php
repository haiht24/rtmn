<?php

App::uses('AppHelper', 'View/Helper');

class CountryHelper extends AppHelper {

    public $helpers = ['Form'];
    private $defaultCountry = 'de';
    private $countryCode = null;
    private $_currencies = [
        'EUR' => '€',
        'USD' => '$'
    ];
    private $_locales = [
        'en' => 'en_GB',
        'de' => 'de_DE',
        'fr' => 'fr_FR',
        'it' => 'it_IT',
        'es' => 'es_ES',
        'nl' => 'nl_NL',
        'bg' => 'bg_BG',
        'zh' => 'ch_CN',
        'hr' => 'hr_HR',
        'cs' => 'cs_CZ',
        'da' => 'da_DK',
        'et' => 'et_EE',
        'el' => 'el_GR',
        'hu' => 'hu_HU',
        'in' => 'in_ID',
        'iw' => 'iw_IL',
        'ja' => 'ja_JP',
        'ko' => 'ko_KR',
        'lv' => 'lv_LV',
        'lt' => 'lt_LT',
        'mk' => 'mk_MK',
        'ms' => 'ms_MY',
        'no' => 'no_NO',
        'pl' => 'pl_PL',
        'pt' => 'pt_PT',
        'ro' => 'ro_RO',
        'ru' => 'ru_RU',
        'ar' => 'ar_SA',
        'sk' => 'sk_SK',
        'sv' => 'sv_SE',
        'th' => 'th_TH',
        'tr' => 'tr_TR',
        'vi' => 'vi_VN'
    ];
    private $_supportedLanguages = [
        'en' => 'English',
        'de' => 'Deutsch',
        'fr' => 'Français',
        'it' => 'Italiano',
        'es' => 'Español',
        'nl' => 'Nederlandse',
        'bg' => 'България',
        'zh' => '中国',
        'hr' => 'Hrvatska',
        'cs' => 'Čeština',
        'da' => 'Dansk',
        'et' => 'Eesti',
        'el' => 'Ελλάδα',
        'hu' => 'Magyarország',
        'in' => 'Bahasa Indonesia',
        'iw' => 'עִבְרִית ʿIvrit',
        'ja' => '日本語',
        'ko' => '한국어, 조선말',
        'lv' => 'Latviešu valoda',
        'lt' => 'Lietuvių kalba',
        'mk' => 'Македонски јазик',
        'ms' => 'بهاس ملايو',
        'no' => 'Norsk',
        'pl' => 'Język polski',
        'pt' => 'Português',
        'ro' => 'Română',
        'ru' => 'Pусский язык',
        'ar' => 'العربية',
        'sk' => 'Slovenčina',
        'sv' => 'Svenska',
        'th' => 'ภาษาไทย',
        'tr' => 'Türkçe',
        'vi' => 'Tiếng Việt'
    ];
    private $_countries = [
        'af' => 'Afganistan',
        'al' => 'Albania',
        'dz' => 'Algeria',
        'as' => 'American Samoa',
        'ad' => 'Andorra',
        'ao' => 'Angola',
        'ai' => 'Anguilla',
        'aq' => 'Antarctica',
        'ag' => 'Antigua and Barbuda',
        'ar' => 'Argentina',
        'am' => 'Armenia',
        'aw' => 'Aruba',
        'au' => 'Australia',
        'at' => 'Austria',
        'az' => 'Azerbaijan',
        'bs' => 'Bahamas',
        'bh' => 'Bahrain',
        'bd' => 'Bangladesh',
        'bb' => 'Barbados',
        'by' => 'Belarus',
        'be' => 'Belgium',
        'bz' => 'Belize',
        'bj' => 'Benin',
        'bm' => 'Bermuda',
        'bt' => 'Bhutan',
        'bo' => 'Bolivia',
        'ba' => 'Bosnia and Herzegowina',
        'bw' => 'Botswana',
        'bv' => 'Bouvet Island',
        'br' => 'Brazil',
        'io' => 'British Indian Ocean Territory',
        'bn' => 'Brunei Darussalam',
        'bg' => 'Bulgaria',
        'bf' => 'Burkina Faso',
        'bi' => 'Burundi',
        'kh' => 'Cambodia',
        'cm' => 'Cameroon',
        'ca' => 'Canada',
        'cv' => 'Cape Verde',
        'ky' => 'Cayman Islands',
        'cf' => 'Central African Republic',
        'td' => 'Chad',
        'cl' => 'Chile',
        'cn' => 'China',
        'cx' => 'Christmas Island',
        'cc' => 'Cocos Keeling Islands',
        'co' => 'Colombia',
        'km' => 'Comoros',
        'cg' => 'Congo',
        'cd' => 'Congo, Democratic Republic of the',
        'ck' => 'Cook Islands',
        'cr' => 'Costa Rica',
        'ci' => 'Cote d\'Ivoire',
        'hr' => 'Croatia Hrvatska',
        'cu' => 'Cuba',
        'cy' => 'Cyprus',
        'cz' => 'Czech Republic',
        'dk' => 'Denmark',
        'dj' => 'Djibouti',
        'dm' => 'Dominica',
        'do' => 'Dominican Republic',
        'tp' => 'East Timor',
        'ec' => 'Ecuador',
        'eg' => 'Egypt',
        'sv' => 'El Salvador',
        'gq' => 'Equatorial Guinea',
        'er' => 'Eritrea',
        'ee' => 'Estonia',
        'et' => 'Ethiopia',
        'fk' => 'Falkland Islands Malvinas',
        'fo' => 'Faroe Islands',
        'fj' => 'Fiji',
        'fi' => 'Finland',
        'fr' => 'France',
        'fx' => 'France, Metropolitan',
        'gf' => 'French Guiana',
        'pf' => 'French Polynesia',
        'tf' => 'French Southern Territories',
        'ga' => 'Gabon',
        'gm' => 'Gambia',
        'ge' => 'Georgia',
        'de' => 'Germany',
        'gh' => 'Ghana',
        'gi' => 'Gibraltar',
        'gr' => 'Greece',
        'gl' => 'Greenland',
        'gd' => 'Grenada',
        'gp' => 'Guadeloupe',
        'gu' => 'Guam',
        'gt' => 'Guatemala',
        'gn' => 'Guinea',
        'gw' => 'Guinea-Bissau',
        'gy' => 'Guyana',
        'ht' => 'Haiti',
        'hm' => 'Heard and Mc Donald Islands',
        'va' => 'Holy See (Vatican City State)',
        'hn' => 'Honduras',
        'hk' => 'Hong Kong',
        'hu' => 'Hungary',
        'is' => 'Iceland',
        'in' => 'India',
        'id' => 'Indonesia',
        'ir' => 'Iran, Islamic Republic of',
        'iq' => 'Iraq',
        'ie' => 'Ireland',
        'il' => 'Israel',
        'it' => 'Italy',
        'hm' => 'Jamaica',
        'jp' => 'Japan',
        'jo' => 'Jordan',
        'kz' => 'Kazakhstan',
        'ke' => 'Kenya',
        'ki' => 'Kiribati',
        'kp' => 'Korea, Democratic People\'s Republic of',
        'kr' => 'Korea, Republic of',
        'kw' => 'Kuwait',
        'kg' => 'Kyrgyzstan',
        'la' => 'Lao People\'s Democratic Republic',
        'lv' => 'Latvia',
        'lb' => 'Lebanon',
        'ls' => 'Lesotho',
        'lr' => 'Liberia',
        'ly' => 'Libyan Arab Jamahiriya',
        'li' => 'Liechtenstein',
        'lt' => 'Lithuania',
        'lu' => 'Luxembourg',
        'mo' => 'Macau',
        'mk' => 'Macedonia, The Former Yugoslav Republic of',
        'mg' => 'Madagascar',
        'mw' => 'Malawi',
        'my' => 'Malaysia',
        'mv' => 'Maldives',
        'ml' => 'Mali',
        'mt' => 'Malta',
        'mh' => 'Marshall Islands',
        'mq' => 'Martinique',
        'mr' => 'Mauritania',
        'mu' => 'Mauritius',
        'yt' => 'Mayotte',
        'mx' => 'Mexico',
        'fm' => 'Micronesia, Federated States of',
        'md' => 'Moldova, Republic of',
        'mc' => 'Monaco',
        'mn' => 'Mongolia',
        'ms' => 'Montserrat',
        'ma' => 'Morocco',
        'mz' => 'Mozambique',
        'mm' => 'Myanmar',
        'na' => 'Namibia',
        'nr' => 'Nauru',
        'np' => 'Nepal',
        'nl' => 'Netherlands',
        'an' => 'Netherlands Antilles',
        'nc' => 'New Caledonia',
        'nz' => 'New Zealand',
        'ni' => 'Nicaragua',
        'ne' => 'Niger',
        'ng' => 'Nigeria',
        'nu' => 'Niue',
        'nf' => 'Norfolk Island',
        'mp' => 'Northern Mariana Islands',
        'no' => 'Norway',
        'om' => 'Oman',
        'pk' => 'Pakistan',
        'pw' => 'Palau',
        'pa' => 'Panama',
        'pg' => 'Papua New Guinea',
        'py' => 'Paraguay',
        'pe' => 'Peru',
        'ph' => 'Philippines',
        'pn' => 'Pitcairn',
        'pl' => 'Poland',
        'pt' => 'Portugal',
        'pr' => 'Puerto Rico',
        'qa' => 'Qatar',
        're' => 'Reunion',
        'ro' => 'Romania',
        'ru' => 'Russian Federation',
        'rw' => 'Rwanda',
        'kn' => 'Saint Kitts and Nevis',
        'lc' => 'Saint LUCIA',
        'vc' => 'Saint Vincent and the Grenadines',
        'ws' => 'Samoa',
        'sm' => 'San Marino',
        'st' => 'Sao Tome and Principe',
        'sa' => 'Saudi Arabia',
        'sn' => 'Senegal',
        'sc' => 'Seychelles',
        'sl' => 'Sierra Leone',
        'sg' => 'Singapore',
        'sk' => 'Slovakia (Slovak Republic)',
        'si' => 'Slovenia',
        'sb' => 'Solomon Islands',
        'so' => 'Somalia',
        'za' => 'South Africa',
        'gs' => 'South Georgia and the South Sandwich Islands',
        'es' => 'Spain',
        'lk' => 'Sri Lanka',
        'sh' => 'St. Helena',
        'pm' => 'St. Pierre and Miquelon',
        'sd' => 'Sudan',
        'sr' => 'Suriname',
        'sj' => 'Svalbard and Jan Mayen Islands',
        'sz' => 'Swaziland',
        'se' => 'Sweden',
        'ch' => 'Switzerland',
        'sy' => 'Syrian Arab Republic',
        'tw' => 'Taiwan, Province of China',
        'tj' => 'Tajikistan',
        'tz' => 'Tanzania, United Republic of',
        'th' => 'Thailand',
        'tg' => 'Togo',
        'tk' => 'Tokelau',
        'to' => 'Tonga',
        'tt' => 'Trinidad and Tobago',
        'tn' => 'Tunisia',
        'tr' => 'Turkey',
        'tm' => 'Turkmenistan',
        'tc' => 'Turks and Caicos Islands',
        'tv' => 'Tuvalu',
        'ug' => 'Uganda',
        'ua' => 'Ukraine',
        'ae' => 'United Arab Emirates',
        'gb' => 'United Kingdom',
        'us' => 'United States',
        'um' => 'United States Minor Outlying Islands',
        'uy' => 'Uruguay',
        'uz' => 'Uzbekistan',
        'vu' => 'Vanuatu',
        've' => 'Venezuela',
        'vn' => 'Viet Nam',
        'vg' => 'Virgin Islands (British)',
        'vi' => 'Virgin Islands (U.S.)',
        'wf' => 'Wallis and Futuna Islands',
        'eh' => 'Western Sahara',
        'ye' => 'Yemen',
        'yu' => 'Yugoslavia',
        'zm' => 'Zambia',
        'zw' => 'Zimbabwe'
    ];
    private $_eligibleCountriesForTaxDeduction = ['AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE',
        'FI', 'FR', 'GR', 'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK',
        'SI', 'ES', 'SE', 'UK'];

    public function getSupportedLanguages() {
        return $this->_supportedLanguages;
    }

    public function getLanguageLocale($languageCode) {

        if (isset($this->_locales[$languageCode])) {
            return $this->_locales[$languageCode];
        } else {
            return 'en_GB';
        }
    }

    /**
     * Outputs country list
     */
    public function countrySelect($fieldName, $options = []) {
        $options = array_merge([
            'label' => __('Country', true),
            'default' => $this->defaultCountry,
            'class' => null
                ], $options);
        $selected = $this->getSelected($fieldName);
        if ($selected === null ||
                !array_key_exists($selected, $this->_countries)) {
            if ($this->countryCode === null) {
                $selected = $options['default'];
            } else {
                $selected = $this->countryCode;
            }
        }
        $opts = [];

        //set names as value
        $countries = [];
        foreach ($this->_countries as $i => $v) {
            $countries[strtoupper($i)] = $v;
        }

        $opts['options'] = $countries;
        $opts['selected'] = $selected;
        $opts['multiple'] = false;
        $opts['label'] = $options['label'];
        if ($options['class'] !== null) {
            $opts['class'] = $options['class'];
        }
        if ($options['ng-model'] !== null) {
            $opts['ng-model'] = $options['ng-model'];
        }
        if (isset($options['required'])) {
            $opts['required'] = $options['required'];
        }
        if (isset($options['name'])) {
            $opts['name'] = $options['name'];
        }

        return $this->Form->input($fieldName, $opts);
    }

    private function getSelected($fieldName) {
        if (empty($this->data)) {
            return null;
        }
        $view = & ClassRegistry::getObject('view');
        $this->setEntity($fieldName);
        $ent = $view->entity();
        if (empty($ent)) {
            return null;
        }
        $obj = $this->data;
        $i = 0;
        while (true) {
            if (is_array($obj)) {
                if (array_key_exists($ent[$i], $obj)) {
                    $obj = $obj[$ent[$i]];
                    $i++;
                }
            } else {
                return $obj;
            }
        }
    }

    public function translate($toTranslate, $defaultLang) {
        if (isset($toTranslate[Configure::read('Config.language')])) {
            return $toTranslate[Configure::read('Config.language')];
        } elseif (isset($toTranslate[$defaultLang])) {
            return $toTranslate[$defaultLang];
        } else {
            if (!empty($toTranslate)) {
                return array_shift(array_values($toTranslate));
            }
            return null;
        }
    }

    public function getCurrencySymbol($currency) {
        return $this->_currencies[$currency];
    }

    public function getEligibleCountriesForTaxDeduction() {
        return $this->_eligibleCountriesForTaxDeduction;
    }

    public function getCountries() {
        return $this->_countries;
    }

}

?>