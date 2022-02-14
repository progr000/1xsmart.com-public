<?php

namespace common\helpers;

use Yii;
//use yii\helpers\ArrayHelper;
//use common\helpers\JSMin;

/**
 * This is the static Functions.
 *
 */
class Functions
{
    const IS_DEBUG = true;

    /**
     * @param $str
     */
    public static function debugEcho($str)
    {
        if (self::IS_DEBUG) {
            echo $str;
        }
    }

    private static $start_time;

    /**
     * @return float
     */
    private static function get_microtime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return (doubleval($usec) + doubleval($sec));
    }

    public static function start_timer()
    {
        self::$start_time = self::get_microtime();
    }

    /**
     * @return float
     */
    public static function get_execute_time()
    {
        return (self::get_microtime() - self::$start_time);
    }

    /**
     * prepared queryString
     * @param string $simbol - first simbol before queryString  & | ?
     * @param array $exclude - name of params of queryString wich were excluded from her
     * @return string - queryString
     */
    public static function prepareQS($exclude = [], $simbol = '&')
    {
        if (!in_array($simbol, ['&', '?']))
            $simbol = '&';

        $parsms = Yii::$app->request->queryParams;
        foreach ($exclude as $v)
            unset($parsms[$v]);

        if (sizeof($parsms) > 0)
            return $simbol . http_build_query($parsms);
        else
            return "";
    }

    /**
     * @param array $color_name_array it's array like this Users::statusParams(); [1=>['color'=>'any_color', 'name'=>'any_name'], 2=>[]...]
     * @return string
     */
    public static function getLegend($color_name_array)
    {
        $str = "<b>Interpretation of statuses:&nbsp;</b>";
        foreach ($color_name_array as $k => $v) {
            $str .= '<span class="badge" style="background-color: ' . $v['color'] . '">&nbsp;</span> ' . $v['name'] . ';&nbsp;&nbsp;';
        }
        return $str;
    }

    /**
     * Function returns array of dates for search
     *
     * @return array
     */
    public static function dateInfo()
    {
        $d['today_begin'] = date('Y-m-d', time()) . " 00:00:00";
        $d['today_end'] = date('Y-m-d', time()) . " 23:59:59";

        $day_week = intval(date('N', time())); // 1-пн, 2-вт ... 7-вс
        $time_first_day_week = ($day_week > 1) ? time() - ($day_week - 1) * 86400 : time();
        $d['week_begin'] = date('Y-m-d', $time_first_day_week) . " 00:00:00";
        $d['week_end'] = date('Y-m-d', time()) . " 23:59:59";

        $day_month = intval(date('j', time()));
        $time_first_day_mnth = ($day_month > 1) ? time() - ($day_month - 1) * 86400 : time();
        $d['mnth_begin'] = date('Y-m-d', $time_first_day_mnth) . " 00:00:00";
        $d['mnth_end'] = date('Y-m-d', time()) . " 23:59:59";

        return $d;
    }

    /**
     * @param $code_period
     * @return string
     */
    public static function forPeriod($code_period)
    {
        switch ($code_period) {
            case 'day':
                return "Per day: ";
                break;
            case 'week':
                return "Per week: ";
                break;
            case 'mnth':
                return "Per month: ";
                break;
            case 'now':
                return "Now: ";
                break;
            case 'for24hours':
                return "Per 24 hours: ";
                break;
            default:
                return $code_period;
                break;
        }
    }

    public static function HttpGet($url, $user = null, $passwd = null, $timeout = 30, $headers = [])
    {
        if (sizeof($headers) == 0) {
            $headers = ["Accept-Language: en"];
        }
        $ch = curl_init();    // initialize curl handle
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); // times out after 40s
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($ch, CURLOPT_HEADER, true);
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, $str); // add POST fields
        if ($user && $passwd) {
            curl_setopt($ch, CURLOPT_USERPWD, "{$user}:{$passwd}");
        }
        $answer = curl_exec($ch);// run the whole process
        curl_close($ch);

        return $answer;
    }

    public static function getBrowserByUserAgent($ua)
    {
        $ua = mb_strtolower($ua);
        if (strrpos($ua, 'firefox')) {
            return 'firefox';
        }
        if (strrpos($ua, 'opera')) {
            return 'opera';
        }
        if (strrpos($ua, 'netscape')) {
            return 'netscape';
        }
        if (strrpos($ua, 'chrome')) {
            return 'chrome';
        }
        if (strrpos($ua, 'safari')) {
            return 'safari';
        }
        if (strrpos($ua, 'msie') || strrpos($ua, "trident") || strrpos($ua, "rv:11")) {
            return 'msie';
        }
        return null;
    }

    public static function getOsTypeByUserAgent($ua)
    {
        $ua = mb_strtolower($ua);
        if (strrpos($ua, 'android')) {
            return 'Android';
        }
        if (strrpos($ua, 'linux')) {
            return 'Linux';
        }
        if (strrpos($ua, 'ios') || strrpos($ua, 'mac')) {
            return 'MacOS';
        }
        if (strrpos($ua, 'windows') || strrpos($ua, 'msie') || strrpos($ua, "trident") || strrpos($ua, "rv:11")) {
            return 'Windows';
        }
        return 'unknown';
    }

    /**
     * @param integer $bytes
     * @param integer $decimal_digits
     * @param string $force ('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb')
     * @param string $space_between
     * @param bool $no_power
     * @return string
     */
    //public static function file_size_format($bytes, $format = '', $force = '')
    public static function file_size_format($bytes, $decimal_digits = 2, $force = '', $space_between = ' ', $no_power = false)
    {
        //$defaultFormat = '%s%s';
        //if (strlen($format) == 0) { $format = $defaultFormat; }
        $bytes = max(0, round($bytes));
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        $power = array_search($force, $units);
        if ($power === false) {
            $power = ($bytes > 0) ? floor(log($bytes, 1024)) : 0;
        }
        //return sprintf($format, $bytes / pow(1024, $power), $units[$power]);
        //return sprintf($format, round($bytes / pow(1024, $power), 2), $units[$power]);
        if ($no_power) {
            return '' . number_format(round($bytes / pow(1024, $power), 2), $decimal_digits, '.', '');
        } else {
            return '' . number_format(round($bytes / pow(1024, $power), 2), $decimal_digits, '.', '') . $space_between . $units[$power];
        }
    }

    /**
     * return bool
     */
    public static function isIE()
    {
        $ua = \Yii::$app->request->useragent;
        if (strrpos($ua, 'msie') || strrpos($ua, "trident") || strrpos($ua, "rv:11")) {
            return true;
        }
        return false;
    }

    public static function getOsExtendedByUserAgent($ua)
    {
        $ua = mb_strtolower($ua);
        //var_dump(preg_match("/(Linux|X11)/i", $ua)); exit;
        if (preg_match("/Windows/i", $ua)) {
            if (preg_match("/(Windows 10.0|Windows NT 10.0)/i", $ua)) {
                return 'Windows 10';
            }
            if (preg_match("/(Windows 8.1|Windows NT 6.3)/i", $ua)) {
                return 'Windows 8.1';
            }
            if (preg_match("/(Windows 8|Windows NT 6.2)/i", $ua)) {
                return 'Windows 8';
            }
            if (preg_match("/(Windows 7|Windows NT 6.1)/i", $ua)) {
                return 'Windows 7';
            }
            if (preg_match("/Windows NT 6.0/i", $ua)) {
                return 'Windows Vista';
            }
            if (preg_match("/Windows NT 5.2/i", $ua)) {
                return 'Windows Server 2003';
            }
            if (preg_match("/(Windows NT 5.1|Windows XP)/i", $ua)) {
                return 'Windows XP';
            }
            if (preg_match("/(Windows NT 5.0|Windows 2000)/i", $ua)) {
                return 'Windows 2000';
            }
            if (preg_match("/(Win 9x 4.90|Windows ME)/i", $ua)) {
                return 'Windows ME';
            }
            if (preg_match("/(Windows 98|Win98)/i", $ua)) {
                return 'Windows 98';
            }
            if (preg_match("/(Windows 95|Win95|Windows_95)/i", $ua)) {
                return 'Windows 95';
            }
            if (preg_match("/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/i", $ua)) {
                return 'Windows NT 4.0';
            }
            if (preg_match("/Windows CE/i", $ua)) {
                return 'Windows CE';
            }
            if (preg_match("/Win16/i", $ua)) {
                return 'Windows 3.11';
            }
            return 'Windows';
        }
        if (preg_match("/Android/i", $ua)) {
            return 'Android';
        }
        if (preg_match("/OpenBSD/i", $ua)) {
            return 'Open BSD';
        }
        if (preg_match("/SunOS/i", $ua)) {
            return 'Sun OS';
        }
        if (preg_match("/(Linux|X11)/i", $ua)) {
            if (preg_match("/Kubuntu/i", $ua)) {
                return 'Kubuntu';
            }
            if (preg_match("/Ubuntu/i", $ua)) {
                return 'Ubuntu';
            }
            if (preg_match("/Debian/i", $ua)) {
                return 'Debian';
            }
            if (preg_match("/Red/i", $ua)) {
                return 'Red Hat';
            }
            if (preg_match("/Cent/i", $ua)) {
                return 'Linux CentOS';
            }
            if (preg_match("/Mint/i", $ua)) {
                return 'Linux Mint';
            }
            if (preg_match("/SUSE/i", $ua)) {
                return 'openSUSE';
            }
            if (preg_match("/Fedora/i", $ua)) {
                return 'Fedora';
            }
            return 'Linux';
        }
        if (preg_match("/(iPhone|iPad|iPod)/i", $ua)) {
            return 'iOS';
        }
        if (preg_match("/Mac OS X/i", $ua)) {
            return 'Mac OS X';
        }
        if (preg_match("/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/i", $ua)) {
            return 'Mac OS';
        }
        if (preg_match("/QNX/i", $ua)) {
            return 'QNX';
        }
        if (preg_match("/UNIX/i", $ua)) {
            return 'UNIX';
        }
        if (preg_match("/BeOS/i", $ua)) {
            return 'BeOS';
        }
        if (preg_match("/OS\/2/i", $ua)) {
            return 'OS/2';
        }
        if (preg_match("/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/i", $ua)) {
            return 'Search Bot\'';
        }
        return 'unknown';
    }

    /**
     * @param string $nAgt
     * @return array
     */
    public static function clientDetection($nAgt)
    {
        $unknown = '';
        $nAgt = mb_strtolower($nAgt);

        /* init */
        $browser = $unknown;
        $browserVersion = $unknown;
        //$browserMajorVersion = $unknown;
        //$mobile = false;
        $os = $unknown;
        $osVersion = $unknown;


        /* detect is mobile or not */
        $mobile = preg_match("/mobile|mini|fennec|android|ipad|ipod|iphone/", $nAgt);


        /* detect browser name and version */
        // Opera
        if (($verOffset = mb_strpos($nAgt, 'opera')) !== false) {
            $browser = 'Opera';
            $browserVersion = mb_substr($nAgt, $verOffset + 6);
            if (($verOffset = mb_strpos($nAgt, 'version')) !== false) {
                $browserVersion = mb_substr($nAgt, $verOffset + 8);
            }
        } // MSIE
        elseif (($verOffset = mb_strpos($nAgt, 'msie')) !== false) {
            $browser = 'Microsoft Internet Explorer';
            $browserVersion = mb_substr($nAgt, $verOffset + 5);
        } // Chrome
        elseif (($verOffset = mb_strpos($nAgt, 'chrome')) !== false) {
            $browser = 'Chrome';
            $browserVersion = mb_substr($nAgt, $verOffset + 7);
        } // Safari
        elseif (($verOffset = mb_strpos($nAgt, 'safari')) !== false) {
            $browser = 'Safari';
            $browserVersion = mb_substr($nAgt, $verOffset + 7);
            if (($verOffset = mb_strpos($nAgt, 'version')) !== false) {
                $browserVersion = mb_substr($nAgt, $verOffset + 8);
            }
        } // Firefox
        elseif (($verOffset = mb_strpos($nAgt, 'firefox')) !== false) {
            $browser = 'Firefox';
            $browserVersion = mb_substr($nAgt, $verOffset + 8);
        } // MSIE 11+
        elseif (($verOffset = mb_strpos($nAgt, 'trident/')) !== false) {
            $browser = 'Microsoft Internet Explorer';
            $browserVersion = mb_substr($nAgt, mb_strrpos($nAgt, 'rv:') + 3);
        } // Other browsers
        elseif (($nameOffset = mb_strrpos($nAgt, ' ') + 1) < ($verOffset = mb_strrpos($nAgt, '/'))) {
            $len = mb_strpos($nAgt, '/', $nameOffset) - $nameOffset;
            $browser = mb_substr($nAgt, $nameOffset, $len);
            $browserVersion = mb_substr($nAgt, $verOffset + 1);
        }

        // trim the version string
        if (($ix = mb_strpos($browserVersion, ';')) !== false) $browserVersion = mb_substr($browserVersion, 0, $ix);
        if (($ix = mb_strpos($browserVersion, ' ')) !== false) $browserVersion = mb_substr($browserVersion, 0, $ix);
        if (($ix = mb_strpos($browserVersion, ')')) !== false) $browserVersion = mb_substr($browserVersion, 0, $ix);

        $browserMajorVersion = intval($browserVersion, 10);


        /* detect os and it version */

        $clientStrings = [
            ['s' => 'Windows 10', 'r' => "/(Windows 10.0|Windows NT 10.0)/"],
            ['s' => 'Windows 8.1', 'r' => "/(Windows 8.1|Windows NT 6.3)/"],
            ['s' => 'Windows 8', 'r' => "/(Windows 8|Windows NT 6.2)/"],
            ['s' => 'Windows 7', 'r' => "/(Windows 7|Windows NT 6.1)/"],
            ['s' => 'Windows Vista', 'r' => "/Windows NT 6.0/"],
            ['s' => 'Windows Server 2003', 'r' => "/Windows NT 5.2/"],
            ['s' => 'Windows XP', 'r' => "/(Windows NT 5.1|Windows XP)/"],
            ['s' => 'Windows 2000', 'r' => "/(Windows NT 5.0|Windows 2000)/"],
            ['s' => 'Windows ME', 'r' => "/(Win 9x 4.90|Windows ME)/"],
            ['s' => 'Windows 98', 'r' => "/(Windows 98|Win98)/"],
            ['s' => 'Windows 95', 'r' => "/(Windows 95|Win95|Windows_95)/"],
            ['s' => 'Windows NT 4.0', 'r' => "/(Windows NT 4.0|WinNT4.0|WinNT|Windows NT)/"],
            ['s' => 'Windows CE', 'r' => "/Windows CE/"],
            ['s' => 'Windows 3.11', 'r' => "/Win16/"],
            ['s' => 'Android', 'r' => "/Android/"],
            ['s' => 'Open BSD', 'r' => "/OpenBSD/"],
            ['s' => 'Sun OS', 'r' => "/SunOS/"],
            ['s' => 'Linux', 'r' => "/(Linux|X11)/"],
            ['s' => 'iOS', 'r' => "/(iPhone|iPad|iPod)/"],
            ['s' => 'Mac OS X', 'r' => "/Mac OS X/"],
            ['s' => 'Mac OS', 'r' => "/(MacPPC|MacIntel|Mac_PowerPC|Macintosh)/"],
            ['s' => 'QNX', 'r' => "/QNX/"],
            ['s' => 'UNIX', 'r' => "/UNIX/"],
            ['s' => 'BeOS', 'r' => "/BeOS/"],
            ['s' => 'OS/2', 'r' => "/OS\/2/"],
            ['s' => 'Search Bot', 'r' => "/(nuhk|Googlebot|Yammybot|Openbot|Slurp|MSNBot|Ask Jeeves\/Teoma|ia_archiver)/"],
        ];
        foreach ($clientStrings as $cs) {
            $cs['r'] = mb_strtolower($cs['r']);
            if (preg_match($cs['r'] . "i", $nAgt)) {
                $os = $cs['s'];
                break;
            }
        }


        if (preg_match("/Windows/i", $os)) {
            $osVersion = str_replace("Windows ", "", $os);
            $os = 'Windows';
        }

        //$test = preg_match("/Mac OS X ([\.\_\d]+)/i", $nAgt, $matches);
        //var_dump($matches);
        //exit;

        switch ($os) {
            case 'Mac OS X':
                preg_match("/Mac OS X ([\.\_\d]+)/i", $nAgt, $matches);
                if (isset($matches[1])) $osVersion = str_replace('_', '.', $matches[1]);
                break;
            case 'Android':
                preg_match("/Android ([\.\_\d]+)/i", $nAgt, $matches);
                if (isset($matches[1])) $osVersion = str_replace('_', '.', $matches[1]);
                break;
            case 'iOS':
                preg_match("/OS ((\d+)\_(\d+)\_?(\d+)?)/i", $nAgt, $matches);
                if (isset($matches[1])) $osVersion = str_replace('_', '.', $matches[1]);
                break;
            case 'Linux' :
                if (strpos($nAgt, 'kubuntu') !== false) $osVersion = 'Kubuntu';
                elseif (strpos($nAgt, 'ubuntu') !== false) $osVersion = 'Ubuntu';
                elseif (strpos($nAgt, 'debian') !== false) $osVersion = 'Debian';
                elseif (strpos($nAgt, 'red') !== false) $osVersion = 'Red Hat';
                elseif (strpos($nAgt, 'cent') !== false) $osVersion = 'CentOS';
                elseif (strpos($nAgt, 'mint') !== false) $osVersion = 'Mint';
                elseif (strpos($nAgt, 'suse') !== false) $osVersion = 'openSUSE';
                elseif (strpos($nAgt, 'fedora') !== false) $osVersion = 'Fedora';
                break;
        }

        $osMajorVersion = intval($osVersion, 10);

        return [
            'mobile' => boolval($mobile),
            'browser' => [
                'name' => $browser,
                'version' => $browserVersion,
                'majorVersion' => $browserMajorVersion,
            ],
            'os' => [
                'name' => $os,
                'version' => $osVersion,
                'majorVersion' => $osMajorVersion,
            ],
        ];
    }

    /**
     * @param string $str
     * @param integer $length
     * @return string
     */
    public static function cutUtf8StrToLengthBites($str, $length)
    {
        while (mb_strlen($str, '8bit') > $length) {
            $str = mb_substr($str, 1);
        }
        return $str;
    }

    /**
     * @param string $format
     * @param string $pg_date
     * @return string
     */
    public static function formatPostgresDate($format, $pg_date)
    {
        return date($format, strtotime($pg_date));
    }

    /**
     * @param int $timestamp
     * @param int $timeZone
     * @return int
     */
    public static function getTimestampEndOfDayByTimestamp($timestamp, $timeZone = 0)
    {
        $beginOfDay = strtotime("midnight", $timestamp);
        $endOfDay = strtotime("tomorrow", $beginOfDay) - 1;
        return $endOfDay + $timeZone;
        /*
        return  gmmktime(date(23,  $timestamp),
                         date(59,  $timestamp),
                         date(59,  $timestamp),
                         date("n", $timestamp),
                         date("j", $timestamp),
                         date("Y", $timestamp));
        */
    }

    /**
     * @param int $timestamp
     * @param int $timeZone
     * @return int
     */
    public static function getTimestampBeginOfDayByTimestamp($timestamp, $timeZone = 0)
    {
        $beginOfDay = strtotime("midnight", $timestamp);
        return $beginOfDay + $timeZone;
        /*
        return  gmmktime(date(0,   $timestamp),
                         date(0,   $timestamp),
                         date(0,   $timestamp),
                         date("n", $timestamp),
                         date("j", $timestamp),
                         date("Y", $timestamp));
        */
    }

    /**
     * @param string $email
     * @return string
     */
    public static function getNameFromEmail($email)
    {
        return mb_strtoupper(mb_substr($email, 0, 1)) . mb_substr($email, 1, mb_strrpos($email, '@') - 1);
    }

    /**
     * @param $timestamp
     * @return int
     */
    public static function getDayOfWeek($timestamp)
    {
        $d = date('N', $timestamp);
        //$d = intval($d);
        //if ($d == 0) { $d = 7; }
        return $d;
    }

    /**
     * @param int $week_day
     * @param string $in
     * @return string
     */
    public static function getTextWeekDay($week_day, $in = "")
    {
        $days = [
            0 => Yii::t('app/common', "{$in}sunday"),
            1 => Yii::t('app/common', "{$in}monday"),
            2 => Yii::t('app/common', "{$in}tuesday"),
            3 => Yii::t('app/common', "{$in}wednesday"),
            4 => Yii::t('app/common', "{$in}thursday"),
            5 => Yii::t('app/common', "{$in}friday"),
            6 => Yii::t('app/common', "{$in}saturday"),
            7 => Yii::t('app/common', "{$in}sunday"),
        ];
        return isset($days[$week_day]) ? $days[$week_day] : 'unknown';
        //return $days[$week_day];
    }

    /**
     * @param int $minutes
     * @return string
     */
    public static function left_minutes_ru_text($minutes)
    {
        $minutes = intval($minutes);

        if ($minutes == 1) {
            return [
                Yii::t('app/common', 'minutu_1'),
                $minutes . ' ' . Yii::t('app/common', 'minutu_1'),
                Yii::t('app/common', 'minuta_1'),
            ];
        }

        if ($minutes >= 10 && $minutes <= 20) {
            return [
                Yii::t('app/common', 'minut'),
                $minutes . ' ' . Yii::t('app/common', 'minut'),
                Yii::t('app/common', 'minut'),
            ];
        }

        $test = $minutes % 10;

        if ($test == 1) {
            return [
                Yii::t('app/common', 'minutu_many'),
                $minutes . ' ' . Yii::t('app/common', 'minutu_many'),
                Yii::t('app/common', 'minuta_many'),
            ];
        }

        if ($test >= 2 && $test <= 4) {
            return [
                Yii::t('app/common', 'minuti'),
                $minutes . ' ' . Yii::t('app/common', 'minuti'),
                Yii::t('app/common', 'minuti'),
            ];
        }

        return [
            Yii::t('app/common', 'minut'),
            $minutes . ' ' . Yii::t('app/common', 'minut'),
            Yii::t('app/common', 'minut'),
        ];
    }

    /**
     * @param int $days
     * @return array
     */
    public static function in_days_ru_text($days)
    {
        $days = intval($days);

        if ($days >= 10 && $days <= 20) {
            return [
                'дней',
            ];
        }

        $test = $days % 10;

        if ($test == 1) {
            return [
                'день',
            ];
        }

        if ($test >= 2 && $test <= 4) {
            return [
                'дня',
            ];
        }

        return [
            'дней',
        ];
    }

    /**
     * @param int $hours
     * @return array
     */
    public static function in_hours_ru_text($hours)
    {
        $hours = intval($hours);

        if ($hours == 1) {
            return [
                Yii::t('app/common', 'chas_1'),
                $hours . ' ' . Yii::t('app/common', 'chas_1'),
            ];
        }

        if ($hours >= 10 && $hours <= 20) {
            return [
                Yii::t('app/common', 'chasov'),
                $hours . ' '. Yii::t('app/common', 'chasov'),
            ];
        }

        $test = $hours % 10;

        if ($test == 1) {
            return [
                Yii::t('app/common', 'chas_many'),
                $hours . ' ' . Yii::t('app/common', 'chas_many'),
            ];
        }

        if ($test >= 2 && $test <= 4) {
            return [
                Yii::t('app/common', 'chasa'),
                $hours . ' ' . Yii::t('app/common', 'chasa'),
            ];
        }

        return [
            Yii::t('app/common', 'chasov'),
            $hours . ' '. Yii::t('app/common', 'chasov'),
        ];
    }

    /**
     * @param int $timestamp
     * @param int $timeZone
     * @return string
     */
    public static function getTextWhenNextLesson($timestamp, $timeZone = 0)
    {
        $week_day = date('N', $timestamp + $timeZone);

        $now = time();
        $today_left_seconds = (self::getTimestampEndOfDayByTimestamp($now) - $now) - $timeZone;
        //var_dump($today_left_seconds);
        $tomorrow_left_seconds = ($today_left_seconds + 3600 * 24) - $timeZone;
        //var_dump($tomorrow_left_seconds);

        $delta = $timestamp - time();
        $H = intval(date('G', $timestamp + $timeZone));
        //var_dump($delta);
        /* если занятие будет менее чем через час - "через N минут" */
        if ($delta < 60) {
            return Yii::t(
                'app/common',
                '<br />
                 <span class="highlight-c1 no-block">
                    <span id="minutes-left-text-before" class="no-block" data-translate="' . Yii::t('app/common', 'in_less_than_a') . '">' . Yii::t('app/common', 'in_less_than_a') . '</span>
                    <span class="no-block"> </span>
                    <span id="minutes-left-to-next-lesson"
                          class="no-block"
                          data-seconds-left="{seconds_left}"
                          data-lesson-utc-timestamp="{lesson_utc_timestamp}"></span>
                    <span class="no-block"> </span>
                    <span id="minutes-left-text-to-next-lesson" class="no-block">' . Yii::t('app/common', 'minutu') . '</span>
                </span>',
                [
                    'seconds_left' => $delta,
                    'lesson_utc_timestamp' => $timestamp,
                    'minutes' => 1,
                    'minutes_text' => self::left_minutes_ru_text(1)[0],
                ]
            );
        } elseif ($delta < 3600) {
            $left_minutes = intval(ceil($delta / 60));
            return Yii::t(
                'app/common',
                '<br />
                 <span class="highlight-c1 no-block">
                    <span id="minutes-left-text-before" class="no-block">' . Yii::t('app/common', 'through') . '</span>
                    <span class="no-block"> </span>
                    <span id="minutes-left-to-next-lesson"
                          class="no-block"
                          data-seconds-left="{seconds_left}"
                          data-lesson-utc-timestamp="{lesson_utc_timestamp}">{minutes}</span>
                    <span class="no-block"> </span>
                    <span id="minutes-left-text-to-next-lesson" class="no-block">{minutes_text}</span>
                </span>',
                [
                    'seconds_left' => $delta,
                    'lesson_utc_timestamp' => $timestamp,
                    'minutes' => $left_minutes,
                    'minutes_text' => self::left_minutes_ru_text($left_minutes)[0],
                ]
            );
        } elseif ($delta < $today_left_seconds) {
            if ($H == 0) {
                return Yii::t(
                    'app/common',
                    //'<br /><span class="highlight-c1 no-block">tomorrow</span> at <span class="highlight-c1 no-block">{hour}</span> o\'clock',
                    '<br /><span class="highlight-c1 no-block">' . Yii::t('app/common', 'tomorrow') . '</span> ' . Yii::t('app/common', 'at') . ' <span class="highlight-c1 no-block">{hour}</span>',
                    [
                        'hour' => date(Yii::$app->params['time_short_format'], $timestamp + $timeZone),
                        //'hour' => date('H', $timestamp + $timeZone),
                        //'hour_text' => self::in_hours_ru_text($H)[0]
                    ]
                );
            } else {
                return Yii::t(
                    'app/common',
                    '<br /><span class="highlight-c1 no-block">' . Yii::t('app/common', 'today') . '</span> ' . Yii::t('app/common', 'at') . ' <span class="highlight-c1 no-block">{hour}</span>',
                    [
                        'hour' => date(Yii::$app->params['time_short_format'], $timestamp + $timeZone),
                        //'hour' => date('H', $timestamp + $timeZone),
                        //'hour_text' => self::in_hours_ru_text($H)[0]
                    ]
                );
            }
        } elseif (($delta < $tomorrow_left_seconds) && ($H > 0)) {
            return Yii::t(
                'app/common',
                '<br /><span class="highlight-c1 no-block">' . Yii::t('app/common', 'tomorrow') . '</span> ' . Yii::t('app/common', 'at') . ' <span class="highlight-c1 no-block">{hour}</span>',
                [
                    'hour' => date(Yii::$app->params['time_short_format'], $timestamp + $timeZone),
                    //'hour' => date('H', $timestamp + $timeZone),
                    //'hour_text' => self::in_hours_ru_text($H)[0]
                ]
            );
        } else {
            return
                Yii::t('app/common', 'is_on_the') . " <br />" .
                '<span class="highlight-c1 no-block">' .
                self::getTextWeekDay($week_day, "Up_") . " " .
                date(Yii::$app->params['date_format'], $timestamp + $timeZone) .
                '</span>' .
                " " . Yii::t('app/common', "at") . " " .
                '<span class="highlight-c1 no-block">' .
                date(Yii::$app->params['time_short_format'], $timestamp + $timeZone) .
                '</span>';
        }
    }

    /**
     * Валидация даты для поля типа 'timestamp without time zone' в постгре
     * @param $date
     * @return bool
     */
    public static function checkDateIsValidForDB($date)
    {
        $date = str_replace([',', ';'], " ", $date);
        $date = trim($date);

        /** проверка даты */

        // Если дата в формате yyyy-m-d или yyyy.m.d
        if (preg_match("/^([\d]{4})[\-\.]([\d]{1,2})[\-\.]([\d]{1,2})(\s|,|;|$)/i", $date, $ma)) {
            //var_dump($ma);
            $y = intval($ma[1]);
            $m = intval($ma[2]);
            $d = intval($ma[3]);
            if ($ma[4] === "") {
                $eol = true;
            }
        }
        // Если дата в формате d-m-y или d.m.y
        if (preg_match("/^([\d]{1,2})[\-\.]([\d]{1,2})[\-\.]([\d]{1,2})(\s|,|;|$)/i", $date, $ma)) {
            //var_dump($ma);
            $y = intval($ma[3]);
            $m = intval($ma[2]);
            $d = intval($ma[1]);
            if ($ma[4] === "") {
                $eol = true;
            }
            if ($y < 70) {
                $y = ($y > 9) ? "20" . $y : "200" . $y;
            } else {
                $y = "19" . $y;
            }
        }

        // если нет числа, месяца или года, то ошибочная false
        if (!isset($y, $m, $d)) {
            return false;
        }
        // если пхп проверка даты не прошла то ввернем false
        if (!checkdate($m, $d, $y)) {
            return false;
        }
        // если проверка даты успега и дальше нет строки со временем то вернем true
        if (isset($eol)) {
            return true;
        }


        /** если же проверка даты успешная, дальше проверяем время */

        // постгре принимает любой из таких форматов времени:
        // Y-m-d 0000 | Y-m-d 9999
        // к дате будет добавлено количество секунд (если число нельзя интерпритировать как H:i) или проставлено время как H:i:0
        if (preg_match("/(\s|,|;)([\d]{4})$/i", $date, $ma)) {
            return true;
        }

        // постгре принимает любой из таких форматов времени:
        // Y-m-d 000000 | Y-m-d 999999
        // к дате будет добавлено количество секунд (если число нельзя интерпритировать как H:i:s) или проставлено время как H:i:s
        if (preg_match("/(\s|,|;)([\d]{6})$/i", $date, $ma)) {
            return true;
        }

        // Проверка времени в формате H:i:s+TZ || H:i:s-TZ
        if (preg_match("/(\s|,|;)([\d]{1,2})\:([\d]{1,2})\:([\d]{1,2})[\+\-]([\d]{0,2})$/i", $date, $ma)) {
            $h = intval($ma[2]);
            $i = intval($ma[3]);
            $s = intval($ma[4]);
            if ($h > 23 || $i > 59 || $s > 59) {
                return false;
            }

            return true;
        }

        // Проверка времени в формате H:i:s.milliseconds
        if (preg_match("/(\s|,|;)([\d]{1,2})\:([\d]{1,2})\:([\d]{1,2})\.([\d]{0,10})$/i", $date, $ma)) {
            $h = intval($ma[2]);
            $i = intval($ma[3]);
            $s = intval($ma[4]);
            if ($h > 23 || $i > 59 || $s > 59) {
                return false;
            }

            return true;
        }

        // Проверка времени в формате H:i:s
        if (preg_match("/(\s|,|;)([\d]{1,2})\:([\d]{1,2})\:([\d]{1,2})$/i", $date, $ma)) {
            $h = intval($ma[2]);
            $i = intval($ma[3]);
            $s = intval($ma[4]);
            if ($h > 23 || $i > 59 || $s > 59) {
                return false;
            }

            return true;
        }

        // Проверка времени в формате H:i
        if (preg_match("/(\s|,|;)([\d]{1,2})\:([\d]{1,2})$/i", $date, $ma)) {
            $h = intval($ma[2]);
            $i = intval($ma[3]);
            if ($h > 23 || $i > 59) {
                return false;
            }

            return true;
        }

        // Проверка времени в формате H:i:s pm|am
        if (preg_match("/(\s|,|;)([\d]{1,2})\:([\d]{1,2})\:([\d]{1,2})([\s|,|;]{0,20})(pm|am)$/i", $date, $ma)) {
            //var_dump($ma);
            $h = intval($ma[2]);
            $i = intval($ma[3]);
            $s = intval($ma[4]);
            if (mb_strtolower($ma[6]) == 'pm') $h += 12;
            //var_dump($h);
            if ($h > 23 || $i > 59 || $s > 59) {
                return false;
            }
            return true;
        }

        // Проверка времени в формате H:i pn|am
        if (preg_match("/(\s|,|;)([\d]{1,2})\:([\d]{1,2})([\s|,|;]{0,20})(pm|am)$/i", $date, $ma)) {
            //var_dump($ma);
            $h = intval($ma[2]);
            $i = intval($ma[3]);
            if (mb_strtolower($ma[5]) == 'pm') $h += 12;
            //var_dump($h);
            if ($h > 23 || $i > 59) {
                return false;
            }
            return true;
        }

        return false;
    }

    /**
     * @param string $field_name
     * @return array
     */
    public static function get_list_of_timezones($field_name = 'name'/*$locale='en'*/)
    {
        $tzs = [
            ['offset' => -43200, 'short_name' => 'ILW', 'offset_short_name' => 'GMT -12:00', 'name' => "(GMT-12:00) International Date Line West"],
            ['offset' => -39600, 'short_name' => 'MIS', 'offset_short_name' => 'GMT -11:00', 'name' => "(GMT-11:00) Midway Island, Samoa"],
            ['offset' => -36000, 'short_name' => 'HWI', 'offset_short_name' => 'GMT -10:00', 'name' => "(GMT-10:00) Hawaii"],
            ['offset' => -32400, 'short_name' => 'ALS', 'offset_short_name' => 'GMT -09:00', 'name' => "(GMT-09:00) Alaska"],
            ['offset' => -28800, 'short_name' => 'PTT', 'offset_short_name' => 'GMT -08:00', 'name' => "(GMT-08:00) Pacific Time (US and Canada); Tijuana"],
            ['offset' => -25200, 'short_name' => 'ARZ', 'offset_short_name' => 'GMT -07:00', 'name' => "(GMT-07:00) Chihuahua, La Paz, Mazatlan, Arizona, Mountain Time (US and Canada)"],
            ['offset' => -21600, 'short_name' => 'MEX', 'offset_short_name' => 'GMT -06:00', 'name' => "(GMT-06:00) Central Time (US and Canada), Saskatchewan, Guadalajara, Mexico City, Monterrey"],
            ['offset' => -18000, 'short_name' => 'IND', 'offset_short_name' => 'GMT -05:00', 'name' => "(GMT-05:00) Eastern Time (US and Canada), Indiana (East), Bogota, Lima, Quito"],
            ['offset' => -14400, 'short_name' => 'NYK', 'offset_short_name' => 'GMT -04:00', 'name' => "(GMT-04:00) America/New York EDT, Atlantic Time (Canada), Caracas, La Paz, Santiago"],
            //['offset' => -12600, 'short_name' => 'LBR', 'offset_short_name' => 'GMT -03:30', 'name' => "(GMT-03:30) Newfoundland and Labrador"],
            ['offset' => -10800, 'short_name' => 'GRL', 'offset_short_name' => 'GMT -03:00', 'name' => "(GMT-03:00) Greenland, Buenos Aires, Georgetown, Brasilia, Greenland"],
            ['offset' => -7200, 'short_name' => 'MAT', 'offset_short_name' => 'GMT -02:00', 'name' => "(GMT-02:00) Mid-Atlantic"],
            ['offset' => -3600, 'short_name' => 'CVI', 'offset_short_name' => 'GMT -01:00', 'name' => "(GMT-01:00) Azores, Cape Verde Islands"],
            ['offset' => 0, 'short_name' => 'GMT', 'offset_short_name' => 'GMT', 'name' => "(GMT) Greenwich Mean Time: Dublin, Edinburgh, Lisbon, London, Casablanca, Monrovia"],
            ['offset' => 3600, 'short_name' => 'BRL', 'offset_short_name' => 'GMT +01:00', 'name' => "(GMT+01:00) Europe/London BST, Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna"],
            ['offset' => 7200, 'short_name' => 'JER', 'offset_short_name' => 'GMT +02:00', 'name' => "(GMT+02:00) Europe/Amsterdam CEST, Jerusalem, Helsinki, Kiev, Riga, Sofia, Tallinn, Vilnius"],
            ['offset' => 10800, 'short_name' => 'MSK', 'offset_short_name' => 'GMT +03:00', 'name' => "(GMT+03:00) Europe/Moscow MSK, St. Petersburg, Volgograd, Kuwait, Riyadh, Baghdad"],
            // ['offset' => 12600,  'short_name' => 'TEH', 'offset_short_name' => 'GMT +03:30', 'name' => "(GMT+03:30) Tehran"],
            ['offset' => 14400, 'short_name' => 'TBL', 'offset_short_name' => 'GMT +04:00', 'name' => "(GMT+04:00) Abu Dhabi, Muscat, Baku, Tbilisi, Yerevan"],
            //['offset' => 16200,  'short_name' => 'KAB', 'offset_short_name' => 'GMT +04:30', 'name' => "(GMT+04:30) Kabul"],
            ['offset' => 18000, 'short_name' => 'EKT', 'offset_short_name' => 'GMT +05:00', 'name' => "(GMT+05:00) Ekaterinburg, Islamabad, Karachi, Tashkent"],
            //['offset' => 19800,  'short_name' => 'MUM', 'offset_short_name' => 'GMT +05:30', 'name' => "(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi"],
            //['offset' => 20700,  'short_name' => 'KTH', 'offset_short_name' => 'GMT +05:45', 'name' => "(GMT+05:45) Kathmandu"],
            ['offset' => 21600, 'short_name' => 'AST', 'offset_short_name' => 'GMT +06:00', 'name' => "(GMT+06:00) Astana, Dhaka, Sri Jayawardenepura, Almaty, Novosibirsk"],
            //['offset' => 23400,  'short_name' => 'YRG', 'offset_short_name' => 'GMT +06:30', 'name' => "(GMT+06:30) Yangon Rangoon"],
            ['offset' => 25200, 'short_name' => 'KRS', 'offset_short_name' => 'GMT +07:00', 'name' => "(GMT+07:00) Krasnoyarsk, Bangkok, Hanoi, Jakarta"],
            ['offset' => 28800, 'short_name' => 'IRK', 'offset_short_name' => 'GMT +08:00', 'name' => "(GMT+08:00) Irkutsk, Ulaanbaatar, Perth, Taipei, Singapore, Hong Kong SAR"],
            ['offset' => 32400, 'short_name' => 'YAK', 'offset_short_name' => 'GMT +09:00', 'name' => "(GMT+09:00) Yakutsk, Seoul, Osaka, Sapporo, Tokyo"],
            //['offset' => 34200,  'short_name' => 'DRW', 'offset_short_name' => 'GMT +09:30', 'name' => "(GMT+09:30) Darwin, Adelaide"],
            ['offset' => 36000, 'short_name' => 'VLD', 'offset_short_name' => 'GMT +10:00', 'name' => "(GMT+10:00) Vladivostok, Canberra, Melbourne, Sydney, Brisbane, Hobart, Guam, Port Moresby"],
            ['offset' => 39600, 'short_name' => 'MGD', 'offset_short_name' => 'GMT +11:00', 'name' => "(GMT+11:00) Magadan, Solomon Islands, New Caledonia"],
            ['offset' => 43200, 'short_name' => 'AUC', 'offset_short_name' => 'GMT +12:00', 'name' => "(GMT+12:00) Auckland, Wellington, Fiji Islands, Kamchatka, Marshall Islands"],
            ['offset' => 46800, 'short_name' => 'NUK', 'offset_short_name' => 'GMT +13:00', 'name' => "(GMT+13:00) Nuku'alofa"],
        ];
//        $ret = [];
//        foreach ($tzs as $k=>$v) {
//            $ret[$v['offset']] = [
//                'offset' =>$v['offset'],
//                'name'   => $v['name'],
//                'short_name' => $v['short_name']
//            ];
//        }
//        return $ret;
//        var_dump($ret); exit;
        if (isset($tzs[0][$field_name]))
            return array_column($tzs, $field_name, 'offset');
        else
            return array_column($tzs, 'name', 'offset');
    }

    /**
     * @param $str
     * @param $length
     * @return string
     */
    public static function concatString($str, $length)
    {
        if (mb_strlen($str) <= $length) {
            return $str;
        }

        return mb_substr($str, 0, $length) . '...';
    }

    /**
     * @param integer $left_seconds
     * @return string
     */
    public static function getHumanReadableLeftTime($left_seconds)
    {
        $str = "";
        $hours = intval(floor($left_seconds / 3600));
        if ($hours > 0) {
            $str .= "{$hours} hour(s)";
        }

        $test = intval($left_seconds % 3600);
        //var_dump($test);
        if ($test > 0) {
            $minutes = intval(floor($test / 60));
            if ($minutes > 0) {
                $str .= " {$minutes} minutes";
            }
        }

        $tmp = $hours * 3600;
        $tmp += isset($minutes) ? $minutes * 60 : 0;
        $seconds = $left_seconds - $tmp;
        if ($seconds > 0) {
            $str .= " {$seconds} seconds";
        }

        return trim($str);
    }

    /**
     * @param string $file_path
     * @param string $file_path_min
     * @return int
     */
    public static function compressCss($file_path, $file_path_min)
    {
        /* тут производим минификацию и возвращаем путь к нему */
        /* удалить комментарии */
        /* удалить табуляции, пробелы, символы новой строки и т.д. */
        return @file_put_contents(
            $file_path_min,
            str_replace(
                [' {', '} ', '{ ', ' }', ': ', ' :', '; ', ' ;', ', ', ' ,', ' > '],
                ['{', '}', '{', '}', ':', ':', ';', ';', ',', ',', '>'],
                str_replace(
                    ["\r\n", "\r", "\n", "\t", '  ', '    ', '    '],
                    '',
                    preg_replace(
                        '!/\*[^*]*\*+([^/][^*]*\*+)*/!',
                        '',
                        file_get_contents($file_path)
                    )
                )
            )
        );
    }

    /**
     * @param string $file_path
     * @param string $file_path_min
     * @return int
     */
    public static function compressJs($file_path, $file_path_min)
    {
        /* тут производим минификацию и возвращаем путь к нему */
        return @file_put_contents(
            $file_path_min,
            str_replace(
            //[";\r\n", ";\r", ";\n", "}\r\n", "}\r", "}\n", "\r\n{", "\r{", "\n{"],
            //[';'    , ';'  , ';'  , '}'    , '}'  , '}'  , '{'    , '{'  , '{'  ],
                [";\r\n", ";\r", ";\n"],
                [';', ';', ';'],
                trim(JSMin::minify(file_get_contents($file_path)))
            )
        );

        /*
        return @file_put_contents(
            $file_path_min,
            file_get_contents($file_path)
        );
        */
    }

    /**
     * @param int $day
     * @param int $tz
     * @param array $classes
     * @return string
     */
    public static function drawSchedule($day, $tz, $classes = [
        0 => "time-setting__body time-group dependent-visibility",
        1 => "checkbox-group",
        2 => "check-text-wrap check-text-wrap--sm",
    ])
    {
        $ret =
            '
    <div class="' . $classes[0] . '" id="begining-time-' . $day . '">
        <div class="' . $classes[1] . '">
            ';

        $tz_lost = $tz / 3600;
        $minutes = intval(($tz_lost - floor($tz_lost)) * 60);
        if ($minutes < 10) {
            $minutes = "0{$minutes}";
        }
        for ($i = 0; $i <= 23; $i++) {
            if ($i < 10) {
                $_prn = "0{$i}:{$minutes}";
            } else {
                $_prn = "{$i}:{$minutes}";
            }
            $ret .= "        " . '<div class="' . $classes[2] . '"><input class="js-beginning-time" id="time-' . $i . '-' . $day . '" type="checkbox" data-day="' . $day . '" data-hour="' . $i . '" /><label class="time-label" for="time-' . $i . '-' . $day . '">' . $_prn . '</label></div>' . "\n";
        }
        $ret .=
            '
        </div>
    </div>
    ';

        return $ret;
    }

    /**
     * @param $birthday
     * @return int
     */
    public static function calculate_age($birthday)
    {
        $birthday_timestamp = strtotime($birthday);
        $age = date('Y') - date('Y', $birthday_timestamp);
        if (date('md', $birthday_timestamp) > date('md')) {
            $age--;
        }
        return $age;
    }

    /**
     * @param int $age
     * @return string
     */
    public static function ru_string_age($age)
    {
        $age = intval($age);

        if ($age >= 10 && $age <= 20) {
            return $age . ' лет';
        }

        $test = $age % 10;

        if ($test == 1) {
            return $age . ' год';
        }

        if ($test >= 2 && $test <= 4) {
            return $age . ' года';
        }

        return $age . ' лет';
    }

    /**
     * @param $array
     * @return string
     */
    public static function getAdditionalServiceInfo($array = [])
    {
        $additional_service_info = '';
        if (isset($array['HTTP_USER_AGENT'])) {
            $additional_service_info .= "HTTP_USER_AGENT: {$array['HTTP_USER_AGENT']} <br />\n";
        }
        if (isset($array['HTTP_X_REAL_IP'])) {
            $additional_service_info .= "HTTP_X_REAL_IP: {$array['HTTP_X_REAL_IP']} <br />\n";
        }
        if (isset($array['REMOTE_ADDR'])) {
            $additional_service_info .= "REMOTE_ADDR: {$array['REMOTE_ADDR']} <br />\n";
        }
        if (isset($array['GEOIP_ADDR'])) {
            $additional_service_info .= "GEOIP_ADDR: {$array['GEOIP_ADDR']} <br />\n";
        }
        if (isset($array['GEOIP_COUNTRY_CODE'])) {
            $additional_service_info .= "GEOIP_COUNTRY_CODE: {$array['GEOIP_COUNTRY_CODE']} <br />\n";
        }
        if (isset($array['GEOIP_COUNTRY_NAME'])) {
            $additional_service_info .= "GEOIP_COUNTRY_NAME: {$array['GEOIP_COUNTRY_NAME']} <br />\n";
        }
        if (isset($array['GEOIP_CITY'])) {
            $additional_service_info .= "GEOIP_CITY: {$array['GEOIP_CITY']} <br />\n";
        }
        //if (isset($array['GEOIP_LONGITUDE'])) { $additional_service_info .= "GEOIP_LONGITUDE: {$array['GEOIP_LONGITUDE']} <br />\n"; }
        //if (isset($array['GEOIP_LATITUDE'])) { $additional_service_info .= "GEOIP_CITY: {$array['GEOIP_LATITUDE']} <br />\n"; }

        return $additional_service_info;
    }


    /**
     * @param int $count
     * @return array
     */
    public static function string_count_left_suffix($count)
    {
        $count = intval($count);

        if ($count >= 10 && $count <= 20) {
            return 'ов';
        }

        $test = $count % 10;

        if ($test == 1) {
            return '';
        }

        if ($test >= 2 && $test <= 4) {
            return 'a';
        }

        return 'ов';
    }

    /**
     * @param string $url
     * @return string|null
     */
    public static function getYoutubeVideoID($url)
    {
        if (strripos($url, "youtube.com")) {
            parse_str(parse_url($url, PHP_URL_QUERY), $you);
            $youtube_id = $you["v"];
        } elseif (strripos($url, "youtu.be")) {
            $you_mass = explode(".be/", $url);
            $tmp = $you_mass[sizeof($you_mass) - 1];
            $tmp2 = explode('/', $tmp);
            $tmp3 = explode('?', $tmp2[0]);
            $youtube_id = $tmp3[0];
        }

        if (!empty($youtube_id)) {
            return $youtube_id;
        } else {
            return null;
        }
    }

    /**
     * @param string $country_code
     * @return string
     */
    public static function getCountryImage($country_code)
    {
        $webPath = "/assets/xsmart-min/images/flags/";
        $pathToLinkMediaSources = Yii::getAlias('@frontend') . "/web" . $webPath;

        $test = $pathToLinkMediaSources . $country_code . ".svg";
        if (file_exists($test)) {
            return $webPath . $country_code . ".svg";
        }

        return $webPath . "file_not_exist.svg";
    }

    /**
     * @param $country_name
     * @param $city_name
     * @return string
     */
    public static function concatCountryCityName($country_name, $city_name)
    {
        if (!$country_name) {
            return 'undefined';
        }

        return $country_name . ($city_name ? ', ' . $city_name : '');
    }

    /**
     * @return array
     */
    public static function get_days()
    {
        $ret = [];
        for ($i = 1; $i <= 31; $i++) {
            $ret[$i] = ($i > 9) ? "$i" : "0$i";
        }
        return $ret;
    }

    /**
     * @return array
     */
    public static function get_months()
    {
        $ret = [];
        for ($i = 1; $i <= 12; $i++) {
            $ret[$i] = ($i > 9) ? "$i" : "0$i";
        }
        return $ret;
    }

    /**
     * @param int|null $min
     * @param int|null $max
     * @return array
     */
    public static function get_years($min = null, $max = null)
    {
        $min = $min ? $min : 1950;
        $max = $max ? $max : intval(date('Y')) - 6;
        $ret = [];
        for ($i = $min; $i <= $max; $i++) {
            $ret[$i] = "$i";
        }
        return $ret;
    }

    /**
     * @param string $str
     * @param int $max_word_len
     * @param string|null $delimiter
     * @return string
     */
    public static function formatLongString($str, $max_word_len = 40, $delimiter = null)
    {
        if (gettype($str) != 'string') {
            return $str;
        }

        $arr = explode(' ', $str);
        //var_dump($arr);
        foreach ($arr as $k => $v) {
            $len = mb_strlen($v);
            //var_dump($len);
            if ($len > $max_word_len) {
                $j = 0;
                $tmp = '';
                for ($i = 0; $i < $len; $i++) {
                    $tmp .= mb_substr($v, $i, 1);
                    //var_dump($tmp);
                    $j++;
                    if ($j > $max_word_len) {
                        if ($delimiter) {
                            $tmp .= $delimiter;
                        } else {
                            $tmp .= mb_convert_encoding('&#8203;', 'UTF-8', 'HTML-ENTITIES'); //chr(8203);
                        }
                        $j = 0;
                    }
                }
                $arr[$k] = $tmp;
            }
        }

        return implode(' ', $arr);
    }

    /**
     * @param string $str
     * @return string
     */
    public static function my_nl2br($str)
    {
        $tmp = explode("\n", $str);
        $str_res = '<p>';
        if (sizeof($tmp) > 1) {
            foreach ($tmp as $k=>$v) {
                $tmp[$k] = self::formatLongString($v);
            }
            $str_res .= implode('</p><p>', $tmp);
        } else {
            $str_res .= self::formatLongString($tmp[0]);
        }
        $str_res .= '</p>';

        return $str_res;
    }

    /**
     * @param double $sum
     * @return mixed
     */
    public static function getInCurrency($sum)
    {
        //$currency = Yii::$app->params['exchange']['usd'][Yii::$app->session->get('current----_currency', Yii::$app->params['exchange']['default'])];
        $currency = Yii::$app->params['exchange']['usd'][Yii::$app->request->cookies->getValue('_currency', Yii::$app->params['exchange']['default'])];

        $currency['sum'] = number_format(round($sum*$currency['val'], 2), 2, '.', '');
        return $currency;
    }

    public static function getInUsd($sum)
    {
        //$currency = Yii::$app->params['exchange']['usd'][Yii::$app->session->get('current----_currency', Yii::$app->params['exchange']['default'])];
        $currency = Yii::$app->params['exchange']['usd'][Yii::$app->request->cookies->getValue('_currency', Yii::$app->params['exchange']['default'])];

        $usd_sum = number_format(round($sum/$currency['val'], 2), 2, '.', '');
        return $usd_sum;
    }
}