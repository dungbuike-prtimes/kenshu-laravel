<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostFormRequest;
use App\Post;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    private $postRepository;
    private $tagRepository;
    private $imageRepository;

    public function __construct(PostRepositoryInterface $postRepository,
                                TagRepositoryInterface $tagRepository,
                                ImageRepositoryInterface $imageRepository)
    {
        $this->postRepository = $postRepository;
        $this->tagRepository = $tagRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = $this->postRepository->getAllByUser(Auth::id());
        return view('post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = $this->tagRepository->all();
        return view('post.create', compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(PostFormRequest $request)
    {
        $params = $request->validated();
        $post = $this->postRepository->create($params);
        if(isset($post)) {
            return redirect()->route('post.index')->with('success', 'Post is created!');
        } else {
            return redirect()->back()->withInput()->with('error', 'Create Failed!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = $this->postRepository->getPost($id);
        return view('post.show', compact('post'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('post.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->postRepository->delete($id)) {
            return redirect()->route('post.index')->with('success', 'Delete post success!');
        } else {
            return redirect()->route('post.index')->with('error', 'Delete post Failed!');
        };
    }
}
