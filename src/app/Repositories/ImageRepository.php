<?php


namespace App\Repositories;


use App\Image;

class ImageRepository implements ImageRepositoryInterface
{
    private $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function getImageOfPost($post_id)
    {
        // TODO: Implement getImageOfPost() method.
    }
}
