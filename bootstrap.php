<?php

use App\courseProject\Blog\Container\DIContainer;
use App\courseProject\Blog\Repository\Interface\LikesRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\Repository\Sqlite\SqliteLikesRepository;
use App\courseProject\Blog\Repository\Sqlite\SqlitePostsRepository;
use App\courseProject\Blog\Repository\Sqlite\SqliteUsersRepository;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;
use App\courseProject\Blog\Http\Auth\JsonBodyUuidAuthentication;
use App\courseProject\Blog\Http\Auth\AuthenticationInterface;
use App\courseProject\Blog\Repository\Interface\CommentsRepositoryInterface;
use App\courseProject\Blog\Repository\Sqlite\SqliteCommentsRepository;
use App\courseProject\Blog\Http\Auth\PasswordAuthentication;
use App\courseProject\Blog\Http\Auth\PasswordAuthenticationInterface;
use App\courseProject\Blog\Repository\AuthTokensRepository\AuthTokensRepositoryInterface;
use App\courseProject\Blog\Repository\AuthTokensRepository\SqliteAuthTokensRepository;
use App\courseProject\Blog\Http\Auth\TokenAuthenticationInterface;
use App\courseProject\Blog\Http\Auth\BearerTokenAuthentication;
use Faker\Provider\Lorem;
use Faker\Provider\ru_RU\Internet;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\ru_RU\Text;
use Faker\Generator;

// Подключаем автозагрузчик Composer

require_once __DIR__ . '/vendor/autoload.php';
// Создаём объект контейнера ..
Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();
// .. и настраиваем его:
// 1. подключение к БД
$container->bind(
    PDO::class,
    new PDO('sqlite:' . __DIR__ . '/' . $_SERVER['SQLITE_DB_PATH'])
);
// 2. репозиторий статей
$container->bind(
    PostsRepositoryInterface::class,
    SqlitePostsRepository::class
);
// 3. репозиторий пользователей
$container->bind(
    UsersRepositoryInterface::class,
    SqliteUsersRepository::class
);
// 4. репозиторий лайков
$container->bind(
    LikesRepositoryInterface::class,
    SqliteLikesRepository::class
);

$container->bind(
    CommentsRepositoryInterface ::class,
    SqliteCommentsRepository::class
);

$container->bind(
    AuthenticationInterface::class,
    JsonBodyUuidAuthentication::class
);

$container->bind(
    PasswordAuthenticationInterface::class,
    PasswordAuthentication::class
);

$container->bind(
    AuthTokensRepositoryInterface::class,
    SqliteAuthTokensRepository::class
);

$container->bind(
    TokenAuthenticationInterface::class,
    BearerTokenAuthentication::class
);


// Добавляем логгер в контейнер
$container->bind(
    LoggerInterface::class,
    (new Logger('blog'))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.log'
        ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ))
// Добавили ещё один обработчик;
// он будет вызываться первым …
        ->pushHandler(
// .. и вести запись в поток php://stdout,
// то есть в консоль
            new StreamHandler("php://stdout")
        )
);

// Выносим объект логгера в переменную
$logger = (new Logger('blog'));
// Включаем логирование в файлы,
// если переменная окружения LOG_TO_FILES
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_FILES']) {
    $logger->pushHandler(new StreamHandler(
        __DIR__ . '/logs/blog.log'
    ))
        ->pushHandler(new StreamHandler(
            __DIR__ . '/logs/blog.error.log',
            level: Logger::ERROR,
            bubble: false,
        ));
}
// Включаем логирование в консоль,
// если переменная окружения LOG_TO_CONSOLE
// содержит значение 'yes'
if ('yes' === $_SERVER['LOG_TO_CONSOLE']) {
    $logger
        ->pushHandler(
            new StreamHandler("php://stdout")
        );
}
$container->bind(
    LoggerInterface::class,
    $logger
);

// Создаём объект генератора тестовых данных
$faker = new Generator();
// Инициализируем необходимые нам виды данных
$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));
// Добавляем генератор тестовых данных
// в контейнер внедрения зависимостей
$container->bind(
    Generator::class,
    $faker
);

// Возвращаем объект контейнера
return $container;
