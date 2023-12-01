<?php
namespace my\Model;

class UUID {
    public function __construct(
        public string $uuid
    ) {
        if (!is_string($this->uuid))
            throw new \InvalidArgumentException('Не корректный UUID');
    }

    public function toString() {
        return $this->uuid;
    }

    public static function random(): self {
        return new self(\Ramsey\Uuid\Uuid::uuid4()->toString());
    }
}