
@extends('layouts.master')
@section('content')
    <div class="main">
        <div class="main__wrapper">
            <div class="content-container">
                <div class="content-container__header">
                    <h2>Content</h2>
                </div>
                <div class="content-container__body">
                    <div class="form__field">
                        <a href="{{ route('post.create') }}" type="button" class="form__button--success --pull-right">+ New Post</a>
                    </div>
                    <ul class="content-container__list">
<!--                        --><?php
//                        if (!isset($data['posts'])) {
//                            echo "<p>You have no post.</p>";
//                        }
//                        foreach ($data['posts'] as $post) {
//                            echo '<li class="post">
//                        <a class="post__title" href="/posts/' . $post["id"] . '">' . h($post["title"]) . '</a>
//                        <span class="post__content">' . h($post["content"]) .'</span>
//                        <div class="post__tag-box">';
//                            foreach ($post['tags'] as $tag) {
//                                echo '<span class="form__tag">' . h($tag['name']) . '</span>';
//                            }
//                            echo '</div></li>';
//                        }
//                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
