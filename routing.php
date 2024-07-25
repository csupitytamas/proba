<?php

use App\Controller\Entities\Kitoro;
use App\Controller\Entities\Rudak;
use App\Controller\Entities\User;
use App\Controller\Pages\Maps\Farriers;
use App\Controller\Pages\Maps\Main;
use App\Controller\Pages\Maps\Respect;
use App\Controller\Pages\Maps\Storage;
use App\Helpers\Request;

try {
    if (!file_exists('app/Database/Credentials.php')) {
        throw new RuntimeException('Credentials file not found');
    }
    $urlArray = explode( "/", trim( str_replace( '%7C', '|', $_SERVER['REQUEST_URI'] ), "/" ) );
    $parameters = new StdClass();
    if ($urlArray[0] ?? false) {
        Request::calculateUrlAndParameters($urlArray[0], $parameters);
        switch ($urlArray[0]) {
            case 'main':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $main = new Main($parameters);
                    switch ($urlArray[1]) {
                        case 'on-field':
                            echo $main->getAllData();
                            exit;
                        case 'new-wing':
                            echo $main->addWingsToField();
                            exit;
                        case 'new-pole':
                            echo $main->addPolesToField();
                            exit;
                        case 'delete-wing':
                            echo $main->deleteWingsFromField();
                            exit;
                        case 'delete-pole':
                            echo $main->deletePolesFromField();
                            exit;
                        default:
                            include('app/View/layout.html');
                            include ('app/View/main.html');
                            exit;
                    }
                }
                include('app/View/layout.html');
                include('app/View/main.html');
                exit;
            case 'respect':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $respect = new Respect($parameters);
                    switch ($urlArray[1]) {
                        case 'on-field':
                            echo $respect->getAllData();
                            exit;
                        case 'new-wing':
                            echo $respect->addWingsToField();
                            exit;
                        case 'new-pole':
                            echo $respect->addPolesToField();
                            exit;
                        case 'delete-wing':
                            echo $respect->deleteWingsFromField();
                            exit;
                        case 'delete-pole':
                            echo $respect->deletePolesFromField();
                            exit;
                        default:
                            include('app/View/layout.html');
                            include ('app/View/respect.html');
                            exit;
                    }
                }
                include('app/View/layout.html');
                include('app/View/respect.html');
                exit;
            case 'farriers':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $farriers = new Farriers($parameters);
                    switch ($urlArray[1]) {
                        case 'on-field':
                            echo $farriers->getAllData();
                            exit;
                        case 'new-wing':
                            echo $farriers->addWingsToField();
                            exit;
                        case 'new-pole':
                            echo $farriers->addPolesToField();
                            exit;
                        case 'delete-wing':
                            echo $farriers->deleteWingsFromField();
                            exit;
                        case 'delete-pole':
                            echo $farriers->deletePolesFromField();
                            exit;
                        default:
                            include('app/View/layout.html');
                            include ('app/View/farriers.html');
                            exit;
                    }
                }
                include('app/View/layout.html');
                include('app/View/farriers.html');
                exit;
            case 'storage':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $storage = new Storage($parameters);
                    switch ($urlArray[1]) {
                        case 'on-field':
                            echo $storage->getAllData();
                            exit;
                        case 'new-wing':
                            echo $storage->addWings();
                            exit;
                        case 'new-pole':
                            echo $storage->addPoles();
                            exit;
                        case 'update-wing':
                            echo $storage->updateWingPieces();
                            exit;
                        case 'update-pole':
                            echo $storage->updatePolePieces();
                            exit;
                        case 'delete-wing':
                            echo $storage->deleteWing();
                            exit;
                        case 'delete-pole':
                            echo $storage->deletePole();
                            exit;
                        default:
                            include('app/View/layout.html');
                            include ('app/View/storage.html');
                            exit;
                    }
                }
                include('app/View/layout.html');
                include('app/View/storage.html');
                exit;
            case 'admin':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    switch ($urlArray[1]) {
                        case 'edit':
                            include('app/View/layout.html');
                            include('app/View/Admin/edit.html');
                            exit;
                        case 'new':
                            include('app/View/layout.html');
                            include('app/View/Admin/new.html');
                            exit;
                        default:
                            include('app/View/layout.html');
                            include('app/View/Admin/dashboard.html');
                            exit;
                    }
                }
                include('app/View/layout.html');
                include('app/View/Admin/dashboard.html');
                exit;
            case 'poles':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $poleEntity = new Rudak($parameters);
                    switch ($urlArray[1]) {
                        case 'get-poles':
                            echo $poleEntity->gelAll();
                            exit;
                        case 'get-pole':
                            echo $poleEntity->get();
                            exit;
                        case 'new-pole':
                            echo $poleEntity->create();
                            exit;
                        case 'update-pole':
                            echo $poleEntity->update();
                            exit;
                        case 'delete-pole':
                            echo $poleEntity->delete();
                            exit;
                        default:
                            include ('app/View/home.html');
                            exit;
                    }
                }
                include('app/View/storage.html');
                exit;
            case 'wings':
                if ($urlArray[1] ?? false) {
                    Request::calculateUrlAndParameters($urlArray[1], $parameters);
                    $wingsEntity = new Kitoro($parameters);
                    switch ($urlArray[1]) {
                        case 'get-wings':
                            echo $wingsEntity->gelAll();
                            exit;
                        case 'get-wing':
                            echo $wingsEntity->get();
                            exit;
                        case 'new-wing':
                            echo $wingsEntity->create();
                            exit;
                        case 'update-wing':
                            echo $wingsEntity->update();
                            exit;
                        case 'delete-wing':
                            echo $wingsEntity->delete();
                            exit;
                        default:
                            include ('app/View/home.html');
                            exit;
                    }
                }
                include('app/View/storage.html');
                exit;
            case 'switch-lang':
                if (isset($parameters->lang)) {
                    setcookie('lang', $parameters->lang, time() + (86400 * 30), "/"); // 86400 = 1 day
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'success',
                        'selected_lang' => $parameters->lang
                    ]);
                }
                else {
                    header("HTTP/1.1 400 Bad Request");
                    header('Content-Type: application/json');
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Nem volt lang paraméter átadva, hogy eltároljuk'
                    ]);
                }
                exit;
            case 'auth':
                if ($urlArray[1] ?? false) {
                    $user = new User($_POST);
                    switch ($urlArray[1]) {
                        case 'login':
                            echo $user->login();
                            exit;
                        case 'login-page':
                            include('app/View/layout.html');
                            include ('app/View/login.html');
                            exit;
                        case 'registration':
                            echo $user->registration();
                            exit;
                        case 'registration-page':
                            include('app/View/layout.html');
                            include ('app/View/registration.html');
                            exit;
                        case 'get-roles':
                            echo $user->getRoles();
                            exit;
                        case 'get-permissions':
                            echo $user->getPermissions();
                            exit;
                        default:
                            include('app/View/layout.html');
                            include ('app/View/home.html');
                            exit;
                    }
                }
                include('app/View/layout.html');
                include ('app/View/home.html');
                exit;
            default:
                include('app/View/layout.html');
                include ('app/View/home.html');
                exit;
        }
    }
    include('app/View/layout.html');
    include ('app/View/home.html');
} catch (RuntimeException $exception) {
    header('HTTP/1.1 400 Bad Request');
    die($exception->getMessage());
} catch (Exception $exception) {
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => $exception->getMessage() . " | " . $exception->getFile() . " | " . $exception->getLine()
    ]);
    exit;
}