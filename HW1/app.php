<?php

use GeekBrains\Blog_Old\New_Post;
use GeekBrains\Person\Name;
use GeekBrains\Person\Person;


spl_autoload_register(function ($class) {
    $file = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';
    $filePhp = explode("/", $file);
    $file2 = str_replace('_', DIRECTORY_SEPARATOR, end($filePhp));
    array_pop($filePhp);
    $file = implode("/", $filePhp) . "/" . $file2;
    var_dump($file);
    if (file_exists($file)) {
        require $file;
    }
});

$post = new New_Post(
    new Person(
        new Name('Иван', 'Никитин'),
        new DateTimeImmutable()
    ),
    'Всем привет!'
);

print $post;
