<?php

use App\Controller\Maps\Main;
use App\Helpers\Request;

try {
    $urlArray = explode( "/", trim( str_replace( '%7C', '|', $_SERVER['REQUEST_URI'] ), "/" ) );
    $parameters = new StdClass();
    if ($urlArray[0] ?? false) {
        Request::calculateGetParameters($urlArray[0], $parameters);
        if (str_contains($urlArray[0], "?")) {
            $urlArray[0] = strstr($urlArray[0], '?', true);
        }
        switch ($urlArray[0]) {
            case 'farriers':
                include('farriers.php');
                exit;
            case 'main':
                if ($urlArray[1] ?? false) {
                    Request::calculateGetParameters($urlArray[1], $parameters);
                    if (str_contains($urlArray[1], "?")) {
                        $urlArray[1] = strstr($urlArray[1], '?', true);
                    }
                    $view = new Main($parameters);
                    switch ($urlArray[1]) {
                        case 'on-field':
                            echo $view->getAllData();
                            exit;
                        case 'new-wings':
                            echo $view->newWings();
                            exit;
                        case 'new-poles':
                            echo $view->newPoles();
                            exit;
                        case 'delete-wing':
                            echo $view->deleteWings();
                            exit;
                        case 'delete-poles':
                            echo $view->deletePoles();
                            exit;
                        default:
                            include ('app/View/home.html');
                            exit;
                    }
                }
                include('app/View/main.html');
                exit;
            case 'respect':
                include('respect.php');
                exit;
            case 'raktar':
                include('raktar.php');
                exit;
            default:
                include ('app/View/home.html');
                exit;
        }
    }
    include ('app/View/home.html');
} catch (Exception $exception) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $exception->getMessage()
    ]);
    exit;
}