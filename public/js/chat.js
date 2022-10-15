
        const base_url = window.location.origin;


       function useronline(user) {

            if(user.is_online == 'online') {
                $('.userItem[data-user="'+user.uuid+'"]').find('.onlineStatus').removeClass('offline');
                $('.chatItem[data-user="'+user.uuid+'"]').find('.userOnline').text('Online');
            }else {
                $('.userItem[data-user="'+user.uuid+'"]').find('.onlineStatus').addClass('offline');
                $('.chatItem[data-user="'+user.uuid+'"]').find('.userOnline').text('Offline');
            }

          if ($('.users-list').children('.userItem[data-user="'+user.uuid+'"]').length == 0) {
              $('.users-list').prepend(`<a class="userItem" href="/chat/${user.uuid}" data-user="${user.uuid}">
                    <div class="content">
                        <img src="${base_url+'/'+user.image}">
                        <div class="details">
                            <span>${user.name}</span>
                            <p>${user.lastmessage != null ? user.lastmessage.message : ''}</p>
                        </div>
                    </div>
                    <div class="onlineStatus status-dot"><i class="fas fa-circle"></i></div>
                </a>`);
          }

        }



        function getUserList() {
            $.ajax({
                url: base_url + "/users/list",
                type: 'GET',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function(response) {
                            console.log(response);
                            response.data.forEach(user => {
                                    $('.users-list').append(`<a class="userItem" href="/chat/${user.uuid}" data-user="${user.uuid}">
                                    <div class="content">
                                        <img src="${base_url+'/'+user.image}">
                                        <div class="details">
                                            <span>${user.name}</span>
                                            <p>${user.lastmessage != null ? user.lastmessage.message : ''}</p>
                                        </div>
                                    </div>
                                    <div class="onlineStatus status-dot ${user.is_online == 'offline' ? 'offline' : ''}"><i class="fas fa-circle"></i></div>
                                </a>`);
                            });
                },
            });
    }



    function getMessage(user,date = 'today') {
        $.ajax({
                    type: "POST",
                    url: base_url+'/get/messageList',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data:{
                        user:user,
                        date:date
                    },
                    dataType:"json",
                    cache: false,
                    success: function(response){
                        if(response.success == true) {
                            if( response.data.length > 0 ) {

                                if(date == 'today'){
                                    messageBuilder(response.data);
                                }else {
                                    oldMessageBuilder(response.data);
                                }

                            }

                            if(response.last_date != '') {
                                    $('.chat-box').prepend(`<button class="oldMessage" data-date="${response.last_date}">Önceki Mesajları Getir</button>`);
                                }
                        }
                    },
                    error: function (data) {

                    },
                    complete: function() {

                    },
                });
    }



    function messageBuilder(data) {

                        data.forEach(item => {
                            if(id != item.sender_id) {
                                imgItem =  `<img src="${base_url + '/' + item.user.image}" alt="">`;
                            }else {
                                imgItem =  '';
                            }

                            $('.chat-box').append(`<div class="chat ${id == item.sender_id ? 'outgoing' : 'incoming'}">
                                    ${imgItem}
                                <div class="details">
                                    <p>${item.message}</p>
                                </div>
                            </div>`);
                        });
                        bottomScroll();
                        noMessageHide();
    }



    function oldMessageBuilder(data) {

                        data.forEach(item => {
                            if(id != item.sender_id) {
                                imgItem =  `<img src="${base_url + '/' + item.user.image}" alt="">`;
                            }else {
                                imgItem =  '';
                            }

                            $('.chat-box').prepend(`<div class="chat ${id == item.sender_id ? 'outgoing' : 'incoming'}">
                                    ${imgItem}
                                <div class="details">
                                    <p>${item.message}</p>
                                </div>
                            </div>`);
                        });
                        topScroll();
    }


    function topScroll(){
        $('.chat-box').scrollTop(0);
    }


    function bottomScroll(){
        $('.chat-box').scrollTop($('.chat-box')[0].scrollHeight);
    }


    function noMessageHide() {
        $('.noMessage').remove();
    }



    $(document).on("submit","#messageSend",function(e) {
        e.preventDefault();

            formName = $(this)[0];
            let form = new FormData(formName);

            $.ajax({
                type: "POST",
                url: base_url+'/message/send',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processData: false,
                contentType: false,
                cache: false,
                data:form,
                dataType:"json",
                beforeSend: function() {
                    $("#submitBtn").attr("disabled", true);
                    $("#messageInput").attr("readonly", true);
                },
                success: function(response){
                    if(response.success == true) {
                        formName.reset();

                       $('.chat-box').append(`<div class="chat outgoing">
                            <div class="details">
                                <p>${response.message}</p>
                            </div>
                        </div>`);
                    }

                },
                error: function (data) {

                },
                complete: function() {
                    $("#submitBtn").attr("disabled", false);
                    $("#messageInput").attr("readonly", false);
                },
            });

    });



$(document).on("click",".oldMessage",function(e) {
    e.preventDefault();
    getMessage(uuid,$(this).attr('data-date'));
    $(this).remove();
});
