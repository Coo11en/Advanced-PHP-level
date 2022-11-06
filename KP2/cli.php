<?php

use App\courseProject\Blog\Repository\Sqlite\SqliteCommentsRepository;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use App\courseProject\Blog\Comment;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\Repository\Sqlite\SqliteUsersRepository;
use App\courseProject\Blog\Repository\Sqlite\SqlitePostsRepository;

require_once __DIR__ . '/vendor/autoload.php';

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$faker = Faker\Factory::create('ru_RU');

$usersRepository = new SqliteUsersRepository($connection);
$postsRepository = new SqlitePostsRepository($connection);
$commentsRepository = new SqliteCommentsRepository($connection);

$user = new User(UUID::random(), $faker->userName(), $faker->firstName('female'), $faker->lastName());

$post = new Post(UUID::random(), $user->getUuid(), $faker->text(rand(20, 30)), $faker->text(rand(100, 150)));

$comment = new Comment(UUID::random(), $post->getUuid(), $user->getUuid(), $faker->text(rand(100, 150)));

$route = $argv[1] ?? null;

switch ($route) {
    case "user":
        $usersRepository->save($user);
        echo $user .PHP_EOL;
        break;
    case "post":
        $usersRepository->save($user);
        $postsRepository->save($post);
        echo $post .PHP_EOL;
        break;
    case "comment":
//        $comment = new Comment(UUID::random(), $user, new Post(UUID::random(), $user, $faker->text(rand(20, 30)), $faker->text(rand(100, 150))), $faker->text(rand(100, 150)));
        $usersRepository->save($user);
        $postsRepository->save($post);
        $commentsRepository->save($comment);
        echo $comment .PHP_EOL;
        break;
    default:
        echo "Ошибка ввода команды!!!";
}


