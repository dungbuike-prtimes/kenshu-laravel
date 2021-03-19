@foreach ($errors->all() as $error)
    <div class="form__message form__message--error" onclick="this.style.display='none'">{{ $error }}</div>
@endforeach

@if (session('success'))
    <div class="form__message form__message--success" onclick="this.style.display='none'">{{ session('success') }}</div>
@endif
@if (session('warning'))
    <div class="form__message form__message--warning" onclick="this.style.display='none'">{{ session('warning') }}</div>
@endif
@if (session('error'))
    <div class="form__message form__message--error" onclick="this.style.display='none'">{{ session('error') }}</div>
@endif
