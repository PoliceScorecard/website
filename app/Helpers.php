<?php
if (!function_exists('anon')) {
    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param  string  $address
     * @return string
     */
    function anon($address)
    {
        $ipv4NetMask = "255.255.255.0";
        $ipv6NetMask = "ffff:ffff:ffff:ffff:0000:0000:0000:0000";

        $packedAddress = inet_pton($address);
        if (strlen($packedAddress) == 4) {
            return inet_ntop(inet_pton($address) & inet_pton($ipv4NetMask));
        } elseif (strlen($packedAddress) == 16) {
            return inet_ntop(inet_pton($address) & inet_pton($ipv6NetMask));
        } else {
            return "";
        }
    }
}

if (!function_exists('isDevelopment')) {
    /**
     * Check if we're not in Production
     * @return string
     */
    function isDevelopment()
    {
        return (env('APP_ENV') !== 'production' && env('APP_ENV') !== 'staging') ? 'true' : 'false';
    }
}

if (!function_exists('isProduction')) {
    /**
     * Check if we're in Production
     * @return string
     */
    function isProduction()
    {
        return (env('APP_ENV') === 'production' || env('APP_ENV') === 'staging') ? 'true' : 'false';
    }
}

if (!function_exists('titleCase')) {
    /**
     * Convert String to Title Case
     * @param $string
     * @return string
     */
    function titleCase($string)
    {
        $title = str_replace('-', ' ', $string);
        $title = str_replace('_', ' ', $title);

        $regx = '/<(code|var)[^>]*>.*?<\/\1>|<[^>]+>|&\S+;/';

        preg_match_all($regx, $title, $html, PREG_OFFSET_CAPTURE);

        $title = preg_replace($regx, '', $title);

        preg_match_all('/[\w\p{L}&`\'‘’"“\.@:\/\{\(\[<>_]+-? */u', $title, $m1, PREG_OFFSET_CAPTURE);

        foreach ($m1[0] as &$m2) {
            list ($m, $i) = $m2;

            $i = mb_strlen(substr ($title, 0, $i), 'UTF-8');

            $m = $i > 0 && mb_substr($title, max(0, $i-2), 1, 'UTF-8') !== ':' && !preg_match('/[\x{2014}\x{2013}] ?/u', mb_substr($title, max(0, $i-2), 2, 'UTF-8')) &&

            preg_match('/^(a(nd?|s|t)?|b(ut|y)|en|for|i[fn]|o[fnr]|t(he|o)|vs?\.?|via)[ \-]/i', $m)
                ? mb_strtolower($m, 'UTF-8')
                : (preg_match('/[\'"_{(\[‘“]/u', mb_substr($title, max(0, $i-1), 3, 'UTF-8'))
                    ? mb_substr($m, 0, 1, 'UTF-8') . mb_strtoupper(mb_substr($m, 1, 1, 'UTF-8'), 'UTF-8') . mb_substr($m, 2, mb_strlen($m, 'UTF-8')-2, 'UTF-8')
                    : (preg_match ('/[\])}]/', mb_substr($title, max (0, $i-1), 3, 'UTF-8')) || preg_match('/[A-Z]+|&|\w+[._]\w+/u', mb_substr($m, 1, mb_strlen($m, 'UTF-8')-1, 'UTF-8'))
                        ? $m
                        : mb_strtoupper (mb_substr($m, 0, 1, 'UTF-8'), 'UTF-8') . mb_substr($m, 1, mb_strlen($m, 'UTF-8'), 'UTF-8')
                    ));

            $title = mb_substr($title, 0, $i, 'UTF-8') . $m . mb_substr($title, $i + mb_strlen($m, 'UTF-8'), mb_strlen($title, 'UTF-8'), 'UTF-8');
        }

        foreach ($html[0] as &$tag) {
            $title = substr_replace ($title, $tag[0], $tag[1], 0);
        }

        // We have some Roman Numerals we need to consider too
        $title = preg_replace_callback('/\b(?=[LXIVCDM]+\b)([a-z]+)\b/i',
            function($matches) {
                return strtoupper($matches[0]);
            }, ucwords(strtolower($title))
        );

        $title = preg_replace('/\s+/', ' ', $title);
        return $title;
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate Text
     * @param $string
     * @param $limit
     * @param string $break
     * @param string $pad
     * @return string
     */
    function truncateText($string, $limit, $break=" ", $pad=" ...")
    {
        // return with no change if string is shorter than $limit
        if(strlen($string) <= $limit) return $string;

        // is $break present between $limit and the end of the string?
        if(false !== ($breakpoint = strpos($string, $break, $limit))) {
            if($breakpoint < strlen($string) - 1) {
                $string = substr($string, 0, $breakpoint) . $pad;
            }
        }

        return $string;
    }
}

if (!function_exists('pageClass')) {
    /**
     * Convert Current Page to Class
     * @param string $url
     * @return string
     */
    function pageClass($url)
    {
        list($page, $sub) = array_pad(explode('/', $url), 2, '');
        $classes = array();

        if ($page) {
            $classes[] = 'page-' . $page;
        } else {
            $classes[] = 'page-home';
        }

        if ($sub) {
            $classes[] = 'sub-page-' . $sub;
        }

        return implode(' ', $classes);
    }
}

if (!function_exists('removeEmpty')) {

    function removeEmpty($data, $key_name)
    {
        $clean = array();
        $arr = (array) $data;
        foreach ($arr as $key => $item) {
            $arr = (array) $item;
            $check = trim($arr[$key_name]);

            if (isset($arr[$key_name]) && !empty($check) && strlen($check) > 0) {
                $clean[] = $item;
            }
        }

        return $clean;
    }
}

if (!function_exists('trackData')) {

    function trackData($category, $action, $label = null, $value = null)
    {
        $data = array('data-track');

        if ( !empty($category)) {
            $data[] = 'data-category="' . $category . '"';
        }

        if ( !empty($action)) {
            $data[] = 'data-action="' . $action . '"';
        }

        if ( !empty($label)) {
            $data[] = 'data-label="' . $label . '"';
        }

        if ( !empty($value)) {
            $data[] = 'data-value="' . $value . '"';
        }

        return join(' ', $data);
    }
}

if (!function_exists('getStateName')) {
    function getStateName($abbr) {
        $states = array(
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'US' =>  'United States',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming'
        );

        return $states[strtoupper($abbr)];
    }
}

/**
 * Get National Grades
 *
 * @param string $states
 * @param string $type
 *
 * @return object
 */
if (!function_exists('getNationalGrades')) {
    function getNationalGrades($states, $type) {
        $complete = array();
        $incomplete = array();

        foreach($states as $abbr => $state) {
            if ($type === 'police-department' && !empty($state['police-department'])) {
                foreach($state['police-department'] as $department) {
                    if ($department['complete']) {
                        $complete[] = array(
                            'agency_name' => $department['agency_name'] . ', '.$abbr,
                            'complete' => $department['complete'],
                            'grade_class' => $department['grade_class'],
                            'grade_letter' => $department['grade_letter'],
                            'overall_score' => $department['overall_score'],
                            'change_overall_score' => $department['change_overall_score'],
                            'title' => $department['title'],
                            'url_pretty' => $department['url_pretty'],
                            'url' => $department['url']
                        );
                    } else {
                        $incomplete[] = array(
                            'agency_name' => $department['agency_name'] . ', '.$abbr,
                            'complete' => $department['complete'],
                            'grade_class' => $department['grade_class'],
                            'grade_letter' => $department['grade_letter'],
                            'overall_score' => $department['overall_score'],
                            'change_overall_score' => $department['change_overall_score'],
                            'title' => $department['title'],
                            'url_pretty' => $department['url_pretty'],
                            'url' => $department['url']
                        );
                    }
                }
            }

            if ($type === 'sheriff' && !empty($state['sheriff'])) {
                foreach($state['sheriff'] as $department) {
                    if ($department['complete']) {
                        $complete[] = array(
                            'agency_name' => $department['agency_name'] . ', '.$abbr,
                            'complete' => $department['complete'],
                            'grade_class' => $department['grade_class'],
                            'grade_letter' => $department['grade_letter'],
                            'overall_score' => $department['overall_score'],
                            'change_overall_score' => $department['change_overall_score'],
                            'title' => $department['title'],
                            'url_pretty' => $department['url_pretty'],
                            'url' => $department['url']
                        );
                    } else {
                        $incomplete[] = array(
                            'agency_name' => $department['agency_name'] . ', '.$abbr,
                            'complete' => $department['complete'],
                            'grade_class' => $department['grade_class'],
                            'grade_letter' => $department['grade_letter'],
                            'overall_score' => $department['overall_score'],
                            'change_overall_score' => $department['change_overall_score'],
                            'title' => $department['title'],
                            'url_pretty' => $department['url_pretty'],
                            'url' => $department['url']
                        );
                    }
                }
            }
        }

        usort($complete, function($a, $b) {
            return $a['overall_score'] > $b['overall_score'];
        });

        usort($incomplete, function($a, $b) {
            return $a['overall_score'] > $b['overall_score'];
        });

        return array(
            'complete' => $complete,
            'incomplete' => $incomplete,
            'all' => array_merge($complete, $incomplete)
        );
    }
}

/**
 * Output Number for Template
 *
 * @param  string $string String to be converted to Number
 * @param  integer $decimal Number of Decimal Points
 * @param  string $suffix Suffix to Add to Output
 * @param  boolean $invert If this is a percent, and we need to subtract number from total
 *
 * @return string Converted Number
 */
if (!function_exists('num')) {
    function num($string, $decimal = 0, $suffix = '', $invert = false) {
        if (empty($string) && $string !== 0 && $string !== '0') {
            return "N/A";
        }

        $string = preg_replace("/[^0-9\.]/", "", trim($string));
        $output = floatval($string);
        if ($output < 0) {
            $output = 0;
        }

        if ($invert) {
            $output = (100 - $output);
        }

        $output = round($output, $decimal);

        if ($output > 999) {
            $output = number_format($output, $decimal);
        }

        if ($output === '0.0' || $output === '0.00') {
            $output = '0';
        }

        if (substr($output, -2) === '.0' || substr($output, -3) === '.00') {
            $output = intval($string);
        }

        return "{$output}{$suffix}";
    }
}

/**
 * Get National Summary
 *
 * @param string $states
 *
 * @return object
 */
if (!function_exists('getNationalSummary')) {
    function getNationalSummary($states) {
        $total_arrests = 0;
        $total_complaints_reported = 0;
        $total_complaints_sustained = 0;
        $total_people_killed = 0;

        $total_black_people_killed = 0;
        $total_black_population = 0;
        $total_hispanic_people_killed = 0;
        $total_hispanic_population = 0;
        $total_white_people_killed = 0;
        $total_white_population = 0;

        $total_low_level_arrests = 0;
        $total_violent_crime_arrests = 0;

        $total_arrests_2013 = 0;
        $total_arrests_2014 = 0;
        $total_arrests_2015 = 0;
        $total_arrests_2016 = 0;
        $total_arrests_2017 = 0;
        $total_arrests_2018 = 0;
        $total_arrests_2019 = 0;
        $total_arrests_2020 = 0;
        $total_arrests_2021 = 0;
        $total_arrests_2022 = 0;
        $total_arrests_2023 = 0;

        foreach($states as $abbr => $state) {
            $total_arrests += $state['total_arrests'];
            $total_complaints_reported += $state['total_complaints_reported'];
            $total_complaints_sustained += $state['total_complaints_sustained'];
            $total_people_killed += $state['total_people_killed'];

            $total_black_people_killed += $state['total_black_people_killed'];
            $total_black_population += $state['total_black_population'];
            $total_hispanic_people_killed += $state['total_hispanic_people_killed'];
            $total_hispanic_population += $state['total_hispanic_population'];
            $total_white_people_killed += $state['total_white_people_killed'];
            $total_white_population += $state['total_white_population'];

            $total_low_level_arrests += $state['total_low_level_arrests'];
            $total_violent_crime_arrests += $state['total_violent_crime_arrests'];

            $total_arrests_2013 += $state['total_arrests_2013'];
            $total_arrests_2014 += $state['total_arrests_2014'];
            $total_arrests_2015 += $state['total_arrests_2015'];
            $total_arrests_2016 += $state['total_arrests_2016'];
            $total_arrests_2017 += $state['total_arrests_2017'];
            $total_arrests_2018 += $state['total_arrests_2018'];
            $total_arrests_2019 += $state['total_arrests_2019'];
            $total_arrests_2020 += $state['total_arrests_2020'];
            $total_arrests_2021 += $state['total_arrests_2021'];
            $total_arrests_2022 += $state['total_arrests_2022'];
            $total_arrests_2023 += $state['total_arrests_2023'];
        }

        return array(
            'total_arrests' => $total_arrests,
            'total_low_level_arrests' => $total_low_level_arrests,
            'total_complaints_reported' => $total_complaints_reported,
            'total_complaints_sustained' => $total_complaints_sustained,
            'total_people_killed' => $total_people_killed,
            'total_black_people_killed' => $total_black_people_killed,
            'total_hispanic_people_killed' => $total_hispanic_people_killed,
            'total_white_people_killed' => $total_white_people_killed,
            'total_black_population' => $total_black_population,
            'total_hispanic_population' => $total_hispanic_population,
            'total_white_population' => $total_white_population,
            'total_arrests_2013' => $total_arrests_2013,
            'total_arrests_2014' => $total_arrests_2014,
            'total_arrests_2015' => $total_arrests_2015,
            'total_arrests_2016' => $total_arrests_2016,
            'total_arrests_2017' => $total_arrests_2017,
            'total_arrests_2018' => $total_arrests_2018,
            'total_arrests_2019' => $total_arrests_2019,
            'total_arrests_2020' => $total_arrests_2020,
            'total_arrests_2021' => $total_arrests_2021,
            'total_arrests_2022' => $total_arrests_2022,
            'total_arrests_2023' => $total_arrests_2023,
            'black_deadly_force_disparity_per_population' => (($total_black_people_killed / $total_black_population) / ($total_white_people_killed / $total_white_population)),
            'hispanic_deadly_force_disparity_per_population' => (($total_hispanic_people_killed / $total_hispanic_population) / ($total_white_people_killed / $total_white_population)),
            'times_more_misdemeanor_arrests_than_violent_crime' => ($total_low_level_arrests / $total_violent_crime_arrests)
        );
    }
}

/**
 * Get National Total
 *
 * @param string $states
 *
 * @return string
 */
if (!function_exists('getNationalTotal')) {
    function getNationalTotal($states) {
        $total = 0;

        foreach($states as $abbr => $state) {
            if (!empty($state['police-department'])) {
                $total += count($state['police-department']);
            }

            if (!empty($state['sheriff'])) {
                $total += count($state['sheriff']);
            }
        }

        return num($total);
    }
}

/**
 * Get National Police Total
 *
 * @param string $states
 *
 * @return string
 */
if (!function_exists('getNationalPoliceTotal')) {
    function getNationalPoliceTotal($states) {
        $total = 0;

        foreach($states as $abbr => $state) {
            if (!empty($state['police-department'])) {
                $total += count($state['police-department']);
            }
        }

        return num($total);
    }
}

/**
 * Get National Sheriff Total
 *
 * @param string $states
 *
 * @return string
 */
if (!function_exists('getNationalSheriffTotal')) {
    function getNationalSheriffTotal($states) {
        $total = 0;

        foreach($states as $abbr => $state) {
            if (!empty($state['sheriff'])) {
                $total += count($state['sheriff']);
            }
        }

        return num($total);
    }
}

/**
 * Get National Map Data
 *
 * @param string $states
 * @param string $type
 *
 * @return object
 */
if (!function_exists('getNationalMapData')) {
    function getNationalMapData($states, $type) {
        $map_data = array();
        $map_scores = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array()
        );

        foreach($states as $abbr => $state) {
            if ($type === 'police-department' && !empty($state['police-department'])) {
                foreach($state['police-department'] as $department) {
                    if (!empty($department['latitude']) && !empty($department['longitude']) && $department['complete']) {
                        $index = getColorIndex($department['overall_score'], $department['complete']);
                        $map_scores[$index - 1][] = array(
                            'slug' => $department['slug'],
                            'className' => 'location-'.$department['slug'],
                            'colorIndex' => getColorIndex($department['overall_score'], $department['complete']),
                            'name' => $department['agency_name'],
                            'lat' => floatval($department['latitude']),
                            'lon' => floatval($department['longitude']),
                            'stateAbbr' => strtolower($abbr),
                            'value' => $department['overall_score']
                        );
                    }
                }
            }

            if ($type === 'sheriff' && !empty($state['sheriff'])) {
                foreach($state['sheriff'] as $department) {
                    if (!empty($department['district']) && $department['complete']) {
                        $map_data[] = array(
                            'slug' => $department['slug'],
                            'className' => 'location-'.$department['slug'],
                            'colorIndex' => getColorIndex($department['overall_score'], $department['complete']),
                            'name' => $department['agency_name'],
                            'hc-key' => $department['district'],
                            'stateAbbr' => strtolower($abbr),
                            'value' => $department['overall_score']
                        );
                    }
                }
            }
        }

        return ($type === 'police-department') ? json_encode($map_scores) : json_encode($map_data);
    }
}

