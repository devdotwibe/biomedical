@extends('layouts.appmasterspace')
<?php
$title       = 'Request Service Edit';
$description = 'Request Service Edit';
$keywords    = 'Request Service Edit';
$message="";

?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<script src="{{ asset('js/custom.js') }}"></script>


<section class="form-edit" style="margin-top:200px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-list dropdown">
                        
                        <a href="#" onclick="myFunction()" class="dropbtn">Select Option 
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <div class="dropdown-content" id="myDropdown">

                      
                        @include('marketspace.sidebar')
                        </div>
                    
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="tab-card">
				<div class="tab-card-header">
 

					<h5>	Request Service</h5>
				</div>
				
              

         
<div class="tab-card-form">
				
            <form autocomplete="off" role="form" name="frm_request" id="frm_request" method="post" action="{{route('ibstore')}}" enctype="multipart/form-data" >
               @csrf
					
					<div class="form">
						<div class="row">
							<div class="form-group col-md-6">
								<label for="pannumber">Service Title</label>
								<input type="text" name="title" id="title" class="form-control" placeholder="Title" value="{{ $market_req->title }}">
                <input type="hidden" class="form-control" name="product_id" id="product_id" value="{{$id}}">
						  </div>

              <div class="form-group col-md-6">
								<label for="start_date">Select Service Date</label>
									<input type="text" name="start_date" id="start_date" class="form-control" value="{{ $market_req->service_date }}">
							</div>
					  </div>


					<div class="row">
						<div class="form-group col-md-12">
							<label for="description">Description</label>
							<textarea class="form-control" placeholder="Description" name="description" id="description">{{ $market_req->description }}</textarea>
						</div>
					</div>

                    <div class="row">
                        <div class="form-check col-md-12">
                        <label class="control-label">Criteria preference</label>
                        <p>Drag and drop criteria based on higest to lowest</p>
                    </div>

                    <div class="form-check col-md-12">
                    <ul id="sortable1" class="connectedSortable">
                        <li class="ui-state-default"> Skills </li>
                        <li class="ui-state-default">Quick Availability</li>
                        <li class="ui-state-default">Lowest Rate</li>
                        <li class="ui-state-default">Verified</li>
                
                      </ul>
                    </div>

                    <div class="form-check col-md-3">
                        
                        
                        <label class="form-check-label" for="flexCheckDefault">
                            Skills @if(count($marketspace_skill)==0) <span style="color:red;">Not available</span> @endif
                            @if(count($marketspace_skill)>0) <span style="color:green;">available</span> @endif
                        </label>
                      </div>
                      <div class="form-check col-md-3">
                        
                        <label class="form-check-label" for="flexCheckChecked">
                            Quick Availability @if(count($data_date)==0) <span style="color:red;"> Not available </span> @endif
                            @if(count($data_date)>0) <span style="color:green;">available</span> @endif
                        </label>
                      </div>
                   
                    </div>
                        {{-- <div class="form-group col-md-12">
                            <label class="control-label">Criteria preference</label>
                            <p>Drag and drop criteria based on higest to lowest</p>
                        <select
                        multiple="multiple"
                        name="preference[]"
                        id="preference_select"
                    >
                   
                                <option value="Skills" >Skills</option>
                                <option value="Quick Availability" >Quick Availability</option>
                                
                                <option value="Lowest Rate" >Lowest Rate</option>
                             
                    </select>
                </div> --}}
                    
                       
                 
                    

                    {{-- <div class="row">
                        <div class="form-group col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="auth_by_user" id="auth_by_user1" onclick="add_contact_div_hide()" value="Y" checked>
                            <label class="form-check-label" for="flexRadioDefault1">
                                I certify that i am authorised person
                            </label>
                          </div>
                        </div>
                        <div class="form-group col-md-12">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="auth_by_user" id="auth_by_user2" onclick="add_contact_div()"  value="N">
                            <label class="form-check-label" for="flexRadioDefault2">
                                Select Authorised Person
                            </label>
                          </div>
                        </div>
                          
                    </div> --}}

					{{-- <div class="row">
            <div class="form-group col-md-12">
                    <div class="form-check contact_checksec">
                <input class="form-check-input" type="checkbox" value="Y" name="auth_by_user" id="auth_by_user">
                <span class="error_message" id="auth_by_user_message" style="display: none;color:red;">Field is required</span>
                <label class="form-check-label" for="flexCheckDefault">
                I certify that i am authorised person if not select on &nbsp; 
                </label><a onclick="add_contact_div()" class="clickHere">Click here</a>
                <input type="hidden" name="add_contact" id="add_contact" value="0">
                </div>
                </div>
                </div> --}}
              
						
						<div class="card-savebtn">
                            <div class="loader_sec_req" style="display:none;">
                                <img src="{{ asset('images/wait.gif') }}" alt=""/></div>
                                
							    <button @if(count($marketspace_skill)==0 && count($data_date)==0) disabled="true" @endif type="button" class="btn btn-default" id="create-request">Submit Request</button>
                                
							</div>
                            </form>
					</div>
				</div>

			</div>
                        </div>

                    

                        

                       
                      </div>
                </div>
            </div>
        </div>
    </section>

       
