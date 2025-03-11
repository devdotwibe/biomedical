@extends('layouts.appmasterspace')
<?php
$title       = 'View Bid';
$description = 'View Bid';
$keywords    = 'View Bid';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('js/java.js') }}"></script>
<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white custom-nav">
            <!-- <i class="fa fa-bars mobile-menu"></i> -->
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
            <div class="collapse navbar-collapse" id="user-img">
                @include('marketspace/navbar')
                  </div>
        </nav>
    </header>

    <div class="container-fluid height100">
        <div class="row dashboard-row leftnone">

            <main class="col-md-10" id="main">
                <section class="content-wrap bg-none">
                    <div class="content-col1">
                    <div class="content-col1-head">

                    </div>


<section class="service-request service-request-wrap">
        <div class="container">
          <div class="row"><div class="col service-heading"><h3>View Bid</h3></div>
            
          </div>
            <div class="row serv-req-row">
                <div class="col-md-12">
                    <div class="services-tab">
                        <div class="services-tab__container">
                        
                          <div class="services-tab__list-topic">
                          <ul class="services-tab__nav">
                        
                          
                        
                        
                        
                            
                        
                          </ul>
                          </div><!-- /list-topic -->
                        
                          <div class="services-tab__list-content">
                            <div id="tab_1" class="services-tab__tab-item is-visible">

                                @if(count($data)>0)
                                @foreach($data as $val)
                                  <div class="hire-card">
                                      
                                      <div class="hire-details">
                                        <div class="hire-content">
                            
                                          <div class="firstsec-ser">
                                          <div class="hire-head">
                                          <div class="h-idtitle">
                                            <div class="jobid">
                                            <img src="{{ asset('images/job-icon.svg') }}" alt="job-icon"> 
                                             Job ID: {{$val->id}}
                                            </div>
                                           </div>
                                          <div class="hire-date" >
                                             {{ \Carbon\Carbon::parse($val->created_at)->format('M D')}} @ {{ \Carbon\Carbon::parse($val->created_at)->format('H:i A')}}
                                          </div>
                                      </div>
                                            <div class="job-title leftsecdiv" id="hiretitle{{$val->id}}">
                                              <h5>Service Title</h5>
                                              {{$val->title}}
                                            </div>
                                            <div class="job-datadesc leftsecdiv" id="hiredesc{{$val->id}}">
                                              <h5>Description</h5>
                                              <p >{{$val->description}}</p>
                                            </div>

                                            
                                          </div>
                            
                                          <div class="secondsec-ser">
                                            <div class="job-data" id="hiredate{{$val->id}}">
                                              <h5> Service Date</h5>
                                              {{$val->service_date}}
                                            </div>
                                            <div class="job-data" id="hiretime{{$val->id}}">
                                              <h5> Service Time</h5>
                                              {{$val->start_time}}
                                            </div>
                                            <div class="job-data" id="hiretype{{$val->id}}">
                                              <h5> Service Machine</h5>
                                              @php echo App\Product::get_product_details($val->product_id);@endphp
                                            </div>
                                         
                                            <div class="job-data" id="hiretype{{$val->id}}">
                                              <h5>Criteria Preference </h5>
                                             {{ $val->preference }}
                                            </div>
                            
                                            <div class="action">
                                            @if($val->auth_by_user=='Y')
                            
                                            @if($val->status=='Progress' && $marketspace->id==$val->service_staff)
                                            <button class="job-accept accept_job" data-id="{{$val->id}}">Approve</button> <button data-modal="1" data-id="{{$val->id}}" class="job-reject reject_job_staff">Reject</button>
                                            @endif
                                
                                            @if($val->status=='Accept_staff' && $marketspace->id==$val->service_staff)
                                            
                                            <span class="accept_req_sec" style="color:green">Accept request send to customer after verify customer will contact soon.</span>
                                            @endif
                                
                                
                                            @endif
                                
                                            @if($val->auth_by_user=='N' && $marketspace->id!=$val->marketspace_id)
                                            <button class="job-accept accept_job_auth" data-id="{{$val->id}}">Approve</button> <button data-id="{{$val->id}}" class="job-reject reject_job_auth">Reject</button>
                                            @endif
                            
                                           
                            
                                            
                                          </div>
                            
                            
                                          </div>
                            
                                        </div>
                            
                            
                                        {{--  --}}
                                        @php $service_staffs= App\Marketspace::get_service_accept_staff_marketspace($val->marketspace_id,$val->product_id); @endphp
                                        @if(count($service_staffs)>0 && $marketspace->user_type=="Hire")
                                        <h3>Engineer Profile</h3>
                                        <div id="accordion">
                                          @foreach($service_staffs as $keys=>$staffs)
                                         
                            
                                            <div class="card">
                                              <div class="card-header" id="{{ $keys }}">
                                                <h5 class="mb-0">
                                                  <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne{{ $keys }}" aria-expanded="true" aria-controls="collapseOne">
                                                   <b>{{ $staffs->name }}</b>, Quote: {{$staffs->service_staffquote_price}} ,
                                                   Date {{$staffs->service_approve_date}} , Time {{$staffs->service_staff_time}}
                                                  </button>
                                                </h5>
                                              </div>
                                          
                                              <div id="collapseOne{{ $keys }}" class="collapse " aria-labelledby="{{ $keys }}" data-parent="#accordion">
                                                <div class="card-body">
                                                  <table>
                                                    <tr>
                                                      <th>Service Quote</th>
                                                      <th>Engineer Available Date</th>
                                                      <th>Engineer Available Time</th>
                                                    </tr>
                                                    <tr>
                                                      <td>{{$staffs->service_staffquote_price}}</td>
                                                      <td>{{$staffs->service_approve_date}}</td>
                                                      <td>{{$staffs->service_staff_time}}</td>
                                                    </tr>
                                                  </table>
                            
                                                  {{-- start row --}}
                                                  <div class="row">
                            
                                                    <div class="col-md-3">
                                                      <h5>Skills</h5>
                                                      @php $service_staff_skill= App\Marketspace::get_service_staff_skill($val->service_staff); @endphp
                                                      @if(count($service_staff_skill)>0)
                                                      <ul>
                                                      @foreach($service_staff_skill as $val_skill)
                                                        <li>{{ App\Marketspace::get_productname($val_skill->product_id) }}</li>
                                                      @endforeach
                                                    </ul>
                                                      @endif
                                      
                                                    </div>
                                      
                                                    <div class="col-md-3">
                                                      <h5>Verified</h5>
                                                    </div>
                                      
                                                    <div class="col-md-3">
                                                      <h5>Year of Experience</h5>
                                                      @php $service_staff_exp= App\Marketspace::get_service_staff_experiance($val_skill->service_staff); @endphp
                                                      @if(count($service_staff_exp)>0)
                                                      <ul>
                                                      @foreach($service_staff_exp as $val_exp)
                                                      @php $days=array(); @endphp
                                                      @if($val_exp->from_date!='')
                                                        @php 
                                                          $startTimeStamp = strtotime($val_exp->from_date);
                                                          $endTimeStamp = strtotime($val_exp->to_date);
                                      
                                                          $timeDiff = abs($endTimeStamp - $startTimeStamp);
                                      
                                                          $numberDays = $timeDiff/86400;  // 86400 seconds in one day
                                      
                                                          // and you might want to convert to integer
                                                          $numberDays = intval($numberDays);
                                                          $days[]= $numberDays;
                                      
                                                        @endphp
                                                      @endif
                                                        
                                                      @endforeach
                                                      <li>@php
                                                      $days=array_sum($days);
                                                      if( $days>0){
                                                      $years = intval($days / 365); 
                                                      $days = $days % 365;
                                      
                                                      $months = intval($days / 30); 
                                                      $days = $days % 30;
                                                      echo "$years years, $months months, $days days";
                                                      }
                                                      @endphp
                                                        
                                                      </li>
                                                        @endif
                                                    </div>
                                      
                                                    <div class="col-md-3">
                                                      <h5>Rating</h5>
                                                    </div>
                                      
                                                  </div>
                                                  {{-- end row --}}
                            
                                                  <button class="job-accept accept_job_customer"  data-date="{{$val->service_approve_date}}" id="service_acceptbtn{{$val->id}}"  data-id="{{$val->id}}">Select</button>
                                                   {{-- <button data-id="{{$val->id}}" class="job-reject reject_job_customer">Reject</button> --}}
                            
                            
                                                </div>
                                              </div>
                                            </div>
                                        
                                          
                                         
                                          @endforeach
                                        </div>

                                        
                                        @endif
                                         {{--  --}}

                                      

                                     

                                        
                                        
                                        
                                       
                            
                                      </div>

                                        {{-- ifstart --}}
                                        @if(count($service_staffs)>0 && $marketspace->user_type=="Hire")
                                        <div class="reject-service">
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="delete_reason" id="forgetting_eng" attr-id="{{ $val->id }}" value="forgetting_eng">
                                          <label class="form-check-label" for="forgetting_eng">
                                            For getting another Service Engineer based on your preference. Edit your preference options
                                           
                                          </label>
                                        </div>
                                        <div class="reason-another-ser"></div>
                                      
                            
                            
                            
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="delete_reason" value="need_support" id="needsupport" >
                                          <label class="form-check-label" for="needsupport">
                                            I am confused, i require technical support
                                          </label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="delete_reason"  value="issueresolved" id="issueresolved" >
                                          <label class="form-check-label" for="issueresolved">
                                            My issue resolved
                                          </label>
                                        </div>
                                        <div class="form-check">
                                          <input class="form-check-input" type="radio" name="delete_reason"  value="Other" id="other" >
                                          <label class="form-check-label" for="other">
                                            Other
                                          </label>
                                        </div>
                                        <div class="row other-textarea" style="display:none;">
                                          <div class="form-group col-md-12">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" placeholder="Description" name="description" id="description"></textarea>
                                          </div>
                                        </div>
                            <span class="error staff_already_exit" style="display: none;">No service staff available</span>
                                        <button data-id="{{$val->id}}" class="job-reject job_reject_customer">Reject</button>
                            
                                        </div>
                                        @endif

                                        {{--if end  --}}
                                        
                                  </div>


                                  
                                  @endforeach
                                  @endif
                                  @if(count($data)==0)
                                    <p class="no_result">No Result</p>
                                  @endif
                            
                                </div>
                            

                            
                              
                        </div><!-- /services-tab__list-content -->
                        </div>
                        </div>
                </div>
               
            </div>
        </div>
    </section>



      {{-- ----------------------------- --}}
    </div>
                    
