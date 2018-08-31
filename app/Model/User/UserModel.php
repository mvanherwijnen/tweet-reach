<?php

namespace App\Model\User;

use App\Model\AbstractModel;

class UserModel extends AbstractModel
{
    protected $map = [
        'name',
        'follower_count',
    ];

    /** @var string */
    protected $name;

    /** @var int */
    protected $followerCount;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getFollowerCount(): int
    {
        return $this->followerCount;
    }

    /**
     * @param int $followerCount
     */
    public function setFollowerCount(int $followerCount): void
    {
        $this->followerCount = $followerCount;
    }


}
