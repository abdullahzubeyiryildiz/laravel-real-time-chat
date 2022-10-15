
@extends('layouts.app')

@section('content')

<section class="chat-area">
    <header class="chatItem" data-user="{{$user->uuid}}">

      <a href="{{route('home')}}" class="back-icon"><i class="fas fa-arrow-left"></i></a>
      <img src="{{asset($user->image)}}" alt="">
      <div class="details">
        <span> {{$user->name}} </span>
        <p class="userOnline">{{$user->is_online == 'online' ? 'Çevrimiçi' : 'Çevrimdışı'}}</p>
      </div>
    </header>

    <div class="chat-box" data-user="{{$user->uuid}}">

       {{-- <div class="chat outgoing">
            <div class="details">
                <p>Giden Mesaj</p>
            </div>
        </div>

        <div class="chat incoming">
            <img src="{{asset($user->image)}}" alt="">
            <div class="details">
                <p>Gelen Mesaj</p>
            </div>
       </div>



    --}}
    <div class="text noMessage">{{__('Bugün Mesaj yok. Mesaj gönderdikten sonra burada görünecekler.')}}</div>
    </div>

    <form class="typing-area" id="messageSend" method="POST">
        @csrf
      <input type="text" class="incoming_id" name="sent_to_id" value="{{$user->uuid}}" hidden>
      <input type="text" name="message" class="input-field" placeholder="Mesajınızı yazınız..." autocomplete="off">
      <button type="submit"><i class="fab fa-telegram-plane"></i></button>
    </form>

  </section>

@endsection

@section('customjs')


<script>


    id = parseInt("{{auth()->user()->id}}");
    uuid = $('.chat-box').attr('data-user');

    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Socket-Id': window.Echo.socketId()
            }
    });

    window.Echo.channel('online-user-event')
    .listen('.OnlineUserEvent', (response) => {
            useronline(response.user);
    });


    window.Echo.channel('chat.'+uuid+'-'+id)
    .listen('.ChatEvent', (response) => {
             messageBuilder(response.data);
            playAudi();
    });


    getMessage(uuid);

</script>
@endsection