</section>
<div class="right-side-bar">
    @include('marketspace/right-sidebar')
</div>

</div>
</div>



    @include('marketspace.request_service_modalpopup')        

@endsection
@section('scripts')


<script>

$('input[type=radio][name=delete_reason]').change(function() {
  
    if (this.value == 'Other') {
        $(".other-textarea").show();
        $(".reason-another-ser").hide();
    }
    else if (this.value == 'forgetting_eng') {
      $(".other-textarea").hide();
      var id=$(this).attr('attr-id');
      $.ajax({
          type: "POST",
          url:'{{ route("get-anotherservice-staff-form") }}',
        data:"id="+id,
              // dataType: "json",
              headers: {
                              'X-CSRF-Token': '{{ csrf_token() }}',
                      },
                
          success: function(resultData){
            $(".reason-another-ser").show();
            $(".reason-another-ser").html(resultData);
              }
          });

     
     
      
    }else{
      $(".other-textarea").hide();
      $(".reason-another-ser").hide();
    }
});

   
 $('.service_acceptchk').change(function() {
  var id=$(this).attr("data-id"); 
        if(this.checked) {
          
        $("#service_acceptbtn"+id).prop('disabled', false);
        }
        else{
          $("#service_acceptbtn"+id).prop('disabled', true);
        }
        
    });
    
