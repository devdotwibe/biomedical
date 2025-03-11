@extends('layouts.appmasterspace')
<?php
$title       = 'My Work';
$description = 'My Work';
$keywords    = 'My Work';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<script src="{{ asset('js/custom.js') }}"></script>

<section class="form-navbar">
<div class="container">
<div class="row viewbtn">
            <div class="col">
                <div class="viewclientbtn">
                    <a href="{{ route('marketspace/editprofile') }}">View Client Profile</a>
                </div>
            </div>
            <div class="col">
              <div class="userdropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                @if($marketspace) {{ $marketspace->name }} @endif <span class="caret"><i class="fa fa-caret-down"></i></span>
                                </a>

                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                   
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    Edit Profile
                                    </a>

                                    <a class="dropdown-item" href="{{ route('marketspace/editprofile') }}">
                                    Settings
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/mywork') }}">
                                    My Work
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/logout') }}" >
                                        {{ __('Logout') }}
                                    </a>
                                </div>
              </div>
            </div>
        </div>
</div>
</section>

<section class="hired-page" style="margin-top:100px;">
        <div class="container">
        @if(session()->has('message'))
    <div class="alert alert-success">
        {!! session()->get('message') !!}
    </div>
@endif
            <div class="row">

          

                <div class="col-md-8">
                    <div class="services-tab">
                        <div class="services-tab__container">
                        
                          <div class="services-tab__list-topic">
                          <ul class="services-tab__nav">
                        
                            <li class="services-tab__list-item">
                              <a href="#tab_1" class="services-tab__link-item is-active">
                                <span>Hired</span>
                                        
                              </a>
                            </li>
                        
                            <li class="services-tab__list-item">
                              <a href="#tab_2" class="services-tab__link-item">
                                <span>Completed</span>
                              </a>
                            </li>
                        
                            
                        
                          </ul>
                          </div><!-- /list-topic -->
                        
                          <div class="services-tab__list-content">
                            <div id="tab_1" class="services-tab__tab-item is-visible">
                            @if(count($marketspace_hire)==0)
                            <div class="n-result">No Result</div>
                            @endif
                           
                           
                            @if(count($marketspace_hire)>0)
                            @foreach($marketspace_hire as $val)

                           
                              <div class="hire-card">
                                  <div class="hire-head">
                                      <div class="h-idtitle">
                                        <div class="jobid">Job ID: {{$val->job_id}}</div>
                                        @php 
                                        $hire_user = DB::select("select * from `marketspace` where `id`='$val->hire_service_id' ");
                                        @endphp

                                        @if(count($hire_user)>0)
                                        <div class="jobUsername">Name: <a href="{{ url('marketspace/profile/'.$val->hire_service_id) }}">{{$hire_user[0]->name}}</a></div>
                                        @endif
                                      </div>
                                      <div class="hire-date">{{ \Carbon\Carbon::parse($val->updated_at)->format('M D')}} @ {{ \Carbon\Carbon::parse($val->updated_at)->format('H:i A')}}</div>
                                  
                                    </div>
                                    <div class="job-title">{{$val->title}}</div>
                                  <div class="hire-details">
                                    <div class="hire-content">
                                        <p>{{$val->details}}</p>
                                    </div>
                                    
                                    <!-- <button class="hire-chat startmsgbtn" attr-id="{{$val->marketspace_id}}">Chat</button> -->
                                    <a href="{{ route('marketspace/chat') }}" class="hire-chat startmsgbtn hired-chat" attr-id="{{$val->hire_service_id}}">Chat</a>
                                    
                                    <div class="loader_sec{{$val->marketspace_id}}" style="display:none;">
			<img src="{{ asset('images/wait.gif') }}" alt=""/></div>

                      @if(session('MARKETSPACE_ID')==$val->marketspace_id)
                                    <button class="hire-chat setcompleted" attr-id="{{$val->id}}">Completed</button>
                                  @endif

                                  </div>

                                  <div class="chat_display{{$val->marketspace_id}}"></div>
                                  
                              </div>
                             

                              @endforeach
                              @endif


                             


                            </div>
                        
                            <div id="tab_2" class="services-tab__tab-item">
                            @if(count($marketspace_hire_complete)==0)
                            <div class="n-result">No Result </div>
                            @endif
                            @if(count($marketspace_hire_complete)>0)
                            @php $k=0; @endphp
                            @foreach($marketspace_hire_complete as $val)

                           
                              <div class="hire-card">
                                <div class="hire-head">
                                    <div class="h-idtitle">
                                      <div class="jobid">Job ID: {{$val->job_id}}</div>
                                      
                                      @php 
                                        $hire_user = DB::select("select * from `marketspace` where `id`='$val->hire_service_id' ");
                                        @endphp

                                        @if(count($hire_user)>0)
                                        <div class="jobUsername">Name: <a href="{{ url('marketspace/profile/'.$val->hire_service_id) }}">{{$hire_user[0]->name}}</a></div>
                                        @endif

                                    </div>
                                    <div class="hire-date">{{ \Carbon\Carbon::parse($val->updated_at)->format('M D')}} @ {{ \Carbon\Carbon::parse($val->updated_at)->format('H:i A')}}</div>
                                    
                                </div>
                                <div class="job-title">{{$val->title}}</div>
                                <div class="hire-details">
                                  <div class="hire-content">
                                      <p>{{$val->details}}</p>
                                  </div>
                                  
                                  @if(session('MARKETSPACE_ID')==$val->marketspace_id)
                                  
                                  @php 
                                  $log_id=session('MARKETSPACE_ID');
                                  $rating_exit = DB::select("select * from `rating` where `post_id`='$val->hire_service_id' AND  `user_id`='$log_id' ");
                                  @endphp


                                  @if(count($rating_exit)==0)
                                  <a href="{{ url('marketspace/rating/'.$val->hire_service_id.'/'.$val->marketspace_id.'/'.$val->id) }}" class="post-feedback">Post Feedback</a>
                                  @endif

                                  @endif


                                  


                                  
                                </div>

                          
                              </div> 

                              @endforeach

                          

                              @endif

                            </div>
                        </div><!-- /services-tab__list-content -->
                        </div>
                        </div>
                </div>

                <div class="col-md-4">
                    <div class="help-info-card">
                        <div class="help-title">Helpful Information</div>
                        <div class="help-list">
                            <ul>
                                <li>Information list</li>
                                <li>Information two</li>
                                <li>Information three</li>
                            </ul>
                        </div>
                    </div>
                </div>

                </div>
                


            </div>
        </div>
    </section>

    


