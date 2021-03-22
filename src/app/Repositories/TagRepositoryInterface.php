<?php

namespace App\Repositories;

interface TagRepositoryInterface {

    public function all();

    public function get($id);

    public function create($params);

}
