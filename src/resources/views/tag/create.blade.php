@extends('layouts.master')
@section('content')
<div class="main">
    <div class="main__wrapper">
        <div class="content-container">
            <div class="content-container__header">
                <h2>Create Tag</h2>
            </div>
            <div class="content-container__body">
                @include('components.message')
                <form class="form--create-tag" method="post" action="{{ route('tag.store') }}">
                    @csrf
                    <input name="name" type="text" placeholder="Tag name" class="form__input">
                    <textarea name="description" type="text" placeholder="Tag description" class="form__text-area"></textarea>
                    <div class="form__button-group">
                        <input type="submit" value="Create" class="form__button--success">
                        <a href="{{ route('post.index') }}" type="button" class="form__button--cancel">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="side-container">
            <div class="side-container__header">
                <h2>Side Content</h2>
            </div>
            <div class="side-container__body">
                <h3 class="username">Hello Nekko</h3>
                <p class="email">nekko@prtimes.co.jp</p>
            </div>
        </div>
    </div>
</div>
@endsection