<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog confirm-d">
        <div class="modal-content">
            <div class="modal-header">
              Confirm
            </div>
            <div class="modal-body">
            Are you sure to complete?
            </div>
            <div class="modal-footer">
              <inpu type="hidden" name="hire_id" id="hire_id">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="confirmok">Yes</button>
                <div class="loadedstatus" style="display:none;">
			<img src="{{ asset('images/wait.gif') }}" alt=""/></div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
<script>
$(document).on('click', '#confirmok', function(){

$(".loadedstatus").show();
  $.ajax({
      url:'{{ route("statuscompleted") }}',
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
      type: 'post',
      data: {
        id: $("#hire_id").val()
      },
      success: function( data ) {
      location.reload();
     $(".loadedstatus").hide();
      }
    });
});

$(document).on('click', '.setcompleted', function(){
  var id=$(this).attr('attr-id');
  $("#hire_id").val(id);
  $("#confirm").modal("show");
  
  
});
$(document).on('click', '.startmsgbtn', function(){
    var service_id=$(this).attr('attr-id');
    
    $(".loader_sec"+service_id).show();
   $.ajax({
      url:'{{ route("chat") }}',
        headers: {
            'X-CSRF-Token': '{{ csrf_token() }}',
        },
      type: 'post',
      data: {
        service_id:service_id
      },
      success: function( data ) {
        $(".loader_sec"+service_id).hide();
        $(".loader_sec_private_chat").hide();
        location.href="{{ route('marketspace/chat') }}";
       /* $('.chat_display'+service_id).html(data);

        $("#message").focus();
        $(".msg_history").animate({
                    scrollTop: $(
                      '.msg_history').get(0).scrollHeight
                }, 2000);*/
                
      }
    });

});

window.onload = function() {

// Variables 	
var tabLinks = document.querySelectorAll('.services-tab__link-item');
var tabContents = document.querySelectorAll('.services-tab__tab-item');

// Loop through the tab link
for (var i = 0; i < tabLinks.length; i++) {
  tabLinks[i].addEventListener('click', function(e) {
    e.preventDefault();
    var id = this.hash.replace('#', '');

    // Loop through the tab content
    for (var j = 0; j < tabContents.length; j++) {
              var tabContent = tabContents[j];
      tabContent.classList.remove('is-visible');
      tabLinks[j].classList.remove('is-active');
      if (tabContent.id === id) {
        tabContent.classList.add('is-visible');
      }
    }
          
    this.classList.add('is-active');
  });
}
  
}
    
</script>
@endsection