$("#job_reject_auth").click(function(){
        var id=$("#job_auth_reject_id").val();
        $(".rejectjob_gif").show();
        $.ajax({
            type:'POST',
            url:'{{ route("reject_service_request_auth_user") }}',
            //dataType:'json',
            data: {
                customer_reject_id:id
                },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            success:function(data){

              var num='91'+data;
                $.ajax({
                type: "POST",
                url:'{{ route("send_sms_message") }}',
              //  url: "https://betablaster.in/api/send.php?number="+num+"&type=text&message=Service request rejected&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
              data:"number="+num+'&type=service_rejected',
                   // dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });

            $(".rejectjob_gif").hide();
            $("#confirm_reject").modal("hide");
            $("#successmodal_reject_req").modal("show");
            
            }, 
            error: function(data){
            console.log('error')
            }
            });
    })

    
$("#job_accept_auth").click(function(){
        var id=$("#job_auth_accept_id").val();
        $(".rejectjob_gif").show();
        $.ajax({
            type:'POST',
            url:'{{ route("accept_service_request_auth_user") }}',
            //dataType:'json',
            data: {
                customer_reject_id:id
                },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            success:function(data){
              var result = data.split('*');
              
              var num='91'+result[0];
                $.ajax({
                type: "POST",
                url:'{{ route("send_sms_message") }}',
               // url: "https://betablaster.in/api/send.php?number="+num+"&type=text&message=Service request accepted&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
               data:"number="+num+'&type=service_accepted',
                   // dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });

                var num='91'+result[1];
                $.ajax({
                type: "POST",
                url:'{{ route("send_sms_message") }}',
               // url: "https://.php?number="+num+"&type=text&message=This is remider from customer service request booked,reply yes to confirm&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
               data:"number="+num+'&type=service_request',
                   // dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });

            $(".rejectjob_gif").hide();
            $("#confirm_reject").modal("hide");
            $("#successmodal_accept_auth_req").modal("show");
            
            }, 
            error: function(data){
            console.log('error')
            }
            });
    })

    $(".accept_job_auth").click(function(){
        
        var id=$(this).attr('data-id');
        $("#job_auth_accept_id").val(id);
        $("#confirm_accept_job_auth").modal("show");
    })
    $(".reject_job_auth").click(function(){
        
        var id=$(this).attr('data-id');
        $("#job_auth_reject_id").val(id);
        $("#confirm_reject_job_auth").modal("show");
    })


    $(".reject_job_customer").click(function(){
        
        var id=$(this).attr('data-id');
        $("#job_reject_id").val(id);
        $("#confirm_reject").modal("show");
    })
    $(".reject_job_staff").click(function(){
        
        var id=$(this).attr('data-id');
        $("#job_reject_staff_id").val(id);
        $("#confirm_reject_staff").modal("show");
    })
    
    $("#job_reject_staff").click(function(){
        var id=$("#job_reject_staff_id").val();
      

        $(".rejectjob_gif").show();
        $.ajax({
            type:'POST',
            url:'{{ route("reject_service_request") }}',
            //dataType:'json',
            data: {
                customer_reject_id:id,
                },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            success:function(data){

       

            $(".rejectjob_gif").hide();
            $("#confirm_reject").modal("hide");
            $("#successmodal_reject_req").modal("show");
          
        

         

            
            }, 
            error: function(data){
            console.log('error')
            }
            });
    })


    $(".job_reject_customer").click(function(){
        var id=$(this).attr('data-id');
       
        var delete_reason=$("input[name=delete_reason]:checked").val();
        var start_date=$("#start_date").val();
        var start_time=$("#start_time").val();
        var flag=1;
        if(delete_reason=="forgetting_eng"){
          var form = $("#frm_request");
          form.validate({
          rules: {
              start_date: {
                  required:true,
              },
              start_time: {
                  required:true,
              },
              
          },
          messages: {
              start_date: {
                  required:"Field is required!",
              },
              start_time: {
                  required:"Field is required!",
              },
            
          }
          }); 
          if(form.valid() === true) {
            flag=1;
          }
          else{
            flag=0;
          }
        }

        if(flag==1){

        $(".rejectjob_gif").show();
        $.ajax({
            type:'POST',
            url:'{{ route("reject_service_request") }}',
            //dataType:'json',
            data: {
                customer_reject_id:id,delete_reason:delete_reason,start_date:start_date,start_time:start_time
                },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            success:function(data){
            
          if(data==0){
            $(".staff_already_exit").show();
          }else{
            $(".staff_already_exit").hide();
            $(".rejectjob_gif").hide();
            $("#confirm_reject").modal("hide");
            $("#successmodal_reject_req").modal("show");
      
          }

            }, 
         
            });

          }
    })

     $("#job_complete").click(function(){
        var id=$("#job_complete_id").val();
        $(".completejob_gif").show();
        $.ajax({
            type:'POST',
            url:'{{ route("complete_service_request") }}',
            //dataType:'json',
            data: {
                customer_complete_id:id
                },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            success:function(data){
            $(".completejob_gif").hide();
            $("#confirm_complete").modal("hide");
            $("#successmodal_complete_req").modal("show");

            var num='91'+data;
           // alert(num)
                $.ajax({
                type: "POST",
                url:'{{ route("send_sms_message") }}',
              //  url: "https://betablaster.in/api/send.php?number="+num+"&type=text&message=Service request rejected&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
              data:"number="+num+'&type=service_complete',
                   // dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });

            
            }, 
            error: function(data){
            console.log('error')
            }
            });
    })

    $(".job_completed").click(function(){
        
        var id=$(this).attr('data-id');
        $("#job_complete_id").val(id);
        $("#confirm_complete").modal("show");
    })
    $("#approve_accept_job_customer").click(function(){
        $(".acceptjob_cust_gif").show();
        
            $.ajax({
            type:'POST',
            url:'{{ route("approve_service_customer") }}',
            //dataType:'json',
            data: {
                customer_accept_id:$("#customer_accept_id").val()
                },
            headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
            success:function(data){
            $(".acceptjob_cust_gif").hide();
            $("#acceptmodal_customer").modal("hide");
            $("#successmodal_accept_cust").modal("show");
          

            }, 
            error: function(data){
            console.log('error')
            }
            });
    });

   $("#approve_accept_job").click(function(){
    var form = $("#accept_form_approve");
form.validate({
 rules: {
   
     accept_date: {
        required:true,
     },
     accept_time: {
        required:true,
     },
     accept_quote_price: {
        required:true,
     },
     
 },
 messages: {
    accept_date: {
         required:"Field is required!",
     },
     accept_time: {
         required:"Field is required!",
     },
     accept_quote_price: {
         required:"Field is required!",
     },

 }
}); 
if(form.valid() === true) {
    $(".acceptjob_gif").show();
    $.ajax({
    type:'POST',
    url:'{{ route("approve_service_staff") }}',
    //dataType:'json',
    data: $("#accept_form_approve").serialize(),
    headers: {
        'X-CSRF-Token': '{{ csrf_token() }}',
    },
    success:function(data){

    /*  var num='91'+data;
                $.ajax({
                type: "POST",
                url:'{{ route("send_sms_message") }}',
               // url: "https://betablaster.in/api/send.php?number="+num+"&type=text&message=Service request accepted&instance_id=624670E2CEE99&access_token=f702ce3e02efde2b881a7d1a9da55da7",
               data:"number="+num+'&type=service_accepted',
                  //  dataType: "json",
                    headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });*/

      $(".acceptjob_gif").hide();
      $("#acceptmodal").modal("hide");
    $("#successmodal_accept").modal("show");
    }, 
    error: function(data){
      console.log('error')
    }
    });

}
   });
   $(".accept_job").click(function(){
    $("#acceptmodal").modal("show");
    var id=$(this).attr('data-id');
    $("#accept_id").val(id);
    $("#hiretitle").html($("#hiretitle"+id).html());
    $("#date").html($("#hiredate"+id).html());
    $("#desc").html($("#hiredesc"+id).html());
    });

    $(".accept_job_customer").click(function(){
    $("#acceptmodal_customer").modal("show");
    var id=$(this).attr('data-id');
    var ser_date=$(this).attr('data-date');
    
    $("#customer_accept_id").val(id);
    $("#hiretitle_customer").html($("#hiretitle"+id).html());
    $("#date_customer").html($("#hiredate"+id).html());
    $("#desc_customer").html($("#hiredesc"+id).html());
    $("#accept_customer_date").html('Service staff accept date: '+ser_date);
    });
    
    

$('#successmodal_accept').on('hidden.bs.modal', function () {
 location.reload();
})
$('#successmodal_accept_cust').on('hidden.bs.modal', function () {
 location.reload();
})
$('#successmodal_complete_req').on('hidden.bs.modal', function () {
 location.reload();
})
$('#successmodal_reject_req').on('hidden.bs.modal', function () {
 location.reload();
})
$('#successmodal_accept_auth_req').on('hidden.bs.modal', function () {
 location.reload();
})



</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#accept_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                    changeYear: true,
                    yearRange: "1990:2040",
                    minDate: 0  
                });
      
    </script>
@endsection
<style>
    .main-footer{display:none;}
    </style>