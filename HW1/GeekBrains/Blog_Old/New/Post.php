<?php

namespace GeekBrains\Blog_Old;
use GeekBrains\Person\Person;


class New_Post
{
    public function __construct(
        private Person $author,
        private string $text
    ) {
    }
    public function __toString()
    {
        return $this->author . ' пишет: ' . $this->text;
    }
}