<?php

namespace App\Model\User;

use App\Model\AbstractModel;

class UserModel extends AbstractModel
{
    protected $map = [
        'name',
        'followers_count',
    ];

    /** @var string */
    protected $name;

    /** @var int */
    protected $followersCount;

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
    public function getFollowersCount(): int
    {
        return $this->followersCount;
    }

    /**
     * @param int $followersCount
     */
    public function setFollowersCount(int $followersCount): void
    {
        $this->followersCount = $followersCount;
    }


}
