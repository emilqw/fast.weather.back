<?php
require_once "Data.php";

class API
{
    static private function getYandex(float $lat, float $lon, int $limit = 7, bool $hours = true, bool $extra = false, string $lang = "ru_RU")
    {
        $query = array('lat' => $lat, 'lon' => $lon, 'limit' => $limit, 'hours' => $hours, 'extra' => $extra, 'lang' => $lang);
        $option = array(
            'http' => array(
                'method' => "GET",
                'header' => "X-Yandex-API-Key: 0e0542e7-3ed6-4455-9aa3-a039ef99971b"
            ),
            'ssl' => array('verify_peer' => false, 'verify_peer_name' => false)
        );
        $url = Data::$YA_API_URL . http_build_query($query);
        $context = stream_context_create($option);
        $handle = fopen($url, 'r', false, $context);
        $contents = '';
        while (!feof($handle)) {
            $contents .= fread($handle, 8192);
        }
        fclose($handle);

        //echo $contents;
        // $contents = file_get_contents($url, false, $context);
        return json_decode($contents, true);
    }

    static function get(float $lat, float $lon, int $limit = 7, bool $hours = true, bool $extra = false, string $lang = "ru_RU")
    {
            return API::getYandex($lat, $lon, $limit, $hours, $extra, $lang);
    }

}