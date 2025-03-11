@extends('layouts.appmasterspace')
<?php
$title       = 'Service Request';
$description = 'Service Request';
$keywords    = 'Service Request';
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
          <div class="row"><div class="col service-heading"><h3>Service Request</h3></div>
            
          </div>
            <div class="row serv-req-row">
                <div class="col-md-12">
                    <div class="services-tab">
                        <div class="services-tab__container">
                        
                          <div class="services-tab__list-topic">
                          <ul class="services-tab__nav">
                        
                            <li class="services-tab__list-item">
                              <a href="#tab_1" class="services-tab__link-item is-active">
                                <span>Service Request</span>
                                        
                              </a>
                            </li>
                        
                            <li class="services-tab__list-item">
                              <a href="#tab_2" class="services-tab__link-item">
                                <span>Accepted</span>
                              </a>
                            </li>

                            <li class="services-tab__list-item">
                              <a href="#tab_3" class="services-tab__link-item">
                                <span>Rejected</span>
                              </a>
                            </li>

                            <li class="services-tab__list-item">
                              <a href="#tab_4" class="services-tab__link-item">
                                <span>Completed</span>
                              </a>
                            </li>
                        
                            
                        
                          </ul>
                          </div><!-- /list-topic -->
                        
                          <div class="services-tab__list-content">
                            <div id="tab_1" class="services-tab__tab-item is-visible">

                              @include('marketspace.service-request-tab1')

                              @include('marketspace.service-request-tab2')

                              @include('marketspace.service-request-tab3')

                              @include('marketspace.service-request-tab4')
                              
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
            var num='91'+data;
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