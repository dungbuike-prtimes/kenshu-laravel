<?php


namespace App\Repositories;


use App\Tag;

class TagRepository implements TagRepositoryInterface
{
    private $tag;

    public function __construct()
    {
        $this->tag = new Tag();
    }

    public function all()
    {
        return $this->tag->all();
    }

    public function get($id)
    {
        // TODO: Implement get() method.
    }

    public function create($params)
    {
        return $this->tag->create($params);
    }
}
