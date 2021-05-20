<?php

namespace workingCurl;
require(dirname(__FILE__) . '/connection.php');



class workingCurl
{
    //Функция отвечате за работу CURL - получание сайта
    public function curlInitialization($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; ru; rv:1.9.2) Gecko/20100115 Firefox/3.6");
        curl_setopt($curl, CURLOPT_COOKIEFILE, "Coocke");
        curl_setopt($curl, CURLOPT_COOKIEJAR, "Coocke");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, "gzip,deflate");
        $output = curl_exec($curl);
        $update = mb_convert_encoding($output, 'utf-8', 'windows-1251');
        curl_close($curl);
        return $update;
    }



}