/**
 * Return Percent Score to Letter Grade
 *
 * @param integer $score Percent Score
 *
 * @return integer
 */
if (!function_exists('getColorIndex')) {
    function getColorIndex($score, $complete) {
        $score = intval($score);

        if (!$complete) {
            return 1;
        } else if ($score <= 29) {
            return 2;
        } else if ($score <= 59 && $score >= 30) {
            return 3;
        }
        elseif($score <= 69 && $score >= 60) {
            return 4;
        }
        elseif($score <= 79 && $score >= 70) {
            return 5;
        }
        elseif($score <= 89 && $score >= 80) {
            return 6;
        }
        elseif($score >= 90) {
            return 7;
        }
    }
}


/**
 * Generate State Link
 *
 * @param string $key
 * @param string $state
 *
 * @return string
 */
if (!function_exists('generateStateLink')) {
    function generateStateLink($key, $state) {
        $stateName = getStateName($key);
        $activeClass = (strtoupper($key) === strtoupper($state)) ? 'active' : '';
        $stateCode = strtolower($key);

        return "<a href=\"/${stateCode}\" class=\"state-link ${activeClass}\" title=\"View Report for ${stateName}'s Largest Police Department\">${stateName}</a>";
    }
}

/**
 * Get State Icon
 *
 * @param string $abbr
 *
 * @return string
 */
