@extends('layouts.master')
@section('content')
    <div class="main">
        <div class="main__wrapper">
            <div class="content-container">
                <div class="content-container__header">
                    <h2>Content</h2>
                </div>
                <div class="content-container__body">
                    <form id="form" class="form"
                          action="{{ route('post.update', $post['id']) }}" method="post"
                          enctype="multipart/form-data">
                        @include('components.message')

                        @csrf
                        @method('PUT')

                        <div class="form__field">
                            <label class="form__label" for="title">Title</label>
                            <input type="text" class="form__input" name="title" placeholder="Post Title"
                                   value="{{ $post['title'] }}">
                        </div>
                        <div class="form__field">
                            <label class="form__label" for="content">Content</label>
                            <textarea class="form__text-area" name="content" placeholder="Post content"
                            >{{ $post['content'] }}</textarea>
                        </div>
                        <div class="form__field">
                            <label class="form__label" for="tag">Tag</label>
                            <div class="form__tag-field" id="tagField">
                                @foreach($post->tags as $tag)
                                    <div class="form__tag-group">
                                        <input name="tags[]" type="hidden" value="{{ $tag['id'] }}">
                                        <span class="form__tag">{{ $tag['name'] }}</span>
                                    </div>
                                @endforeach
                                <input id="create-tag" type="button" value="+ Create Tag"
                                       class="form__button--success --pull-right">
                            </div>
                            <select id="tagSelect" class="form__input form__input--select2">
                                <option value="" selected disabled>Choose Tag</option>
                                @foreach($tags as $tag)
                                    <option value="{{ $tag['id'] }}">{{ $tag['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form__field">
                            <label class="form__label" for="file-upload">Image</label>
                            <input id="file-upload" type="file" class="form__file-upload" multiple name="images[]"
                                   placeholder="Upload Image">
                            <div class="form__image-preview">
                                @foreach($post->images as $img)
                                    <div class="form__image-preview-box">
                                        <img src="{{ Storage::url($img['url']) }}">
                                        <a type="button" class="form__button--danger --bottom --center delete-image">
                                            Delete
                                        </a><input type="hidden" value="{{ $img['id'] }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form__button-group">
                            <input type="submit" class="form__button--success" value="Update Post">
                            <input id="cancel-button" type="button" class="form__button--cancel" value="Cancel">
                            <input id="delete-button" type="button" class="form__button--danger --pull-right"
                                   value="Delete this post">
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
    <div id="myModal" class="modal">

        <!-- Modal content -->
        <div class="modal__header">
            <span class="close">&times;</span>
            <h3>Are you sure to delete this post</h3>
        </div>
        <div class="modal__content">
            <form method="post" action="{{ route('post.destroy', $post['id']) }}">
                @method('DELETE')
                @csrf
                <input type="submit" class="form__button--danger" value="Sure, delete post!">
                <input type="button" class="form__button--success" value="No, keep post!">
            </form>
        </div>

    </div>
@endsection
@push('script')
    <script language="JavaScript">
        let form = document.getElementById('form');
        let tagSelect = document.getElementById('tagSelect');
        let tagField = document.getElementById('tagField');
        let tagSelectedArr = [];
        <?php
        foreach ($post['tags'] as $tag) {
            echo 'tagSelectedArr.push("' . $tag['id'] . '");';
        }
        ?>
        let insertedTags = document.querySelectorAll('.form__tag-group');
        insertedTags.forEach(element => {
            element.addEventListener('click', (event) => {
                element.remove();
                for (let i = 0; i < tagSelectedArr.length; i++) {
                    if (tagSelectedArr[i] === element.childNodes[0].value) {
                        tagSelectedArr.splice(i, 1);
                    }
                }
            })
        })

        tagSelect.addEventListener('change', () => {
            let val = tagSelect.value;
            if (tagSelectedArr.indexOf(val) === -1) {
                let tagGroup = document.createElement('div');
                tagGroup.classList.add('form__tag-group');

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
                    for (let i = 0; i < tagSelectedArr.length; i++) {
                        if (tagSelectedArr[i] === tagValue.value) {
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

        let deleteImage = document.querySelectorAll('.delete-image');
        deleteImage.forEach(element => {
            element.addEventListener('click', () => {
                let deleteImageHiddenInput = document.createElement('input');
                deleteImageHiddenInput.hidden = true;
                deleteImageHiddenInput.name = "deleteImage[]";
                deleteImageHiddenInput.value = element.nextSibling.value;
                console.log(element.nextSibling);
                form.appendChild(deleteImageHiddenInput);
                element.parentNode.remove();
            })
        })

        let cancelButton = document.getElementById('cancel-button');
        cancelButton.addEventListener('click', () => {
            window.location.href = '/posts'
        })
        let modal = document.getElementById("myModal");

        // Get the button that opens the modal
        let btn = document.getElementById("delete-button");

        // Get the <span> element that closes the modal
        let span = document.getElementsByClassName("close")[0];

        // When the user clicks the button, open the modal
        btn.onclick = function () {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endpush
