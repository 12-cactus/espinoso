<?php

namespace App\Lib;

use Exception;

class CronicaGenerator
{
    public static function getTitleUrl($title): string
    {
        $url = "http://placasrojas.me/result.php";
        $params = ['f' => $title];
        try {
            self::getUrl($url, $params, false, $status);
        } catch (Exception $e) {

        }

        $urlRedirect = $status['redirect_url'];
        preg_match("#http://placasrojas.me/(?'id'[^/]*)/#", $urlRedirect, $matches);
        $id = $matches['id'] ?? 287048;
        return "http://placasrojas.me/placa2G.php?idC=" . $id ;
    }

    // ASCO, pero no importa nada
    private static function getUrl($url, $params, $follow_redirs = false, &$status)
    {
        if (!function_exists('curl_init'))
            throw new Exception('cURL Must be installed for geturl function to work. Ask your host to enable it or uncomment extension=php_curl.dll in php.ini');

        $cookie = tempnam("/tmp", "CURLCOOKIE");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; CrawlBot/1.0.0)');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($ch, CURLOPT_MAXREDIRS, 15);
        curl_setopt($ch, CURLOPT_POST, 1);

        if (!empty($params))
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $html = curl_exec($ch);
        $status = curl_getinfo($ch);
        curl_close($ch);

        if ($follow_redirs && ($status['http_code'] == 301 || $status['http_code'] == 302))
        {
            list($header) = explode("\r\n\r\n", $html, 2);
            $matches = array();
            preg_match("/(Location:|URI:)[^(\n)]*/", $header, $matches);

            $url = trim(str_replace($matches[1],"", $matches[0]));
            $url_parsed = parse_url($url);

            return (isset($url_parsed) && !empty($url)) ? geturl($url, $params) : '';
        }
        return $html;
    }
}