if (!function_exists('getStateIcon')) {
    function getStateIcon($abbr) {
        $states = array(
            "AK" => "A",
            "AL" => "B",
            "AR" => "C",
            "AZ" => "D",
            "CA" => "E",
            "CO" => "F",
            "CT" => "G",
            "DC" => "y",
            "DE" => "H",
            "FL" => "I",
            "GA" => "J",
            "HI" => "K",
            "IA" => "L",
            "ID" => "M",
            "IL" => "N",
            "IN" => "O",
            "KS" => "P",
            "KY" => "Q",
            "LA" => "R",
            "MA" => "S",
            "MD" => "T",
            "ME" => "U",
            "MI" => "V",
            "MN" => "W",
            "MO" => "X",
            "MS" => "Y",
            "MT" => "Z",
            "NC" => "a",
            "ND" => "b",
            "NE" => "c",
            "NH" => "d",
            "NJ" => "e",
            "NM" => "f",
            "NV" => "g",
            "NY" => "h",
            "OH" => "i",
            "OK" => "j",
            "OR" => "k",
            "PA" => "l",
            "RI" => "m",
            "SC" => "n",
            "SD" => "o",
            "TN" => "p",
            "TX" => "q",
            "US" => "z",
            "UT" => "r",
            "VA" => "s",
            "VT" => "t",
            "WA" => "u",
            "WI" => "v",
            "WV" => "w",
            "WY" => "x",
        );

        return $states[strtoupper($abbr)];
    }
}

/**
 * Get State Total
 *
 * @param string $states
 * @param string $code
 *
 * @return string
 */
if (!function_exists('getStateTotal')) {
    function getStateTotal($states, $code) {
        $total = 0;

        foreach($states as $abbr => $state) {
            if ($code === $abbr) {
                if (!empty($state['police-department'])) {
                    $total += count($state['police-department']);
                }

                if (!empty($state['sheriff'])) {
                    $total += count($state['sheriff']);
                }
            }
        }

        return num($total);
    }
}

/**
 * Return Percent Score to Letter Grade Class
 *
 * @param string $score Percent Score
 *
 * @return string
 */
if (!function_exists('getGradeClass')) {
    function getGradeClass($score) {
        $score = intval($score);

        if ($score <= 29) {
            return 'f-minus';
        } else if ($score <= 59 && $score >= 30) {
            return 'f';
        }
        elseif($score <= 62 && $score >= 60) {
            return 'd';
        }
        elseif($score <= 66 && $score >= 63) {
            return 'd';
        }
        elseif($score <= 69 && $score >= 67) {
            return 'd';
        }
        elseif($score <= 72 && $score >= 70) {
            return 'c';
        }
        elseif($score <= 76 && $score >= 73) {
            return 'c';
        }
        elseif($score <= 79 && $score >= 77) {
            return 'c';
        }
        elseif($score <= 82 && $score >= 80) {
            return 'b';
        }
        elseif($score <= 86 && $score >= 83) {
            return 'b';
        }
        elseif($score <= 89 && $score >= 87) {
            return 'b';
        }
        elseif($score <= 92 && $score >= 90) {
            return 'a';
        }
        elseif($score <= 97 && $score >= 93) {
            return 'a';
        }
        elseif($score >= 98) {
            return 'a';
        }
    }
}

/**
 * Get Change
 *
 * @param integer $change
 * @param boolean $reverse
 * @param string $label
 *
 * @return string
 */
if (!function_exists('getChange')) {
    function getChange($change, $reverse = false, $label = 'from 2016', $police_violence = null) {
        $change = intval($change);
        $text = '';
        $tooltip = '';
        $class = '';

        if ($police_violence) {
            $start = null;
            $end = null;

            if (isset($police_violence['less_lethal_force_2013'])) {
                if (!$start) {
                    $start = '2013';
                }

                $end = '2013';
            }

            if (isset($police_violence['less_lethal_force_2014'])) {
                if (!$start) {
                    $start = '2014';
                }

                $end = '2014';
            }

            if (isset($police_violence['less_lethal_force_2015'])) {
                if (!$start) {
                    $start = '2015';
                }

                $end = '2015';
            }

            if (isset($police_violence['less_lethal_force_2016'])) {
                if (!$start) {
                    $start = '2016';
                }

                $end = '2016';
            }

            if (isset($police_violence['less_lethal_force_2017'])) {
                if (!$start) {
                    $start = '2017';
                }

                $end = '2017';
            }

            if (isset($police_violence['less_lethal_force_2018'])) {
                if (!$start) {
                    $start = '2018';
                }

                $end = '2018';
            }

            if (isset($police_violence['less_lethal_force_2019'])) {
                if (!$start) {
                    $start = '2019';
                }

                $end = '2019';
            }

            if (isset($police_violence['less_lethal_force_2020'])) {
                if (!$start) {
                    $start = '2020';
                }

                $end = '2020';
            }

            if (isset($police_violence['less_lethal_force_2021'])) {
                if (!$start) {
                    $start = '2021';
                }

                $end = '2021';
            }

            if (isset($police_violence['less_lethal_force_2022'])) {
                if (!$start) {
                    $start = '2022';
                }

                $end = '2022';
            }

            if (isset($police_violence['less_lethal_force_2023'])) {
                if (!$start) {
                    $start = '2023';
                }

                $end = '2023';
            }

            if ($start && $end && $start !== $end) {
                $label = "from {$start}-" . substr($end, -2);
            } else if ($start && $end && $start === $end) {
                $label = "from {$start}";
            }
        }

        if ($change && $change !== 0) {
            $text = ($change > 0) ? "<span class=\"grade-arrow\"><span>▶</span><small>+{$change}%</small></span>" : "<span class=\"grade-arrow\"><span>▶</span><small>{$change}%</small></span>";
            $class = ($change > 0) ? 'bad' : 'good';
            $tooltip = ($change > 0) ? "Up {$change}% {$label}" : "Down ". abs($change) . "% {$label}";

            if ($reverse) {
                $class .= ' reverse';
            }

            return "<a href=\"#\" class=\"stats-change tooltip {$class}\" data-tooltip=\"{$tooltip}\">{$text}</a>";
        }
    }
}

/**
 * Output Template
 *
 * @param  string $template Text to Convert
 * @param  string [$default='N/A'] Text to use if output is empty
 * @param  string [$suffix=''] Append this to end of string
 *
 * @return string
 */
if (!function_exists('output')) {
    function output($template, $default = 'N/A', $suffix = '') {
        $template = strval($template);
        if (empty($template) && $template !== '0') {
            $template = $default;
        }

        return "{$template}{$suffix}";
    }
}

/**
 * Custom Color for Progress Bar
 *
 * @param  string $score Number
 * @param  string $color Which Color Pattern to use
 * @param  string $break Which Break Point to use
 *
 * @return string Color to use
 */
