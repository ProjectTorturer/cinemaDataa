<?php


namespace caching;
$path = dirname(dirname(__FILE__));
require_once(__DIR__ . '/connection.php');
require_once($path . '/vendor/rb-mysql.php');
use R;



class cachingData
{

    public function cachingDataCinema() {
        //Подключение Memcache (Для работы с Кэшем)
        $memcache_obj = new \Memcache;
        $memcache_obj->connect('127.0.0.1', 11211);

        $dataCinemaAll = R::getAll("SELECT * FROM cinemastorage");

        if(count($dataCinemaAll) < 200) {
            $cat = new \filingData\filingData();
            $cat->gettingMovieData();
        }

        $groupAllCinema = R::getAll("SELECT * FROM groupcinema");
        for ($i = 0; $i < count($groupAllCinema); $i++ ) {
            $cache[$i] = $memcache_obj->get("dataCinema$i");
            $dataCinemaGroup = R::getAll("SELECT * FROM cinemastorage WHERE ID_Group_Cinema = ?", [$groupAllCinema[$i]['id']]);
            if (empty($cache[$i])) {
                $memcache_obj->set("dataCinema$i", $dataCinemaGroup, 0, 30);
                $cache[$i] = $memcache_obj->get("dataCinema$i");
            } else {
                $cache[$i] = $memcache_obj->get("dataCinema$i");
            }
        }

        return $cache;
    }

}