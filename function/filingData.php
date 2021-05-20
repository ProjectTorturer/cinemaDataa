<?php


namespace filingData;
$path = dirname(dirname(__FILE__));
require_once($path . '/vendor/rb-mysql.php');

use DiDom\Document;

use R;
use workingCurl\workingCurl;

require_once(__DIR__ . '/connection.php');

R::setup('mysql:host=127.0.0.1;dbname=BDData-cinema', 'root', 'root', false);
if (!R::testConnection()) die('No DB connection!');

class filingData
{

    //Получение групп и запись в бд
    function gettingMovieGroups()
    {

        $arrayGroup = [];
        $cat = new workingCurl();
        $url = "http://www.world-art.ru/cinema/rating_tv_top.php?public_list_anchor=1";
        $HtmlCod = $cat->curlInitialization($url);
        $document = new Document($HtmlCod);
        $href = $document->find("//*/td/h3/a[@href]", \DiDom\Query::TYPE_XPATH);
        $groupAll = R::getAll("SELECT * FROM groupcinema");

        if (count($groupAll) != count($href)) {

            foreach ($href as $item) {
                $group = R::getAll('SELECT * FROM groupcinema Where groupName = ?', [$item->text()]);
                if (!$group) {
                    $group = R::dispense('groupcinema');
                    $arrayGroup[] = $item->text();
                    $group->groupName = $item->text();
                    $id = R::store($group);
                } else {
                    continue;
                }
            }

        } else {

            foreach ($href as $item) {
                $arrayGroup[] = $item->text();
            }


        }
        R::close();
        return $arrayGroup;

    }

    //Получение ссылок к каждому фильму
    function gettingHrefData()
    {
        $url = "http://www.world-art.ru/cinema/rating_tv_top.php?public_list_anchor=";
        $curl = new workingCurl();
        $count = count($this->gettingMovieGroups());
        for ($a = 1; $a <= $count; $a++) {
            $Html = $curl->curlInitialization($url . $a);
            $document = new Document($Html);

            $href = $document->find("//*/td/a[@href]", \DiDom\Query::TYPE_XPATH);

            for ($i = 2; $i <= (count($href) - 2); $i += 2) {
                $arrayDataHref["Группа № {$a}"][] = $href[$i]->getAttribute('href');
            }
        }

        return $arrayDataHref;
    }

    //Получение описания фильма
    function gettingMovieSynopsis()
    {
        $url = "http://www.world-art.ru/cinema/";
        $curl = new workingCurl();
        $count = count($this->gettingMovieGroups());
        $href = $this->gettingHrefData();
        $Synopsis = [];
        for ($i = 1; $i <= $count; $i++) {
            foreach ($href["Группа № {$i}"] as $item) {
                $Html = $curl->curlInitialization($url . $item);
                $document1 = new Document($Html);
                $Synopsisa = $document1->find("//*/td/p[@align='justify']", \DiDom\Query::TYPE_XPATH);
                $SynopsisaImg = $document1->find('img[src$=jpg]');
                if ($Synopsisa) {
                    $Synopsis[$i][] = $Synopsisa[0]->text();
                } else {
                    $Synopsis[$i][] = "";
                }
                $img = $SynopsisaImg[0]->getAttribute('src');
                $Synopsis[$i][] = $img;

            }
        }
        return $Synopsis;
    }


    //Получение данных
    public function gettingMovieData()
    {
        $dataCinemaAll = R::getAll("SELECT * FROM cinemastorage");
        if (count($dataCinemaAll) < 200) {
            $url = "http://www.world-art.ru/cinema/rating_tv_top.php?public_list_anchor=";
            $curl = new workingCurl();
            $count = count($this->gettingMovieGroups());
            $synopsis = $this->gettingMovieSynopsis();
            $groupAll = R::getAll("SELECT * FROM groupcinema");

            for ($i = 1; $i <= $count; $i++) {
                $s = 0;
                $a = 38;
                $Html = $curl->curlInitialization($url . $i);
                $document = new Document($Html);
                $year = $document->find('td');


                for ($a; $a <= (count($year) - 11); $a += 5) {

                    $synopsisCheck = R::getAll("SELECT * FROM cinemastorage Where name_Cinema = ?", [$year[$a + 1]->text()]);
                    if (!$synopsisCheck) {

                        preg_match("/\[(.+?)]/", $year[$a + 1]->text(), $yearDate);

                        $cinemaData = R::dispense('cinemastorage');
                        $cinemaData->nameCinema = $year[$a + 1]->text();
                        $cinemaData->position = (int)$year[$a]->text();
                        $cinemaData->estimatedScore = (double)$year[$a + 2]->text();
                        $cinemaData->voices = (int)$year[$a + 3]->text();
                        $cinemaData->averageScore = (double)$year[$a + 4]->text();
                        $cinemaData->synopsis = $synopsis[$i][$s];
                        $cinemaData->yearCinema = (int)$yearDate[1];
                        $pathImageSave = "Image/{$i}Фильм{$year[$a]->text()}.jpg";
                        $cinemaData->pathImage = $pathImageSave;
                        $cinemaData->idGroupCinema = (int)$groupAll[$i - 1]['id'];


                        R::store($cinemaData);
                        //Получение и запись фоторографии в папку (Проекта)
                        $pathImage = "http://www.world-art.ru/cinema/" . $synopsis[$i][$s + 1];
                        $file = file_get_contents($pathImage);
                        file_put_contents($pathImageSave, $file);
                    } else {
                        $s += 2;
                        continue;
                    }
                    $s += 2;
                }
            }
        }

        return $dataCinemaAll;
    }

}