if (!function_exists('progressBar')) {
    function progressBar($score, $color = 'default', $break = 'default') {
        if (empty($score)) {
            $score = 0;
        }

        $breakpoints = array(
            'default' => array(20, 40, 50, 60, 100)
        );

        $colors = array(
            'default' => array('red', 'orange', 'yellow', 'green', 'bright-green'),
            'reverse' => array('bright-green', 'green', 'yellow', 'orange', 'red')
        );

        $output = $colors[$color][0];
        $score = floatval(preg_replace("/[^0-9\.]/", "", trim($score)));

        if ($score > 100) {
            $score = 100;
        }

        if ($score < 0) {
            $score = 0;
        }

        foreach($breakpoints[$break] as $index => $breakpoint) {
            if ($score >= intval($breakpoint)) {
                $output = (($index + 1) < sizeof($colors[$color])) ? $colors[$color][$index + 1] : $colors[$color][$index];
            }
        }

        return $output;
    }
}


/**
 * Number Formatter
 *
 * @param integer $num
 * @param integer $decimal
 *
 * @return string
 */
if (!function_exists('nFormatter')) {
    function nFormatter($num, $decimal = 2) {
        $num = intval(str_replace(',', '', $num));
        $units = ["k", "M", "B", "T"];
        $order = floor(log($num) / log(1000));
        $unit_name = ($order > 0) ? $units[($order - 1)] : '';

        $val = ($num === 0) ? $num : round(floatval($num / 1000 ** $order), $decimal).$unit_name;

        // output number remainder + unitname
        return '$'.$val;
    }
}

/**
 * Get Map Data
 *
 * @param string $state
 *
 * @param string $type
 */
if (!function_exists('getMapData')) {
    function getMapData($state, $type, $grades) {
        $map_data = array();
        $map_scores = array(
            array(),
            array(),
            array(),
            array(),
            array(),
            array(),
            array()
        );

        foreach($grades as $grade) {
            if ($type === 'police-department' && !empty($grade['latitude']) && !empty($grade['longitude'])) {
                $index = getColorIndex($grade['overall_score'], $grade['complete']);
                $map_scores[$index - 1][] = array(
                    'slug' => $grade['slug'],
                    'className' => 'type-'.$type.' location-'.$grade['slug'],
                    'colorIndex' => getColorIndex($grade['overall_score'], $grade['complete']),
                    'name' => $grade['agency_name'],
                    'lat' => floatval($grade['latitude']),
                    'lon' => floatval($grade['longitude']),
                    'value' => $grade['overall_score']
                );
            } else if ($type === 'sheriff' && !empty($grade['district'])) {
                $map_data[] = array(
                    'slug' => $grade['slug'],
                    'className' => 'type-'.$type.' location-'.$grade['slug'],
                    'colorIndex' => getColorIndex($grade['overall_score'], $grade['complete']),
                    'name' => $grade['agency_name'],
                    'hc-key' => $grade['district'],
                    'value' => $grade['overall_score']
                );
            }
        }

        return ($type === 'police-department') ? json_encode($map_scores) : json_encode($map_data);
    }
}

/**
 * Get Map Location
 *
 * @param string $type
 * @param object $scorecard
 * @param string $location
 *
 * @return string
 */
if (!function_exists('getMapLocation')) {
    function getMapLocation($type, $scorecard, $location) {
        $map_data = array(
            'type' => $type,
            'name' => ($type === 'police-department') ? 'Police Department' : 'Sheriff Department',
            'data' => array(),
            'icon' => getGradeIcon($scorecard['report']['overall_score'], $scorecard['agency']['complete'])
        );

        $latitude = (isset($scorecard['geo']) && isset($scorecard['geo']['city']) && isset($scorecard['geo']['city']['latitude'])) ? floatval($scorecard['geo']['city']['latitude']) : null;
        $longitude = (isset($scorecard['geo']) && isset($scorecard['geo']['city']) && isset($scorecard['geo']['city']['longitude'])) ? floatval($scorecard['geo']['city']['longitude']) : null;

        $map_data['data'][] = array(
            'slug' => $location,
            'className' => 'location-'.$location,
            'colorIndex' => getColorIndex($scorecard['report']['overall_score'], $scorecard['agency']['complete']),
            'name' => $scorecard['agency']['name'],
            'lat' => $latitude,
            'lon' => $longitude,
            'value' => intval($scorecard['report']['overall_score'])
        );

        return json_encode($map_data);
    }
}

/**
 * Get Map Grade Icon
 * @param string $score Percent Score
 *
 * @return string
 */
if (!function_exists('getGradeIcon')) {
    function getGradeIcon($score, $complete) {
        $icon = $complete ? $score : 'incomplete';
        return 'url(/images/numbers/' . $icon . '.svg)';
    }
}


/**
 * Generate Arrest Chart
 *
 * @param object
 */
if (!function_exists('generateArrestChart')) {
    function generateArrestChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Low Level Arrests',
                    'backgroundColor' => '#b02424',
                    'stack' => 'arrests',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Other Arrests',
                    'backgroundColor' => '#9a9b9f',
                    'stack' => 'arrests',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['arrests']['arrests_2013'])) {
            $arrests_2013 = intval($scorecard['arrests']['arrests_2013']);
            $low_level_arrests_2013 = intval($scorecard['arrests']['low_level_arrests_2013']);

            $output['labels'][] = '2013';
            $output['datasets'][1]['data'][] = $arrests_2013 - $low_level_arrests_2013;
            $output['datasets'][0]['data'][] = $low_level_arrests_2013;
        }

        if (isset($scorecard['arrests']['arrests_2014'])) {
            $arrests_2014 = intval($scorecard['arrests']['arrests_2014']);
            $low_level_arrests_2014 = intval($scorecard['arrests']['low_level_arrests_2014']);

            $output['labels'][] = '2014';
            $output['datasets'][1]['data'][] = $arrests_2014 - $low_level_arrests_2014;
            $output['datasets'][0]['data'][] = $low_level_arrests_2014;
        }

        if (isset($scorecard['arrests']['arrests_2015'])) {
            $arrests_2015 = intval($scorecard['arrests']['arrests_2015']);
            $low_level_arrests_2015 = intval($scorecard['arrests']['low_level_arrests_2015']);

            $output['labels'][] = '2015';
            $output['datasets'][1]['data'][] = $arrests_2015 - $low_level_arrests_2015;
            $output['datasets'][0]['data'][] = $low_level_arrests_2015;
        }

        if (isset($scorecard['arrests']['arrests_2016'])) {
            $arrests_2016 = intval($scorecard['arrests']['arrests_2016']);
            $low_level_arrests_2016 = intval($scorecard['arrests']['low_level_arrests_2016']);

            $output['labels'][] = '2016';
            $output['datasets'][1]['data'][] = $arrests_2016 - $low_level_arrests_2016;
            $output['datasets'][0]['data'][] = $low_level_arrests_2016;
        }

        if (isset($scorecard['arrests']['arrests_2017'])) {
            $arrests_2017 = intval($scorecard['arrests']['arrests_2017']);
            $low_level_arrests_2017 = intval($scorecard['arrests']['low_level_arrests_2017']);

            $output['labels'][] = '2017';
            $output['datasets'][1]['data'][] = $arrests_2017 - $low_level_arrests_2017;
            $output['datasets'][0]['data'][] = $low_level_arrests_2017;
        }

        if (isset($scorecard['arrests']['arrests_2018'])) {
            $arrests_2018 = intval($scorecard['arrests']['arrests_2018']);
            $low_level_arrests_2018 = intval($scorecard['arrests']['low_level_arrests_2018']);

            $output['labels'][] = '2018';
            $output['datasets'][1]['data'][] = $arrests_2018 - $low_level_arrests_2018;
            $output['datasets'][0]['data'][] = $low_level_arrests_2018;
        }

        if (isset($scorecard['arrests']['arrests_2019'])) {
            $arrests_2019 = intval($scorecard['arrests']['arrests_2019']);
            $low_level_arrests_2019 = intval($scorecard['arrests']['low_level_arrests_2019']);

            $output['labels'][] = '2019';
            $output['datasets'][1]['data'][] = $arrests_2019 - $low_level_arrests_2019;
            $output['datasets'][0]['data'][] = $low_level_arrests_2019;
        }

        if (isset($scorecard['arrests']['arrests_2020'])) {
            $arrests_2020 = intval($scorecard['arrests']['arrests_2020']);
            $low_level_arrests_2020 = intval($scorecard['arrests']['low_level_arrests_2020']);

            $output['labels'][] = '2020';
            $output['datasets'][1]['data'][] = $arrests_2020 - $low_level_arrests_2020;
            $output['datasets'][0]['data'][] = $low_level_arrests_2020;
        }

        if (isset($scorecard['arrests']['arrests_2021'])) {
            $arrests_2021 = intval($scorecard['arrests']['arrests_2021']);
            $low_level_arrests_2021 = intval($scorecard['arrests']['low_level_arrests_2021']);

            $output['labels'][] = '2021';
            $output['datasets'][1]['data'][] = $arrests_2021 - $low_level_arrests_2021;
            $output['datasets'][0]['data'][] = $low_level_arrests_2021;
        }

        if (isset($scorecard['arrests']['arrests_2022'])) {
            $arrests_2022 = intval($scorecard['arrests']['arrests_2022']);
            $low_level_arrests_2022 = intval($scorecard['arrests']['low_level_arrests_2022']);

            $output['labels'][] = '2022';
            $output['datasets'][1]['data'][] = $arrests_2022 - $low_level_arrests_2022;
            $output['datasets'][0]['data'][] = $low_level_arrests_2022;
        }

        if (isset($scorecard['arrests']['arrests_2023'])) {
            $arrests_2023 = intval($scorecard['arrests']['arrests_2023']);
            $low_level_arrests_2023 = intval($scorecard['arrests']['low_level_arrests_2023']);

            $output['labels'][] = '2023';
            $output['datasets'][1]['data'][] = $arrests_2023 - $low_level_arrests_2023;
            $output['datasets'][0]['data'][] = $low_level_arrests_2023;
        }

        return json_encode($output);
    }
}

