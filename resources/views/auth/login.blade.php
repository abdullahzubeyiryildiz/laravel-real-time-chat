@extends('layouts.app')

@section('content')

<section class="form login">
    <header>{{__('Chat Giriş')}}</header>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="row mb-3">
            <div class="field input">

                <label for="email">{{ __('Email Address') }}</label>

                <input id="email" type="email" class=" @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="E-Posta adresinizi giriniz..." autofocus>

                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
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

            <div class="field button">
                <button type="submit" class="btn btn-primary">
                    {{ __('Giriş Yap') }}
                </button>
            </div>
    </form>
    <div class="link">Hesabınız yok ise <a href="{{ route('register') }}">Kayıt Olun</a></div>
  </section>
@endsection
