@extends('layouts.master')
@section('content')
    <div class="main">
        <div class="main__wrapper">
            <div class="content-container">
                <div class="content-container__header">
                    <h2>Home > Index</h2>
                </div>
                <div class="content-container__body">
                    <ul class="content-container__list">
                        @if (!isset($posts))
                            <p>You have no post.</p>
                        @endif
                        @foreach($posts as $post)
                            <li class="post">
                                <a class="post__title" href="{{ route('post.show', $post['id']) }}">{{ $post['title'] }}</a>
                                <span class="post__content">{{ $post['content'] }}</span>
                                <div class="post__tag-box">
                                    @foreach($post->tags as $tag)
                                        <span class="form__tag">{{ $tag['name'] }}</span>
                                    @endforeach
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