/**
 * Generate Civilian Chart
 *
 * @param object
 */
if (!function_exists('generateCivilianChart')) {
    function generateCivilianChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                // Civilian Complaints
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Complaints Sustained',
                    'backgroundColor' => '#9a9b9f',
                    'stack' => 'civilian_complaints',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Complaints Not Sustained',
                    'backgroundColor' => '#b02424',
                    'stack' => 'civilian_complaints',
                    'data' => array()
                ),

            )
        );

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2016']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][1]['data'][] = intval($scorecard['police_accountability']['civilian_complaints_reported_2016']) - intval($scorecard['police_accountability']['civilian_complaints_sustained_2016']);
            $output['datasets'][0]['data'][] = intval($scorecard['police_accountability']['civilian_complaints_sustained_2016']);
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2017']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2017'] - $scorecard['police_accountability']['civilian_complaints_sustained_2017'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2017'];
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2018']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2018'] - $scorecard['police_accountability']['civilian_complaints_sustained_2018'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2018'];
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2019']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2019'] - $scorecard['police_accountability']['civilian_complaints_sustained_2019'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2019'];
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2020']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2020'] - $scorecard['police_accountability']['civilian_complaints_sustained_2020'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2020'];
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2021']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2021'] - $scorecard['police_accountability']['civilian_complaints_sustained_2021'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2021'];
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2022']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2022'] - $scorecard['police_accountability']['civilian_complaints_sustained_2022'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2022'];
        }

        if (isset($scorecard['police_accountability']['civilian_complaints_reported_2023']) && isset($scorecard['police_accountability']['civilian_complaints_sustained_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['civilian_complaints_reported_2023'] - $scorecard['police_accountability']['civilian_complaints_sustained_2023'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['civilian_complaints_sustained_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Use Of Force Chart
 *
 * @param object
 */
if (!function_exists('generateUseOfForceChart')) {
    function generateUseOfForceChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                // Use of Force Complaints
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Use of Force Sustained',
                    'backgroundColor' => '#9a9b9f',
                    'stack' => 'use_of_force_complaints',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Use of Force Reported',
                    'backgroundColor' => '#b02424',
                    'stack' => 'use_of_force_complaints',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2016']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2016'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2016'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2017']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2017'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2017'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2018']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2018'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2018'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2019']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2019'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2019'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2020']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2020'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2020'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2021']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2021'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2021'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2022']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2022'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2022'];
        }

        if (isset($scorecard['police_accountability']['use_of_force_complaints_reported_2023']) && isset($scorecard['police_accountability']['use_of_force_complaints_sustained_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_reported_2023'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['use_of_force_complaints_sustained_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Discrimination Chart
 *
 * @param object
 */
if (!function_exists('generateDiscriminationChart')) {
    function generateDiscriminationChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                // Discrimination Complaints
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Discrimination Sustained',
                    'backgroundColor' => '#9a9b9f',
                    'stack' => 'discrimination_complaints',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Discrimination Reported',
                    'backgroundColor' => '#b02424',
                    'stack' => 'discrimination_complaints',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2016']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2016'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2016'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2017']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2017'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2017'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2018']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2018'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2018'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2019']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2019'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2019'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2020']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2020'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2020'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2021']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2021'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2021'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2022']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2022'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2022'];
        }

        if (isset($scorecard['police_accountability']['discrimination_complaints_reported_2023']) && isset($scorecard['police_accountability']['discrimination_complaints_sustained_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['discrimination_complaints_reported_2023'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['discrimination_complaints_sustained_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Criminal Chart
 *
 * @param object
 */
if (!function_exists('generateCriminalChart')) {
    function generateCriminalChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                // Criminal Complaints
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Criminal Sustained',
                    'backgroundColor' => '#9a9b9f',
                    'stack' => 'criminal_complaints',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'Criminal Reported',
                    'backgroundColor' => '#b02424',
                    'stack' => 'criminal_complaints',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2016']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2016'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2016'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2017']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2017'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2017'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2018']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2018'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2018'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2019']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2019'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2019'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2020']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2020'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2020'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2021']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2021'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2021'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2022']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2022'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2022'];
        }

        if (isset($scorecard['police_accountability']['criminal_complaints_reported_2023']) && isset($scorecard['police_accountability']['criminal_complaints_sustained_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['criminal_complaints_reported_2023'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['criminal_complaints_sustained_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Detention Chart
 *
 * @param object
 */
if (!function_exists('generateDetentionChart')) {
    function generateDetentionChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                // Complaints in Detention
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'In Detention Sustained',
                    'backgroundColor' => '#9a9b9f',
                    'stack' => 'complaints_in_detention',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 20,
                    'label' => 'In Detention Reported',
                    'backgroundColor' => '#b02424',
                    'stack' => 'complaints_in_detention',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2016']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2016'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2016'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2017']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2017'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2017'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2018']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2018'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2018'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2019']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2019'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2019'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2020']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2020'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2020'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2021']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2021'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2021'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2022']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2022'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2022'];
        }

        if (isset($scorecard['police_accountability']['complaints_in_detention_reported_2023']) && isset($scorecard['police_accountability']['complaints_in_detention_sustained_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][1]['data'][] = $scorecard['police_accountability']['complaints_in_detention_reported_2023'];
            $output['datasets'][0]['data'][] = $scorecard['police_accountability']['complaints_in_detention_sustained_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate History Chart
 *
 * @param object $scorecard
 *
 * @return string
 */
if (!function_exists('generateHistoryChart')) {
    function generateHistoryChart($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Police Shootings',
                    'backgroundColor' => '#dc4646',
                    'stack' => 'police-violence',
                    'hidden' => false,
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_violence']['police_shootings_2013'])) {
            $output['labels'][] = '2013';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2013'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2014'])) {
            $output['labels'][] = '2014';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2014'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2015'])) {
            $output['labels'][] = '2015';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2015'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2016'])) {
            $output['labels'][] = '2016';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2016'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2017'])) {
            $output['labels'][] = '2017';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2017'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2018'])) {
            $output['labels'][] = '2018';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2018'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2019'])) {
            $output['labels'][] = '2019';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2019'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2020'])) {
            $output['labels'][] = '2020';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2020'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2021'])) {
            $output['labels'][] = '2021';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2021'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2022'])) {
            $output['labels'][] = '2022';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2022'];
        }

        if (isset($scorecard['police_violence']['police_shootings_2023'])) {
            $output['labels'][] = '2023';

            $output['datasets'][0]['data'][] = $scorecard['police_violence']['police_shootings_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Arrest Disparity Chart
 *
 * @param object
 */
if (!function_exists('generateArrestDisparityChart')) {
    function generateArrestDisparityChart($scorecard) {
        $output = array(
            'labels' => array('', '', ''),
            'datasets' => array(
                // Complaints in Detention
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 60,
                    'label' => 'Black',
                    'backgroundColor' => '#b02424',
                    'stack' => 'black_low_level_arrest_rate',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 60,
                    'label' => 'Hispanic',
                    'backgroundColor' => '#f19975',
                    'stack' => 'hispanic_low_level_arrest_rate',
                    'data' => array()
                ),
                array(
                    'minBarLength' => 0,
                    'maxBarThickness' => 60,
                    'label' => 'White',
                    'backgroundColor' => '#d4d9e4',
                    'stack' => 'white_low_level_arrest_rate',
                    'data' => array()
                )
            )
        );

        $output['datasets'][0]['data'][] = isset($scorecard['arrests']['black_low_level_arrest_rate']) ? $scorecard['arrests']['black_low_level_arrest_rate'] : 0;
        $output['datasets'][1]['data'][] = isset($scorecard['arrests']['hispanic_low_level_arrest_rate']) ? $scorecard['arrests']['hispanic_low_level_arrest_rate'] : 0;
        $output['datasets'][2]['data'][] = isset($scorecard['arrests']['white_low_level_arrest_rate']) ? $scorecard['arrests']['white_low_level_arrest_rate'] : 0;

        return json_encode($output);
    }
}

/**
 * Generate Police Violence Chart
 *
 * @param object $scorecard
 *
 * @return string
 */
if (!function_exists('generateViolenceChart')) {
    function generateViolenceChart($scorecard) {
        $output = array(
            'categories' => array(),
            'series' => array()
        );

        $output['series'][] = array(
            'name' => 'Taser',
            'data' => array()
        );

        $output['series'][] = array(
            'name' => 'Impact Weapons',
            'data' => array()
        );

        $output['series'][] = array(
            'name' => 'Chemical Spray',
            'data' => array()
        );

        $output['series'][] = array(
            'name' => 'K9 Deployments',
            'data' => array()
        );

        $output['series'][] = array(
            'name' => 'Neck Restraints',
            'data' => array()
        );

        // Build 2013 Data
        if (isset($scorecard['police_violence']['taser_2013']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2013']) || isset($scorecard['police_violence']['chemical_spray_2013']) || isset($scorecard['police_violence']['K9_deployments_2013']) || isset($scorecard['police_violence']['neck_restraints_2013'])) {
            $output['categories'][] = '2013';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2013']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2013']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2013']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2013']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2013']);
        }

        // Build 2014 Data
        if (isset($scorecard['police_violence']['taser_2014']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2014']) || isset($scorecard['police_violence']['chemical_spray_2014']) || isset($scorecard['police_violence']['K9_deployments_2014']) || isset($scorecard['police_violence']['neck_restraints_2014'])) {
            $output['categories'][] = '2014';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2014']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2014']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2014']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2014']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2014']);
        }

        // Build 2015 Data
        if (isset($scorecard['police_violence']['taser_2015']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2015']) || isset($scorecard['police_violence']['chemical_spray_2015']) || isset($scorecard['police_violence']['K9_deployments_2015']) || isset($scorecard['police_violence']['neck_restraints_2015'])) {
            $output['categories'][] = '2015';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2015']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2015']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2015']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2015']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2015']);
        }

        // Build 2016 Data
        if (isset($scorecard['police_violence']['taser_2016']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2016']) || isset($scorecard['police_violence']['chemical_spray_2016']) || isset($scorecard['police_violence']['K9_deployments_2016']) || isset($scorecard['police_violence']['neck_restraints_2016'])) {
            $output['categories'][] = '2016';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2016']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2016']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2016']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2016']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2016']);
        }

        // Build 2017 Data
        if (isset($scorecard['police_violence']['taser_2017']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2017']) || isset($scorecard['police_violence']['chemical_spray_2017']) || isset($scorecard['police_violence']['K9_deployments_2017']) || isset($scorecard['police_violence']['neck_restraints_2017'])) {
            $output['categories'][] = '2017';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2017']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2017']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2017']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2017']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2017']);
        }

        // Build 2018 Data
        if (isset($scorecard['police_violence']['taser_2018']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2018']) || isset($scorecard['police_violence']['chemical_spray_2018']) || isset($scorecard['police_violence']['K9_deployments_2018']) || isset($scorecard['police_violence']['neck_restraints_2018'])) {
            $output['categories'][] = '2018';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2018']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2018']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2018']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2018']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2018']);
        }

        // Build 2019 Data
        if (isset($scorecard['police_violence']['taser_2019']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2019']) || isset($scorecard['police_violence']['chemical_spray_2019']) || isset($scorecard['police_violence']['K9_deployments_2019']) || isset($scorecard['police_violence']['neck_restraints_2019'])) {
            $output['categories'][] = '2019';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2019']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2019']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2019']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2019']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2019']);
        }

        // Build 2020 Data
        if (isset($scorecard['police_violence']['taser_2020']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2020']) || isset($scorecard['police_violence']['chemical_spray_2020']) || isset($scorecard['police_violence']['K9_deployments_2020']) || isset($scorecard['police_violence']['neck_restraints_2020'])) {
            $output['categories'][] = '2020';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2020']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2020']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2020']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2020']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2020']);
        }

        // Build 2021 Data
        if (isset($scorecard['police_violence']['taser_2021']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2021']) || isset($scorecard['police_violence']['chemical_spray_2021']) || isset($scorecard['police_violence']['K9_deployments_2021']) || isset($scorecard['police_violence']['neck_restraints_2021'])) {
            $output['categories'][] = '2021';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2021']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2021']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2021']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2021']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2021']);
        }

        // Build 2022 Data
        if (isset($scorecard['police_violence']['taser_2022']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2022']) || isset($scorecard['police_violence']['chemical_spray_2022']) || isset($scorecard['police_violence']['K9_deployments_2022']) || isset($scorecard['police_violence']['neck_restraints_2022'])) {
            $output['categories'][] = '2022';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2022']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2022']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2022']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2022']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2022']);
        }

        // Build 2023 Data
        if (isset($scorecard['police_violence']['taser_2023']) || isset($scorecard['police_violence']['impact_weapons_and_projectiles_2023']) || isset($scorecard['police_violence']['chemical_spray_2023']) || isset($scorecard['police_violence']['K9_deployments_2023']) || isset($scorecard['police_violence']['neck_restraints_2023'])) {
            $output['categories'][] = '2023';

            $output['series'][0]['data'][] = intval($scorecard['police_violence']['taser_2023']);
            $output['series'][1]['data'][] = intval($scorecard['police_violence']['impact_weapons_and_projectiles_2023']);
            $output['series'][2]['data'][] = intval($scorecard['police_violence']['chemical_spray_2023']);
            $output['series'][3]['data'][] = intval($scorecard['police_violence']['K9_deployments_2023']);
            $output['series'][4]['data'][] = intval($scorecard['police_violence']['neck_restraints_2023']);
        }

        // Clean up Empty Reports
        if (array_sum($output['series'][4]['data']) === 0) {
            array_splice($output['series'], 4, 1);
        }

        if (array_sum($output['series'][3]['data']) === 0) {
            array_splice($output['series'], 3, 1);
        }

        if (array_sum($output['series'][2]['data']) === 0) {
            array_splice($output['series'], 2, 1);
        }

        if (array_sum($output['series'][1]['data']) === 0) {
            array_splice($output['series'], 1, 1);
        }

        if (array_sum($output['series'][0]['data']) === 0) {
            array_splice($output['series'], 0, 1);
        }

        return $output;
    }
}

