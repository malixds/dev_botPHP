<?php

declare(strict_types=1);

namespace Models;

class User extends BaseModel
{
    protected $table = 'users';

    public function __construct(
        public ?int  $id = null,
        public float $balance = 0,
    )
    {
        parent::__construct();
    }
}