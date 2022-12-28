<?php

use App\courseProject\Blog\Exceptions\AppException;
use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Exceptions\InvalidArgumentException;
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

$post = new Post(UUID::random(), $user, $faker->text(rand(20, 30)), $faker->text(rand(100, 150)));

$comment = new Comment(UUID::random(), $post, $user, $faker->text(rand(100, 150)));

$route = $argv[1] ?? null;
//print_r(new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'));
//$postsRepository->get(new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'));
//print_r(new Post(
//    new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'),
//    new User(new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'), '11331', '222', '333'),
//    'Exercitationem.',
//    'Aliquid iure alias cumque et accusamus. Laudantium nemo animi in molestiae earum.',
//));


switch ($route) {
    case "user":
        $usersRepository->save($user);
//        $user = $usersRepository->get(new UUID('10983474-5a34-48d3-a0b1-9cdd03c20f10'));
        echo $user .PHP_EOL;
        break;
    case "post":
//        $usersRepository->save($user);
//        $postsRepository->save($post);

        $call = $postsRepository->get(new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'));
        $call2 = new Post(
            new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'),
            new User(new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'), 'moiseev.albina', 'Ульяна', 'Шилова'),
            'Exercitationem.',
            'Aliquid iure alias cumque et accusamus. Laudantium nemo animi in molestiae earum.',
        );
        if ($call == $call2) {
            var_dump("Совапали!!!");
        } else {
            var_dump("Несовпали!!!");
            print_r($call->getUuid());
            print_r($call2->getUuid());
        };
        $post = $postsRepository->get(new UUID('cddfa464-07a4-44e1-972c-cfda91648c25'));
        echo $post .PHP_EOL;
        break;
    case "comment":
        $comment = new Comment(UUID::random(), $user, new Post(UUID::random(), $user, $faker->text(rand(20, 30)), $faker->text(rand(100, 150))), $faker->text(rand(100, 150)));
        $usersRepository->save($user);
        $postsRepository->save($post);
        $commentsRepository->save($comment);
//        $comment = $commentsRepository->get(new UUID('827977bc-c18b-433e-947c-0e3c223ba66d'));
        break;
    default:
        echo "Ошибка ввода команды!!!";
}


