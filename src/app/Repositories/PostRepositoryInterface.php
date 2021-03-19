<?php

namespace App\Repositories;


interface PostRepositoryInterface
{
    public function getAll();

    public function getAllByUser();

    public function getPost();

    public function create($params);

    public function update($params);

    public function delete($params);
}
