<?php

use App\courseProject\Blog\Exceptions\AppException;
use App\courseProject\Blog\Http\Actions\Posts\CreatePost;
use App\courseProject\Blog\Http\Actions\Posts\FindByUuid;
use App\courseProject\Blog\Http\Actions\Posts\LikeItPost;
use App\courseProject\Blog\Http\Actions\Users\FindByUsername;
use App\courseProject\Blog\Http\Actions\Posts\FindLikesForPost;
use App\courseProject\Blog\Http\ErrorResponse;
use App\courseProject\Blog\Http\Request;
use App\courseProject\Blog\Exceptions\HttpException;
use App\courseProject\Blog\Http\Actions\Posts\DeletePost;
use Psr\Log\LoggerInterface;
use App\courseProject\Blog\Http\Actions\Comments\CreateComment;
use App\courseProject\Blog\Http\Auth\LogIn;
use App\courseProject\Blog\Http\Auth\LogOut;

    $container = require __DIR__ . '/bootstrap.php';
    $request = new Request(
        $_GET,
        $_SERVER,
        file_get_contents('php://input'),
    );

// Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);

try {
    $path = $request->path();
} catch (HttpException $e) {
// Логируем сообщение с уровнем WARNING
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}
try {
    $method = $request->method();
} catch (HttpException $e) {
// Логируем сообщение с уровнем WARNING
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

    // Ассоциируем маршруты с именами классов действий,
    // вместо готовых объектов
    $routes = [
        'GET' => [
//GET http://127.0.0.1:8000/users/show?username=ivan
            '/users/show' => FindByUsername::class,
//GET http://127.0.0.1:8000/posts/show?uuid=4a2f8b5a-ce02-45a2-9b4f-18eec0ff31be
            '/posts/show' => FindByUuid::class,
//GET http://127.0.0.1:8000/likes/show?uuid=4a2f8b5a-ce02-45a2-9b4f-18eec0ff31be
            '/likes/show' => FindLikesForPost::class,
        ],
        'POST' => [
//POST http://127.0.0.1:8000/login
//{
//"username": "user4",
//"password": "pass1"
//}
            '/login' => LogIn::class,

            '/logout' => LogOut::class,
//POST http://127.0.0.1:8000/posts/create
//
//{
//"title": "Title text",
//"text": "Text text"
//}
            '/posts/create' => CreatePost::class,
//POST http://127.0.0.1:8000/posts/like
//
//{
//"uuid_post": "7b57d0d3-2097-4b15-accb-38d50da752b0"
//}
            '/posts/like' => LikeItPost::class,
//POST http://127.0.0.1:8000/posts/comment
//
//{
//"author_uuid": "8d056441-d4a1-4b87-afd4-da7f779a5eed",
//"post_uuid": "d3059207-32ee-4c8e-b891-320e5e30608c",
//"text": "TEXT999999"
//}
            '/posts/comment' => CreateComment::class,
        ],
        'DELETE' => [
            //Добавили новый маршрут
//DELETE http://127.0.0.1:8000/posts?uuid=392d6f52-b502-4e74-a4dc-91fcd2630b89
            '/posts' => DeletePost::class,
        ],
    ];

if (!array_key_exists($method, $routes)
    || !array_key_exists($path, $routes[$method])) {
// Логируем сообщение с уровнем NOTICE
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}


// Получаем имя класса действия для маршрута
    $actionClassName = $routes[$method][$path];

    // С помощью контейнера
    // создаём объект нужного действия
try {
    $action = $container->get($actionClassName);
    $response = $action->handle($request);
} catch (Exception $e) {
// Логируем сообщение с уровнем ERROR
    $logger->error($e->getMessage(), ['exception' => $e]);
// Больше не отправляем пользователю
// конкретное сообщение об ошибке,
// а только логируем его
    (new ErrorResponse)->send();
    return;
}
$response->send();

