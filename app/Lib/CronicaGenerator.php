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
            self::getUrl($url, $params, $status);
        } catch (Exception $e) {
            return "";
        }

        $urlRedirect = $status['redirect_url'];
        preg_match("#http://placasrojas.me/(?'id'[^/]*)/#", $urlRedirect, $matches);
        $id = $matches['id'] ?? 287048;
        return "http://placasrojas.me/placa2G.php?idC=" . $id ;
    }

    /**
     * @param $url
     * @param $params
     * @param $status
     * @return bool|string
     * @throws Exception
     */
    private static function getUrl($url, $params, &$status)
    {
        if (!function_exists('curl_init'))
            throw new Exception(trans('messages.curl'));

        $cookie = tempnam("/tmp", "CURLCOOKIE");
        $curl = self::curlSetting($url, $cookie);

        if (!empty($params)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $html = curl_exec($curl);
        $status = curl_getinfo($curl);
        curl_close($curl);

        return $html;
    }

    /**
     * @param $url
     * @param $cookie
     * @return false|resource
     */
    protected function curlSetting($url, $cookie)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; CrawlBot/1.0.0)');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_AUTOREFERER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);    # required for https urls
        curl_setopt($curl, CURLOPT_MAXREDIRS, 15);
        curl_setopt($curl, CURLOPT_POST, 1);
        return $curl;
    }
}
