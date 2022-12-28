<?php

use App\courseProject\Blog\Commands\Arguments;
use App\courseProject\Blog\Commands\CreateUserCommand;
use App\courseProject\Blog\Exceptions\AppException;
use Psr\Log\LoggerInterface;

        // Подключаем файл bootstrap.php
        // и получаем настроенный контейнер
$container = require __DIR__ . '/bootstrap.php';
        // При помощи контейнера создаём команду
$command = $container->get(CreateUserCommand::class);
        // Получаем объект логгера из контейнера
$logger = $container->get(LoggerInterface::class);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (AppException $e) {
        // Логируем информацию об исключении.
        // Объект исключения передаётся логгеру
        // с ключом "exception".
        // Уровень логирования – ERROR
    $logger->error($e->getMessage(), ['exception' => $e]);
}


