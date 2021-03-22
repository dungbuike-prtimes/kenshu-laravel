<?php

namespace App\Repositories;


interface PostRepositoryInterface
{
    public function getAll();

    public function getAllByUser($owner);

    public function getPost($id);

    public function create($params);

    public function update($params);

    public function delete($id);
}
