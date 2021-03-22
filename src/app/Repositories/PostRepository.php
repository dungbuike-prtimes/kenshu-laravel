<?php


namespace App\Repositories;


use App\Image;
use App\Post;
use App\PostTag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDOException;

class PostRepository implements PostRepositoryInterface
{
    private $post;

    public function __construct() {
        $this->post = new Post();
    }

    public function getAll()
    {
        // TODO: Implement getAll() method.
    }

    public function getAllByUser($owner)
    {
        $posts = $this->post->where('owner', '=', $owner)
                            ->with('tags')
                            ->with('images')
                            ->orderBy('id', 'DESC')
                            ->get();
        return $posts ? $posts : null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPost($id)
    {
        return $this->post->whereId($id)
            ->where('owner', '=', Auth::id())
            ->with('tags')
            ->with('images')
            ->first();
    }

    public function create($params)
    {
        try {
            DB::beginTransaction();
            $insertField['owner'] = Auth::id();
            $insertField['title'] = $params['title'];
            $insertField['content'] = $params['content'];
            $post = $this->post->create($insertField);
            if (isset($params['tags'])) {
                foreach ($params['tags'] as $tag) {
                    $post->tags()->attach($tag);
                }
            }

            if (isset($params['images'])) {
                foreach ($params['images'] as $img) {
                    $img->storePublicly('public/images');
                    $this->insertImage($post->id, $img->hashName());
                }
            }
            DB::commit();
            return $post;
        } catch (PDOException $exception) {
            DB::rollBack();
            return $exception;
        }

    }

    public function update($params)
    {
        // TODO: Implement update() method.
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $post = $this->getPost($id);
            if ($post) {
                $post->tags()->detach();
                foreach ($post->images as $img) {
                    if(!Storage::delete('/public/'.$img['url'])) {
                        throw new \Exception('Cannot remove file!');
                    }
                }
                $post->images()->delete();
                $post->forceDelete();
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    private function insertTag($post, $tag) {
        $params['post_id'] = $post;
        $params['tag_id'] = $tag;
        PostTag::create($params);
    }

    private function insertImage($post, $img) {
        $params['post_id'] = $post;
        $params['description'] = '';
        $params['url'] = 'images/'.$img;
        Image::create($params);
    }
}
