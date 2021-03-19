<?php


namespace App\Repositories;


use App\User;

class UserRepository implements UserRepositoryInterface
{
    private $user;

    public function __construct()
    {
        $this->user = new User();
    }
    /**
     * @inheritDoc
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * @inheritDoc
     */
    public function first()
    {
        // TODO: Implement first() method.
    }

    /**
     * @inheritDoc
     */
    public function create($params)
    {
        return $this->user->create($params);
    }

    /**
     * @inheritDoc
     */
    public function update($params)
    {
        // TODO: Implement update() method.
    }

    /**
     * @inheritDoc
     */
    public function delete($param)
    {
        // TODO: Implement delete() method.
    }
}
