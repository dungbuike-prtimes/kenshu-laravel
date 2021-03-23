<?php

namespace App\Http\Controllers;

use App\Repositories\ImageRepositoryInterface;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\TagRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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

    public function index() {
        $posts = $this->postRepository->getAll();
        return view('home.index', compact('posts'));
    }
}
