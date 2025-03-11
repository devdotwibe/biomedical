@extends('layouts.appchat')

@section('content')
    <div class="container-fluid" style="margin-top:200px;">
        <div class="row">
            <div class="col-md-4">
                <div class="user-wrapper">
                    <ul class="users">
                        @foreach($users as $user)
                        @php
                        $cur=session('MARKETSPACE_ID');
                        $user_exit = DB::select("select * from `marketspace_chat_user` where
                        (`from_user`='$user->id' OR `from_user`='$cur')
                        AND (`to_user`='$user->id' OR `to_user`='$cur')
                        ");
                      
                        @endphp

                        @if(count($user_exit)>0)
                            <li class="user" id="{{ $user->id }}">
                                {{--will show unread count notification--}}
                                @if($user->unread)
                                    <span class="pending">{{ $user->unread }}</span>
                                @endif

                                <div class="media">
                                    <div class="media-left">
                                      
                                        @if($user->image!='')
                                        <img src="{{ asset('storage/app/public/masterspace/'.$user->image) }}" id="category-img-tag"  />
                                        @endif
                                        @if($user->image=='')
                                        <img src="{{ asset('images/noimage.jpg') }}" id="category-img-tag">
                                        @endif

                                    </div>

                                    <div class="media-body">
                                        <p class="name">{{ $user->name }}</p>
                                        <p class="email">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </li>
                            @endif

                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-8" id="messages">

            </div>
        </div>
    </div>
@endsection