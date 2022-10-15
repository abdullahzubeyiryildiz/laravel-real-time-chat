@extends('layouts.app')

  @section('content')
    <section class="users">
        <header>
        <div class="content">
            <img src="{{asset(auth()->user()->image)}}" alt="">
            <div class="details">
            <span>{{auth()->user()->name}}</span>
            <p>{{auth()->user()->is_online == 'online' ? 'Çevrimiçi' : 'Çevrimdışı'}}</p>
            </div>
        </div>
        <a href="{{route('logout')}}" class="logout">Çıkış</a>
        </header>
        <div class="search">
        <span class="text">Kullanıcıyı seç ve sohbete başla</span>
        <input type="text" placeholder="Kullanıcı adını ile ara...">
        <button><i class="fas fa-search"></i></button>
        </div>
        <div class="users-list">
           {{-- <a href="{{route('chat.detail','12314')}}">
                <div class="content">
                    <img src="resim">
                    <div class="details">
                        <span>Kullanıcı Adı</span>
                        <p>Senin Mesaj</p>
                    </div>
                </div>
                <div class="status-dot hide"><i class="fas fa-circle"></i></div>
            </a> --}}
        </div>
    </section>
  @endsection


  @section('customjs')

    <script>
        getUserList();

        var i = 0;
        window.Echo.channel('online-user-event')
        .listen('.OnlineUserEvent', (response) => {
            i++;
                useronline(response.user);
        });


        window.Echo.connector.socket.on('connect', function(tes){
                    this.isConnected = true;

                    soket_id = window.Echo.socketId();

                    $.ajax({
                            type: "POST",
                            url: base_url+'/user/socket/login',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data:{
                                soket_id:soket_id
                            },
                            dataType:"json",
                            cache: false,
                            success: function(response){

                            },
                            error: function (data) {

                            },
                            complete: function() {

                            },
                        });

                })


    </script>

  @endsection
