<?php

namespace App\Repositories;


interface UserRepositoryInterface
{
    /**
     * Get all
     */
    public function all();

    /**
     * Get one
     */

    public function first();

    /**
     * Create
     * @params array
     */

    public function create($params);

    /**
     * Update
     * $params array
     */

    public function update($params);

    /**
     * Delete
     * $params id
     */
    public function delete($param);

}