/**
 * Generate Bar Chart Funds Taken
 *
 * @param object $scorecard
 *
 * @return string
 */
if (!function_exists('generateBarChartFundsTaken')) {
    function generateBarChartFundsTaken($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Funds Taken',
                    'backgroundColor' => '#dc4646',
                    'stack' => 'funds-taken',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_funding']['fines_forfeitures_2010'])) {
            $output['labels'][] = '2010';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2010'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2011'])) {
            $output['labels'][] = '2011';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2011'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2012'])) {
            $output['labels'][] = '2012';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2012'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2013'])) {
            $output['labels'][] = '2013';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2013'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2014'])) {
            $output['labels'][] = '2014';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2014'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2015'])) {
            $output['labels'][] = '2015';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2015'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2016'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2017'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2018'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2019'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2020'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2021'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2022'];
        }

        if (isset($scorecard['police_funding']['fines_forfeitures_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['fines_forfeitures_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Bar Chart Officers
 *
 * @param object $scorecard
 *
 * @return string
 */
if (!function_exists('generateBarChartOfficers')) {
    function generateBarChartOfficers($scorecard) {
        $output = array(
            'labels' => array(),
            'datasets' => array(
                array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Officers',
                    'backgroundColor' => '#dc4646',
                    'stack' => 'officers',
                    'data' => array()
                )
            )
        );

        if (isset($scorecard['police_funding']['total_officers_2013'])) {
            $output['labels'][] = '2013';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2013'];
        }

        if (isset($scorecard['police_funding']['total_officers_2014'])) {
            $output['labels'][] = '2014';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2014'];
        }

        if (isset($scorecard['police_funding']['total_officers_2015'])) {
            $output['labels'][] = '2015';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2015'];
        }

        if (isset($scorecard['police_funding']['total_officers_2016'])) {
            $output['labels'][] = '2016';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2016'];
        }

        if (isset($scorecard['police_funding']['total_officers_2017'])) {
            $output['labels'][] = '2017';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2017'];
        }

        if (isset($scorecard['police_funding']['total_officers_2018'])) {
            $output['labels'][] = '2018';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2018'];
        }

        if (isset($scorecard['police_funding']['total_officers_2019'])) {
            $output['labels'][] = '2019';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2019'];
        }

        if (isset($scorecard['police_funding']['total_officers_2020'])) {
            $output['labels'][] = '2020';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2020'];
        }

        if (isset($scorecard['police_funding']['total_officers_2021'])) {
            $output['labels'][] = '2021';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2021'];
        }

        if (isset($scorecard['police_funding']['total_officers_2022'])) {
            $output['labels'][] = '2022';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2022'];
        }

        if (isset($scorecard['police_funding']['total_officers_2023'])) {
            $output['labels'][] = '2023';
            $output['datasets'][0]['data'][] = $scorecard['police_funding']['total_officers_2023'];
        }

        return json_encode($output);
    }
}

/**
 * Generate Bar Chart
 *
 * @param object $scorecard
 * @param string $type
 *
 * @return string
 */
if (!function_exists('generateBarChart')) {
    function generateBarChart($scorecard, $type) {
        $output = array(
            'labels' => array(' '),
            'datasets' => array()
        );

        if ($type === 'police-department') {
            if (isset($scorecard['police_funding']['police_budget'])) {
                $police_budget = $scorecard['police_funding']['police_budget'];
                $output['datasets'][] = array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Police',
                    'backgroundColor' => '#dc4646',
                    'borderWidth' => 0,
                    'data' => array($police_budget)
                );
            }

            if (isset($scorecard['police_funding']['health_budget'])) {
                $health_budget = $scorecard['police_funding']['health_budget'];
                $output['datasets'][] = array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Health',
                    'backgroundColor' => '#58595b',
                    'borderWidth' => 0,
                    'data' => array($health_budget)
                );
            }

            if (isset($scorecard['police_funding']['housing_budget'])) {
                $housing_budget = $scorecard['police_funding']['housing_budget'];
                $output['datasets'][] = array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Housing',
                    'backgroundColor' => '#9a9b9f',
                    'borderWidth' => 0,
                    'data' => array($housing_budget)
                );
            }
        } else if ($type === 'sheriff' && isset($scorecard['police_funding'])) {
            if (isset($scorecard['police_funding']['police_budget']) || isset($scorecard['police_funding']['jail_budget'])) {
                $police_budget = isset($scorecard['police_funding']['police_budget']) ? $scorecard['police_funding']['police_budget'] : 0;
                $jail_budget = isset($scorecard['police_funding']['jail_budget']) ? $scorecard['police_funding']['jail_budget'] : 0;

                $output['datasets'][] = array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Police & Jail',
                    'backgroundColor' => '#dc4646',
                    'borderWidth' => 0,
                    'data' => array($police_budget + $jail_budget)
                );
            }

            if (isset($scorecard['police_funding']['health_budget'])) {
                $health_budget = $scorecard['police_funding']['health_budget'];
                $output['datasets'][] = array(
                    'minBarLength' => 5,
                    'maxBarThickness' => 20,
                    'label' => 'Health',
                    'backgroundColor' => '#58595b',
                    'borderWidth' => 0,
                    'data' => array($health_budget)
                );
            }
        }

        return json_encode($output);
    }
}

