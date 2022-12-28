<?php

use App\courseProject\Blog\Exceptions\UserNotFoundException;
use App\courseProject\Blog\Post;
use App\courseProject\Blog\Repository\Sqlite\SqlitePostsRepository;
use App\courseProject\Blog\UUID;
use App\courseProject\Person\User;
use PHPUnit\Framework\TestCase;

class SqlitePostRepositoryTest extends TestCase
{
    public function testItReturnsValuesAsString(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':uuid' => '140e8659-c69f-4a7e-a7dc-88b85432ae7b',
                ':author_uuid' => '140e8659-c69f-4a7e-a7dc-88b85432ae7b',
                ':title' => 'Exercitationem.',
                ':text' => 'Aliquid iure alias cumque et accusamus. Laudantium nemo animi in molestiae earum.',
            ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqlitePostsRepository($connectionStub);
        $repository->save(
            new Post(
                new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'),
                new User(new UUID('140e8659-c69f-4a7e-a7dc-88b85432ae7b'), 'moiseev.albina', 'Ульяна', 'Шилова'),
                'Exercitationem.',
                'Aliquid iure alias cumque et accusamus. Laudantium nemo animi in molestiae earum.',
            )
        );
    }

    /**
     * @throws UserNotFoundException
     * @throws \App\courseProject\Blog\Exceptions\InvalidArgumentException
     */
//    public function testItThrowsAnExceptionWhenPostNotFound(): void
//    {
//        $connectionStub = $this->createStub(PDO::class);
//        $statementMock = $this->createMock(PDOStatement::class);
//        $statementMock->method('fetch')->willReturn(false);
//        $connectionStub->method('prepare')->willReturn($statementMock);
//        $repository = new SqlitePostsRepository($connectionStub);
//
//        $this->expectExceptionMessage('Cannot find post: 140e8659-c89f-4a7e-a7dc-88b85432ae7b');
//        $this->expectException(UserNotFoundException::class);
//        $repository->get(new UUID('140e8659-c89f-4a7e-a7dc-88b85432ae7b'));
//    }

    public function testItSavePostInDatebase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once())
            ->method('execute')
            ->with([
                ':author_uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':title' => 'Ivan',
                ':text' => 'Nikitin',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);
        $repository = new SqlitePostsRepository($connectionStub);

        $user = new User(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            'user',
            'Ivan',
            'Nikitin'
        );

        $repository->save(
            new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                $user,
                'Ivan',
                'Nikitin',
            )
        );
    }

public function testItGetPostByUUid(): void
{
    $connectionStub = $this->createStub(PDO::class);
    $statementMock = $this->createMock(PDOStatement::class);
    $statementMock->method('fetch')->willReturn([
        'uuid' => '123e4567-e89b-12d3-a456-426614174000',
        'author_uuid' => '123e4567-e89b-12d3-a456-426614174051',
        'title' => 'Заголовок',
        'text' => 'ТекстТекстТекст',
        'username' => 'user3',
        'first_name' => 'Mikhail',
        'last_name' => 'Popov',
    ]);

    $connectionStub->method('prepare')->willReturn($statementMock);

    $postRepository = new SqlitePostsRepository($connectionStub);
    $post = $postRepository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));

    $this->assertSame('123e4567-e89b-12d3-a456-426614174000', (string)$post->getUuid());
}
}