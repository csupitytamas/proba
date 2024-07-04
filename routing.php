<?php

use App\Controller\Maps\Main;
use App\Helpers\Request;

try {
    $urlArray = explode( "/", trim( str_replace( '%7C', '|', $_SERVER['REQUEST_URI'] ), "/" ) );
    $parameters = new StdClass();
    if ($urlArray[0] ?? false) {
        Request::calculateUrlAndParameters($urlArray[0], $parameters);
        switch ($urlArray[0]) {
            case 'main':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
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
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $view = new Respect($parameters);
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
                include('app/View/respect.html');
                exit;


            case 'farriers':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $view = new Farriers($parameters);
                    switch ($urlArray[2]) {
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
                include('app/View/farriers.html');
                exit;

            case 'raktar':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $view = new Storage($parameters);
                    switch ($urlArray[2]) {
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
                include('app/View/raktar.html');
                exit;

            case 'switch_lang':
                if (isset($parameters->lang)) {
                    setcookie('lang', $parameters->lang, time() + (86400 * 30), "/"); // 86400 = 1 day
                };
                break;
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
        'message' => $exception->getMessage() . " | " . $exception->getFile() . " | " . $exception->getLine()
    ]);
    exit;
}