<div class="modal fade" id="successmodal_req_save" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
					
                    <div class="modal-body">
                       
						<div class="thank-you-pop">
							<img src="{{ asset('images/accepted-icon.svg') }}" alt="" width="100" height="100">
							<p>Service request saved successfully. A service staff assgined to request</p>
							<!-- <p>Your job accepted service staff will contact soon.</p> -->
							
 						</div>
                         
                    </div>
					
                </div>
            </div>
        </div>


    @include('marketspace.contact_person')

@endsection
@section('scripts')

<style>
    #sortable1, #sortable2 {
      border: 1px solid #eee;
      width: 142px;
      min-height: 20px;
      list-style-type: none;
      margin: 0;
      padding: 5px 0 0 0;
      float: left;
      margin-right: 10px;
    }
    #sortable1 li, #sortable2 li {
      margin: 0 5px 5px 5px;
      padding: 5px;
      font-size: 1.2em;
      width: 120px;
    }
    </style>

<script>
$( function() {
    $( "#sortable1" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  } );
$('#successmodal_req_save').on('hidden.bs.modal', function () {
    location.href='{{ route("marketspace/allservicerequest") }}';
})
    
    function remove_contact_div(){
    $(".contact_sec").hide();
    $(".contact_checksec").show();
    $("#add_contact").val(0);
    $("#contact_person").val('');
}

function add_contact_div(){
    $(".contact_sec").show();
    $(".contact_checksec").hide();
    $("#add_contact").val(1);
}
function add_contact_div_hide(){
    $(".contact_sec").hide();
    $(".contact_checksec").hide();
    $("#add_contact").val(1);
}

function validate_contact()
{

 var form = $("#contactform");
form.validate({
 rules: {
    name: {
        required:true,
     },
     title: {
        required:true,
     },
    //  email: {
    //     required:true,
    //     email: true,
    //  },
     mobile: {
        required:true,
     },
     phone: {
        required:true,
     },
     password: {
        required:true,
     },
     department: {
        required:true,
     },
     designation: {
        required:true,
     },
     
     
     
 },
 messages: {
   
    name: {
         required:"Field is required!",
     },
     title: {
         required:"Field is required!",
     },
    //  email: {
    //      required:"Field is required!",
    //  },
     mobile: {
         required:"Field is required!",
     },
     phone: {
         required:"Field is required!",
     },
     password: {
         required:"Field is required!",
     },
     department: {
         required:"Field is required!",
     },
     designation: {
         required:"Field is required!",
     },
    
 }
}); 
if(form.valid() === true) {
  
  var name=$("#name").val();
  var title=$("#title").val();
  var last_name=$("#last_name").val();
  var mobile=$("#mobile").val();
  var whatsapp=$("#whatsapp").val();
  var email=$("#email").val();
  var phone=$("#phone").val();
  var department=$("#department").val();
  var designation=$("#designation").val();
  var user_id=$("#user_id").val();
  var password=$("#password").val();
  var contact_type=$("input[name='contact_type']:checked").val();

var formData = new FormData();
            
            var image_name1 = $('#image_name1').val();
              if(image_name1 != '') {    
              formData.append('image_name1',$("#image_name1")[0].files[0]);
              }
           var image_name2 = $('#image_name2').val();
            if(image_name2 != '') {    
              formData.append('image_name2',$("#image_name2")[0].files[0]);
            }
          formData.append('title',title);  
          formData.append('mobile',mobile);
          formData.append('whatsapp',whatsapp); 
          formData.append('last_name',last_name); 
          formData.append('contact_type',contact_type); 
          
          formData.append('name',name);
         
          formData.append('email',email);
          formData.append('phone',phone);
         
          
          formData.append('department',department);
          formData.append('designation',designation);
          formData.append('user_id',user_id);
          formData.append('password',password);

          formData.append('remark',$("#remark").val());
         
          
          $('.load-add').show();
         
          $.ajax({
          type: "POST",
          cache: false,
           url:'{{ route("add_contact_person") }}',
           headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
            },
          processData: false,
          contentType: false,
         // data:$("#contactform").serialize(),
         data:formData,
          success: function (data)
          {   $('.load-add').hide();
          $("#successshow").show();
          var response = JSON.parse(data);
      var html='<option value="">Select Authorised Person</option>';
      for (var i = 0; i < response.length; i++) {
        html +='<option value="'+response[i]["id"]+'">'+response[i]["name"]+'</option>';
      }
      
      $("#contact_person").html(html);
      $("#myModal").modal("hide");
          }
        }); 

}

}


    function contact(){
$("#myModal").modal("show");
    }
    

    $("#create-request").click(function () {

        var num_staff=$("#num_staff").val();
  var flag=0;
 if(num_staff==0)
 {
    $(".num_staff_error").show();
 
        

 }
 else{
     flag=1;
    $(".num_staff_error").hide();
 }

 var contact_check=$("input[name='auth_by_user']:checked").val();
 
 if(contact_check=="Y")
 {
    flag=0;
 }
 else{
    if (jQuery("#contact_person").val()=='') 
    {flag=1;
        jQuery("#contact_person_message").show();
    }
    else{flag=0;
        jQuery("#contact_person_message").hide();
    }
 }

 var form = $("#frm_request");
form.validate({
 rules: {
    title: {
        required:true,
     },    
     start_date: {
        required:true,
     },
     description: {
        required:true,
     },
    
 },
 messages: {
    title: {
         required:"Field is required!",
     },
    start_date: {
         required:"Field is required!",
     },
     description: {
         required:"Field is required!",
     },
     
   
 }
}); 
if(form.valid() === true) {
  
 if(flag==0)
 {

                      
                        var product_id=$("#product_id").val();
    $(".loader_sec_req").show();
         $.ajax({
            url:'{{ route("save_service_request") }}',
        headers: {
                'X-CSRF-Token': '{{ csrf_token() }}',
            },
        type: 'post',
        data:$("#frm_request").serialize()+'&product_id='+product_id,
        
        success: function( data ) {
          
            $("#loader").hide();
            $("#successmodal_req_save").modal("show");

           /*
            $(".loader_sec_req").hide();
           var result = data.split('*');
            if(result[1]=="service"){
                var num='91'+result[0];
                $.ajax({
                type: "POST",
                headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
            url:'{{ route("send_sms_message") }}',
               
                data:"number="+num+'&type=service_request',
                  //  dataType: "json",
                 
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });
            }
            else{
                
               var num='91'+result[0];
                $.ajax({
                type: "POST",
                headers: {
                                    'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                data:"number="+num+'&type=service_request',
            
              url:'{{ route("send_sms_message") }}',
                  //  dataType: "json",
                   
                            error: function (request, error) {
                                $(".suc_otp").show()
                    },
                success: function(resultData){
                    }
                });
            }

            $("#loader").hide();
            $("#successmodal_req_save").modal("show");
            */
        }
        });
 }

 
   // $("#frm_request").submit();

}

}); 

