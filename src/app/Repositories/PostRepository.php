<?php


namespace App\Repositories;


use App\Image;
use App\Post;
use App\PostTag;
use http\Env\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PDOException;

class PostRepository implements PostRepositoryInterface
{
    /** @var Model/Post  */
    private $post;

    public function __construct() {
        $this->post = new Post();
    }

    /**
     * @return all Post of all User
     */
    public function getAll()
    {
        $posts = $this->post
            ->with('tags')
            ->with('images')
            ->orderBy('id', 'DESC')
            ->get();
        return $posts ? $posts : null;
    }

    /**
     * get all Post of current user
     * @param $owner
     * @return mixed post list or null
     */
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
     * Get a Post specified by id property
     * @param $id
     * @return mixed one post
     */
    public function getPost($id)
    {
        return $this->post->whereId($id)
            ->with('tags')
            ->with('images')
            ->first();
    }

    /**
     * Create a Post with Tag & Image
     * @param $params validated request
     * @return bool
     */
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
                    $image = new Image();
                    $image->description = '';
                    $image->url = 'images/'.$img->hashName();
                    $image->post_id = $post->id;
                    $post->images()->save($image);
                }
            }
            DB::commit();
            return true;
        } catch (PDOException $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $params  validated request
     * @return bool
     */
    public function update($params)
    {

        try {
            DB::beginTransaction();
            $post = $this->getPost($params['id']);
            if($post['owner'] != Auth::id()) {
                throw new \Exception('No permission to update!');
            }
            $post->title = $params['title'];
            $post->content = $params['content'];
            $post->save();

            $post->tags()->detach();
            if (isset($params['tags'])) {
                foreach ($params['tags'] as $tag) {
                    $post->tags()->attach($tag);
                }
            }
            if (isset($params['deleteImage'])) {
                foreach ($params['deleteImage'] as $img) {
                    $image = Image::whereId($img)->first();
                    if (isset($image) && $image['post_id'] == $params['id']) {
                        if(!Storage::delete('/public/'.$image['url'])) {
                            throw new \Exception('Cannot remove file!');
                        }
                        $image->forceDelete();
                    }
                }
            }

            if (isset($params['images'])) {
                foreach ($params['images'] as $img) {
                    $img->storePublicly('public/images');
                    $image = new Image();
                    $image->description = '';
                    $image->url = 'images/'.$img->hashName();
                    $image->post_id = $post->id;
                    $post->images()->save($image);
                }
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $post = $this->getPost($id);
            if($post['owner'] != Auth::id()) {
                throw new \Exception('No permission to delete!');
            }

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
}
