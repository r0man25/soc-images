<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

class Db
{
    public static function getConnection()
    {
        $db = new PDO('mysql:host=localhost;dbname=routes','root','root');
        $db->query("SET NAMES 'utf8'");
        return $db;
    }
}


class Route
{
    public static $route = [];
    public static $distance;
    public static $time;
    public static $findRoutes;
    public static $fromFirst;


    public static function getFindRoutes($from, $to)
    {
        if ($from != $to) {
            self::$fromFirst = $from;
            self::searchRoute($from, $to);
            if (empty(self::$findRoutes)) {
                return "No data available.";
            }
            return self::$findRoutes;
        }
        return "Error selected route!";

    }

    public static function searchRoute($from, $to)
    {
        $routes = self::getAllRouteByFrom($from);

        foreach ($routes as $route) {
            $k = 0;
            if ($route['to'] == self::$fromFirst) {
                return;
            }

            if ($route['to'] == $to) {
                self::$route[] = $route['from'].$route['to'];
                self::$distance += $route['distance'];
                self::$time += $route['time'];

                self::$findRoutes[] = [
                    'route' => self::$route,
                    'distance' => self::$distance,
                    'time' => self::$time,
                ];
                array_splice(self::$route, count(self::$route)-1);
                self::$distance = 0;
                self::$time = 0;
            }

            if ($route['to'] != $to && !in_array($route['from'].$route['to'], self::$route)) {
                self::$route[] = $route['from'].$route['to'];
//                self::$route[] = $route['from'].$route['to'];
                self::$distance += $route['distance'];
                self::$time += $route['time'];
                $k++;

                self::searchRoute($route['to'], $to);

                array_splice(self::$route, count(self::$route)-$k);
            }
        }

    }


    public static function getAllRouteByFrom($from)
    {
        $db = Db::getConnection();

        $routes = [];

        $result = $db->query("SELECT * FROM `route` WHERE `from` = '$from'");
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()){
            $routes[$i]['from'] = $row['from'];
            $routes[$i]['to'] = $row['to'];
            $routes[$i]['distance'] = $row['distance'];
            $routes[$i]['time'] = $row['time'];
            $i++;
        }

        return $routes;
    }


    public static function sortByDistance(&$arr)
    {
        usort($arr, function($a, $b){
            return ($a['distance'] - $b['distance']);
        });
        return $arr;
    }

    public static function sortByTime(&$arr)
    {
        usort($arr, function($a, $b){
            return ($a['time'] - $b['time']);
        });
        return $arr;
    }


    public static function getFroms()
    {
        $db = Db::getConnection();

        $from = [];

        $result = $db->query("SELECT DISTINCT `from` FROM `route`");
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()){
            $from[$i] = $row['from'];
            $i++;
        }

        return $from;
    }

    public static function getTos()
    {
        $db = Db::getConnection();

        $to = [];

        $result = $db->query("SELECT DISTINCT `to` FROM `route`");
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;
        while ($row = $result->fetch()){
            $to[$i] = $row['to'];
            $i++;
        }

        return $to;
    }


    public static function printResult()
    {
        if (is_array($_SESSION['routes'])) {
            echo '
                <form class="qwe" method="post">
                    <input class="sort-button" type="submit" value="Sort by distance" name="sortByDistance">
                    <input class="sort-button" type="submit" value="Sort by time" name="sortByTime">
                </form>
             ';
        }


        if (is_array($_SESSION['routes'])) {
            foreach ($_SESSION['routes'] as $item) {
                echo '<div class="route">
                    <p>
                        <span class="sort-header">All distance: </span> 
                            <span class="sort-val">'.$item['distance'].'</span>
                        <span class="sort-header"> / All time: </span>
                            <span class="sort-val">'.$item['time'].'</span>
                    </p>
                    <p>
                        <span class="sort-header"> Route:</span>';
                            foreach ($item['route'] as $route) {
                                echo '&nbsp;&nbsp;<span class="sort-val">'.$route.'</span>';
                            }
                    echo '</p>
                </div>';
            }
        } else {
                echo '<p>' . $_SESSION['routes'] . '</p>';
        }
    }

}