/**
 * Get Police Funding Chart
 *
 * @param object $funding
 *
 * @return object
 */
if (!function_exists('getPoliceFundingChart')) {
    function getPoliceFundingChart($funding) {
        $labels = array();
        $police = array();
        $housing = array();
        $health = array();
        $corrections = array();

        if (isset($funding['police_budget_2010']) || isset($funding['housing_budget_2010']) || isset($funding['health_budget_2010']) || isset($funding['corrections_budget_2010'])) {
            $labels[] = '2010';
            $police[] = isset($funding['police_budget_2010']) ? intval($funding['police_budget_2010']) : null;
            $housing[] = isset($funding['housing_budget_2010']) ? intval($funding['housing_budget_2010']) : null;
            $health[] = isset($funding['health_budget_2010']) ? intval($funding['health_budget_2010']) : null;
            $corrections[] = isset($funding['corrections_budget_2010']) ? intval($funding['corrections_budget_2010']) : null;
        }

        if (isset($funding['police_budget_2011']) || isset($funding['housing_budget_2011']) || isset($funding['health_budget_2011']) || isset($funding['corrections_budget_2011'])) {
            $labels[] = '2011';
            $police[] = isset($funding['police_budget_2011']) ? intval($funding['police_budget_2011']) : null;
            $housing[] = isset($funding['housing_budget_2011']) ? intval($funding['housing_budget_2011']) : null;
            $health[] = isset($funding['health_budget_2011']) ? intval($funding['health_budget_2011']) : null;
            $corrections[] = isset($funding['corrections_budget_2011']) ? intval($funding['corrections_budget_2011']) : null;
        }

        if (isset($funding['police_budget_2012']) || isset($funding['housing_budget_2012']) || isset($funding['health_budget_2012']) || isset($funding['corrections_budget_2012'])) {
            $labels[] = '2012';
            $police[] = isset($funding['police_budget_2012']) ? intval($funding['police_budget_2012']) : null;
            $housing[] = isset($funding['housing_budget_2012']) ? intval($funding['housing_budget_2012']) : null;
            $health[] = isset($funding['health_budget_2012']) ? intval($funding['health_budget_2012']) : null;
            $corrections[] = isset($funding['corrections_budget_2012']) ? intval($funding['corrections_budget_2012']) : null;
        }

        if (isset($funding['police_budget_2013']) || isset($funding['housing_budget_2013']) || isset($funding['health_budget_2013']) || isset($funding['corrections_budget_2013'])) {
            $labels[] = '2013';
            $police[] = isset($funding['police_budget_2013']) ? intval($funding['police_budget_2013']) : null;
            $housing[] = isset($funding['housing_budget_2013']) ? intval($funding['housing_budget_2013']) : null;
            $health[] = isset($funding['health_budget_2013']) ? intval($funding['health_budget_2013']) : null;
            $corrections[] = isset($funding['corrections_budget_2013']) ? intval($funding['corrections_budget_2013']) : null;
        }

        if (isset($funding['police_budget_2014']) || isset($funding['housing_budget_2014']) || isset($funding['health_budget_2014']) || isset($funding['corrections_budget_2014'])) {
            $labels[] = '2014';
            $police[] = isset($funding['police_budget_2014']) ? intval($funding['police_budget_2014']) : null;
            $housing[] = isset($funding['housing_budget_2014']) ? intval($funding['housing_budget_2014']) : null;
            $health[] = isset($funding['health_budget_2014']) ? intval($funding['health_budget_2014']) : null;
            $corrections[] = isset($funding['corrections_budget_2014']) ? intval($funding['corrections_budget_2014']) : null;
        }

        if (isset($funding['police_budget_2015']) || isset($funding['housing_budget_2015']) || isset($funding['health_budget_2015']) || isset($funding['corrections_budget_2015'])) {
            $labels[] = '2015';
            $police[] = isset($funding['police_budget_2015']) ? intval($funding['police_budget_2015']) : null;
            $housing[] = isset($funding['housing_budget_2015']) ? intval($funding['housing_budget_2015']) : null;
            $health[] = isset($funding['health_budget_2015']) ? intval($funding['health_budget_2015']) : null;
            $corrections[] = isset($funding['corrections_budget_2015']) ? intval($funding['corrections_budget_2015']) : null;
        }

        if (isset($funding['police_budget_2016']) || isset($funding['housing_budget_2016']) || isset($funding['health_budget_2016']) || isset($funding['corrections_budget_2016'])) {
            $labels[] = '2016';
            $police[] = isset($funding['police_budget_2016']) ? intval($funding['police_budget_2016']) : null;
            $housing[] = isset($funding['housing_budget_2016']) ? intval($funding['housing_budget_2016']) : null;
            $health[] = isset($funding['health_budget_2016']) ? intval($funding['health_budget_2016']) : null;
            $corrections[] = isset($funding['corrections_budget_2016']) ? intval($funding['corrections_budget_2016']) : null;
        }

        if (isset($funding['police_budget_2017']) || isset($funding['housing_budget_2017']) || isset($funding['health_budget_2017']) || isset($funding['corrections_budget_2017'])) {
            $labels[] = '2017';
            $police[] = isset($funding['police_budget_2017']) ? intval($funding['police_budget_2017']) : null;
            $housing[] = isset($funding['housing_budget_2017']) ? intval($funding['housing_budget_2017']) : null;
            $health[] = isset($funding['health_budget_2017']) ? intval($funding['health_budget_2017']) : null;
            $corrections[] = isset($funding['corrections_budget_2017']) ? intval($funding['corrections_budget_2017']) : null;
        }

        if (isset($funding['police_budget_2018']) || isset($funding['housing_budget_2018']) || isset($funding['health_budget_2018']) || isset($funding['corrections_budget_2018'])) {
            $labels[] = '2018';
            $police[] = isset($funding['police_budget_2018']) ? intval($funding['police_budget_2018']) : null;
            $housing[] = isset($funding['housing_budget_2018']) ? intval($funding['housing_budget_2018']) : null;
            $health[] = isset($funding['health_budget_2018']) ? intval($funding['health_budget_2018']) : null;
            $corrections[] = isset($funding['corrections_budget_2018']) ? intval($funding['corrections_budget_2018']) : null;
        }

        if (isset($funding['police_budget_2019']) || isset($funding['housing_budget_2019']) || isset($funding['health_budget_2019']) || isset($funding['corrections_budget_2019'])) {
            $labels[] = '2019';
            $police[] = isset($funding['police_budget_2019']) ? intval($funding['police_budget_2019']) : null;
            $housing[] = isset($funding['housing_budget_2019']) ? intval($funding['housing_budget_2019']) : null;
            $health[] = isset($funding['health_budget_2019']) ? intval($funding['health_budget_2019']) : null;
            $corrections[] = isset($funding['corrections_budget_2019']) ? intval($funding['corrections_budget_2019']) : null;
        }

        if (isset($funding['police_budget_2020']) || isset($funding['housing_budget_2020']) || isset($funding['health_budget_2020']) || isset($funding['corrections_budget_2020'])) {
            $labels[] = '2020';
            $police[] = isset($funding['police_budget_2020']) ? intval($funding['police_budget_2020']) : null;
            $housing[] = isset($funding['housing_budget_2020']) ? intval($funding['housing_budget_2020']) : null;
            $health[] = isset($funding['health_budget_2020']) ? intval($funding['health_budget_2020']) : null;
            $corrections[] = isset($funding['corrections_budget_2020']) ? intval($funding['corrections_budget_2020']) : null;
        }

        if (isset($funding['police_budget_2021']) || isset($funding['housing_budget_2021']) || isset($funding['health_budget_2021']) || isset($funding['corrections_budget_2021'])) {
            $labels[] = '2021';
            $police[] = isset($funding['police_budget_2021']) ? intval($funding['police_budget_2021']) : null;
            $housing[] = isset($funding['housing_budget_2021']) ? intval($funding['housing_budget_2021']) : null;
            $health[] = isset($funding['health_budget_2021']) ? intval($funding['health_budget_2021']) : null;
            $corrections[] = isset($funding['corrections_budget_2021']) ? intval($funding['corrections_budget_2021']) : null;
        }

        if (isset($funding['police_budget_2022']) || isset($funding['housing_budget_2022']) || isset($funding['health_budget_2022']) || isset($funding['corrections_budget_2022'])) {
            $labels[] = '2022';
            $police[] = isset($funding['police_budget_2022']) ? intval($funding['police_budget_2022']) : null;
            $housing[] = isset($funding['housing_budget_2022']) ? intval($funding['housing_budget_2022']) : null;
            $health[] = isset($funding['health_budget_2022']) ? intval($funding['health_budget_2022']) : null;
            $corrections[] = isset($funding['corrections_budget_2022']) ? intval($funding['corrections_budget_2022']) : null;
        }

        if (isset($funding['police_budget_2023']) || isset($funding['housing_budget_2023']) || isset($funding['health_budget_2023']) || isset($funding['corrections_budget_2023'])) {
            $labels[] = '2023';
            $police[] = isset($funding['police_budget_2023']) ? intval($funding['police_budget_2023']) : null;
            $housing[] = isset($funding['housing_budget_2023']) ? intval($funding['housing_budget_2023']) : null;
            $health[] = isset($funding['health_budget_2023']) ? intval($funding['health_budget_2023']) : null;
            $corrections[] = isset($funding['corrections_budget_2023']) ? intval($funding['corrections_budget_2023']) : null;
        }

        return json_encode(array(
            'labels' => $labels,
            'police' => $police,
            'housing' => $housing,
            'health' => $health,
            'corrections' => $corrections
        ));
    }
}

