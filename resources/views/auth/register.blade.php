@extends('layouts.app')

@section('content')

<section class="form login">
    <header>{{__('Chat Kayıt')}}</header>

    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

            <div class="field input">
                     <label for="name">{{ __('Name') }}</label>

                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
            </div>


            <div class="field input">

                <label for="email">{{ __('Email Address') }}</label>

                <input id="email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="E-Posta adresinizi giriniz..." autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="field input">

                <label for="password">{{ __('Password') }}</label>
                <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" placeholder="Şifrenizi giriniz..." required>

                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>


            <div class="field image">
                <label>Profil Fotoğrafı</label>
                <input type="file" name="image" accept="image/x-png,image/gif,image/jpeg,image/jpg" required>
              </div>


            <div class="field button">
                <button type="submit" class="btn btn-primary">
                    {{ __('Kayıt Ol') }}
                </button>
            </div>
    </form>
    <div class="link">Hesabınız yok ise <a href="{{ route('register') }}">Kayıt Olun</a></div>
  </section>
@endsection
