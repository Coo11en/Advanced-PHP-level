<?php

use App\courseProject\Blog\Commands\Users\CreateUser;
use Symfony\Component\Console\Application;
use App\courseProject\Blog\Commands\Post\DeletePost;
use App\courseProject\Blog\Commands\Users\UpdateUser;
use App\courseProject\Blog\Commands\FakeData\PopulateDB;

    $container = require __DIR__ . '/bootstrap.php';
    // Создаём объект приложения
    $application = new Application();
    // Перечисляем классы команд
    $commandsClasses = [
        CreateUser::class,
        // Добавили команду удаления статей
        DeletePost::class,
        // Добавили команду обновления пользователя
        UpdateUser::class,
        // Добавили команду генерирования тестовых данных
        PopulateDB::class,
    ];

    foreach ($commandsClasses as $commandClass) {
    // Посредством контейнера
    // создаём объект команды
        $command = $container->get($commandClass);
    // Добавляем команду к приложению
        $application->add($command);
    }
    // Запускаем приложение
    $application->run();