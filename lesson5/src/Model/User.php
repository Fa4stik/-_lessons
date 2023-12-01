<?php

namespace my\Model;

class User {
    public function __construct(
        public string $uuid,
        public string $name,
        public string $surname
    ) {
    }
}

?>