@extends('layouts.master')
@section('content')
<div class="main">
    <div class="main__wrapper">
        <div class="content-container">
            <div class="content-container__header">
                <h2>Content</h2>
            </div>
            <div class="content-container__body">

                <form id="form" class="form" method="POST" action="{{ route('post.store') }}" enctype="multipart/form-data">
                    @include('components.message')
                    @csrf
                    <div class="form__field">
                        <label class="form__label" for="title">Title</label>
                        <input type="text" class="form__input" name="title" placeholder="Post Title">
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="content">Content</label>
                        <textarea class="form__text-area" name="content" placeholder="Post content"></textarea>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="tag">Tag</label>
                        <div class="form__tag-field" id="tagField">
                            <a href="{{ route('tag.create') }}" id="create-tag" type="button"
                               class="form__button--success --pull-right">+ Create Tag</a>
                        </div>
                        <select id="tagSelect" class="form__input form__input--select2">
                            <option value="" selected disabled>Choose Tag</option>
                            @foreach ($tags as $tag)
                                <option value="{{ $tag["id"] }}">{{ $tag['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form__field">
                        <label class="form__label" for="file-upload">Image</label>
                        <input id="file-upload" type="file" class="form__file-upload" multiple name="images[]"
                               placeholder="Upload Image">
                    </div>
                    <div class="form__button-group">
                        <input type="submit" class="form__button--success" value="Create">
                        <input id="cancel-button" type="button" class="form__button--cancel" value="Cancel">
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
@push('script')
<script language="JavaScript">
    let tagSelect = document.getElementById('tagSelect');
    let tagField = document.getElementById('tagField');
    let tagSelectedArr = [];
    tagSelect.addEventListener('change', () => {
        let val = tagSelect.value;
        if (tagSelectedArr.indexOf(val) === -1) {
            let tagGroup = document.createElement('div');

            let tagValue = document.createElement('input');
            tagValue.hidden = true;
            tagValue.value = val;
            tagValue.name = 'tags[]';

            let tag = document.createElement('span');
            tag.classList.add('form__tag');
            let option_user_selection = tagSelect.options[val].textContent;
            tag.textContent = option_user_selection;
            tag.addEventListener('click', (event) => {
                tag.remove();
                for( let i = 0; i < tagSelectedArr.length; i++){
                    if ( tagSelectedArr[i] === tagValue.value) {
                        tagSelectedArr.splice(i, 1);
                    }
                }
            })

            tagGroup.appendChild(tagValue);
            tagGroup.appendChild(tag);
            tagField.prepend(tagGroup);

            tagSelectedArr.push(val);
        }
    })
    let createTag = document.getElementById('create-tag');
    createTag.addEventListener('click', () => {
        window.location.href = '/tags/create';
    })

    let cancelButton = document.getElementById('cancel-button');
    cancelButton.addEventListener('click', () => {
        window.location.href = '/posts'
    })

</script>
@endpush