function checkcount_null() {
    var num_staff = $("#num_staff").val();
    if (num_staff>0) {
        return true;
    } else {
        return false;

    }
  }

</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/multi/multi.min.css') }}" />
<script src="{{ asset('public/assets/multi/multi.min.js') }}"></script>


    <script>
    //       var select = document.getElementById("preference_select");
    // multi(select, {
    //     enable_search: true
    // });

        // $('#start_date').datepicker({
        //             //dateFormat:'yy-mm-dd',
        //             dateFormat:'yy-mm-dd',
        //             changeYear: true,
        //             yearRange: "1990:2040",
        //             minDate: 0  
        //         });
      
 var eve=<?php echo $json_encode_date;?>;


	var datelist = eve;
	
$('#start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
			changeMonth: true,
            changeYear: true,
			
			minDate: 0, 
			yearRange: "-0:+10",
		beforeShowDay: function(d) {
           
            var dmy = "";
            dmy += ("00" + d.getDate()).slice(-2) + "-";
            dmy += ("00" + (d.getMonth() + 1)).slice(-2) + "-";
            dmy += d.getFullYear();
            if ($.inArray(dmy, datelist) >= 0) {
                return [true, ""];
            }
            else {
                return [false, ""];
            }
        },
           // minDate: 0  
            
        });



                $("#kb_drangeInput").on('input', function(){   

                    $(".kb_show_range").text($(this).val());
                    fetch_data()
                    let countRange = parseFloat($(".kb_show_range").text());

                    })

                    $("#kb_drangeInput1").on('input', function(){   

                    $(".kb_show_range1").text($(this).val());
                    fetch_data()
                    let countRange = parseFloat($(".kb_show_range1").text());

                    })

                    $("#start_date").on("change",function (){ 
                      fetch_data()
                    });

                    function fetch_data()
                    {
                        var search_time=$(".kb_show_range").text();
                        var search_price=$(".kb_show_range1").text();
                        var start_date=$("#start_date").val();
                        var product_id=$("#product_id").val();
                        $("#loader").show();
                        $.ajax({
                            url:'{{ route("search_request_service") }}',
                        headers: {
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            },
                        type: 'post',
                        //dataType: "json",
                        data: {
                            search_time:search_time,
                            search_price:search_price,
                            start_date:start_date,
                            product_id:product_id,
                        },
                        success: function( data ) {
                            $("#loader").hide();
                            $("#staff_count").html(data);
                            $("#num_staff").val(data);
                            
                        }
                        });
                    }
    </script>
@endsection
