<?php

namespace App\courseProject\Blog\Commands\FakeData;

use App\courseProject\Blog\Comment;
use Symfony\Component\Console\Command\Command;
use Faker\Generator;
use App\courseProject\Blog\Repository\Interface\UsersRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\PostsRepositoryInterface;
use App\courseProject\Blog\Repository\Interface\CommentsRepositoryInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\courseProject\Person\User;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\UUID;
use Symfony\Component\Console\Input\InputOption;

class PopulateDB extends Command
{
// Внедряем генератор тестовых данных и
// репозитории пользователей и статей
    public function __construct(
        private Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository,
    ) {
        parent::__construct();
    }
    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Number of users',
            )
            ->addOption(
                'posts-number',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Number of posts',
            );
    }
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $usersNumber = $input->getOption('users-number');
        $postsNumber = $input->getOption('posts-number');
// Создаём десять пользователей
        $users = [];
        for ($i = 0; $i < $usersNumber; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }
// От имени каждого пользователя
// создаём по двадцать статей
        foreach ($users as $user) {
            for ($i = 0; $i < $postsNumber; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getHeader());
}
        }
        foreach ($users as $user) {
            {
                for ($i = 0; $i < 5; $i++) {
                    $comment = $this->createFakeComment($user, $post);
                    $output->writeln('Comment created: ' . $comment->getUuid());
                }
            }
        }
        return Command::SUCCESS;
    }
    private function createFakeUser(): User
    {
        $user = User::createFrom(
// Генерируем имя пользователя
            $this->faker->userName,
// Генерируем пароль
            $this->faker->password,
// Генерируем имя
            $this->faker->firstName,
// Генерируем фамилию
            $this->faker->lastName
        );
// Сохраняем пользователя в репозиторий
        $this->usersRepository->save($user);
        return $user;
    }
    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
// Генерируем предложение не длиннее шести слов
            $this->faker->sentence(6, true),
// Генерируем текст
            $this->faker->realText
        );
// Сохраняем статью в репозиторий
        $this->postsRepository->save($post);
        return $post;
    }
    private function createFakeComment(User $author, Post $post): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $post,
            $author,
// Генерируем предложение не длиннее шести слов
            $this->faker->realText
        );
// Сохраняем статью в репозиторий
        $this->commentsRepository->save($comment);
        return $comment;
    }
}