/**
 * Sort Grades
 *
 * @param object $grades
 *
 * @return object
 */
if (!function_exists('sortGrades')) {
    function sortGrades($grades) {
        if (!is_array($grades)) {
            return array();
        }

        $complete = array();
        $incomplete = array();

        foreach($grades as $grade) {
            if ($grade['complete']) {
                $complete[] = $grade;
            } else {
                $incomplete[] = $grade;
            }
        }

        usort($complete, function($a, $b) {
            return $a['overall_score'] > $b['overall_score'];
        });

        usort($incomplete, function($a, $b) {
            return $a['overall_score'] > $b['overall_score'];
        });

        return array(
            'complete' => $complete,
            'incomplete' => $incomplete,
            'all' => array_merge($complete, $incomplete)
        );
    }
}

if (!function_exists('encodeNDJSON')) {
    function encodeNDJSON($data) {
        $nd_json = '';

        array_walk($data, function ($item) use (&$nd_json) {
            $nd_json .= json_encode($item, JSON_UNESCAPED_SLASHES) . PHP_EOL;
        });

        return $nd_json;
    }
}

if (!function_exists('bytesToHuman')) {
    function bytesToHuman($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }
}

/**
 * Mapbox Update - PUT Mapbox Tiles from GeoJSON-LD
 *
 * @param string $url
 * @param string $file_path
 * @return void
 */
if (!function_exists('mapBoxUpdate')) {
    function mapBoxUpdate($url, $file_path) {
        $file = new CURLFile(realpath($file_path));

        if (!$file) {
            return array(
                'success' => false,
                'response' => 'Unable to Generate File for Upload'
            );
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, array ('file' => $file));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $curl_error = curl_error($ch);
        }

        curl_close($ch);

        if (isset($curl_error)) {
            return array(
                'success' => false,
                'response' => $curl_error
            );
        } else if ($result === FALSE) {
            return array(
                'success' => false,
                'response' => 'Failed to Update Mapbox Tiles.'
            );
        } else {
            $response = json_decode($result, true);

            if (!$response) {
                return array(
                    'success' => false,
                    'response' => 'No Response from Mapbox'
                );
            } else if (isset($response['message'])) {
                return array(
                    'success' => false,
                    'response' => $response['message']
                );
            } else {
                return array(
                    'success' => true,
                    'response' => $response
                );
            }
        }
    }
}

/**
 * Mapbox Publish - Once Tiles are Updated, this will Publish
 *
 * @param string $url
 * @return void
 */
if (!function_exists('mapBoxPublish')) {
    function mapBoxPublish($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $result = curl_exec($ch);

        if (curl_errno($ch)) {
            $curl_error = curl_error($ch);
        }

        curl_close($ch);

        if (isset($curl_error)) {
            return array(
                'success' => false,
                'response' => $curl_error
            );
        } else if ($result === FALSE) {
            return array(
                'success' => false,
                'response' => 'Failed to Publish Mapbox Tiles.'
            );
        } else {
            $response = json_decode($result, true);

            if (!$response) {
                return array(
                    'success' => false,
                    'response' => 'No Response from Mapbox'
                );
            } else if (isset($response['message']) && !isset($response['jobId'])) {
                return array(
                    'success' => false,
                    'response' => $response['message']
                );
            } else {
                return array(
                    'success' => true,
                    'response' => $response
                );
            }
        }
    }
}
