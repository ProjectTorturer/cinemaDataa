<?php


$path = dirname(dirname(__FILE__));
//Сomposer
require_once($path . '/vendor/autoload.php');
//Класс CURL
require_once($path . '/function/workingCurl.php');
//Класс сбора данных
require_once($path . '/function/filingData.php');
//Класс кеширования данных
require_once ($path . '/function/cachingData.php');


