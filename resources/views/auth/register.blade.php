    @extends('layouts.app')
    <?php
    $title       = 'Register';
    $description = setting('META_DESCRIPTION'); //($category->meta_description != '') ? $cms->meta_description: setting('META_DESCRIPTION');
    $keywords    = setting('METAKEYWORDS'); //($category->meta_keywords != '') ? $cms->meta_keywords: setting('METAKEYWORDS');
    ?>

    @section('title', $title)
    @section('meta_keywords', $description)
    @section('meta_description', $keywords)
    @section('content')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">

    <section class="register-wrapper">
            <div class="container">
              <div class="row ">
                 
                  <div class="col-md-7" id="step1">
                  <form method="POST" action="" name="registeruser1" id="registeruser1" class="registeruser1">
                                   @csrf
                     <div class="register-right">
                          <div class="registerform">
                              <div class="step-outer">
                                <ul>
                                <li><a href="register1.html" class="active">1</a></li>
                                <li><a href="register2.html">2</a></li>
                                  <li><a href="register3.html">3</a></li>

                                </ul>
                              </div>
                              <h2>Become a Customer</h2>   
                              <h4>Tell us about yourself</h4>

                                <div class="form-group">
                                  <label>Full Name</label>
                                  <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" maxlength="100">
                                     {{ $errors->first('name') }}
                                </div>
                                <div class="form-group">
                                  <label>Email Address</label>
                                  <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}">
                                    {{ $errors->first('email') }}
                                </div>
                                <div class="form-group">
                                  <label>Phone Number</label>
                                  <input type="text" name="phone" id="phone"  class="form-control" value="{{ old('phone') }}">
                                    {{ $errors->first('phone') }}
                                </div>

                               <div class="form-group">
                                  <label>Password</label>
                                  <input type="password" name="password" id="password"  class="form-control" value="{{ old('password') }}">
                                   {{ $errors->first('password') }}
                                </div>
                                <!---<div class="captcha"><img src="assets/images/captcha.jpg"></div>-->
                                <div class="register-btns">
                                <a class="continue-btn" id="continue_form1">CONTINUE</a>
                                <a href="{{ route('login') }}" class="alrdy-acc-btn">ALREADY ACCOUNT, LOG IN HERE</a>
                                </div>

                          </div>
                      </div>
                      </form>
                </div>
                  
                  
                 
                  <div class="col-md-7" id="step2" style="display:none">
                  <form method="POST" action="" name="registeruser2" id="registeruser2" class="registeruser2">
                  @csrf
                    <div class="register-right">
                          <div class="registerform">
                              <div class="step-outer">
                                <ul>
                                <li><a href="register1.html" >1</a></li>
                                <li><a href="register2.html" class="active">2</a></li>
                                <li><a href="register3.html">3</a></li>
                                </ul>
                              </div>
                              <h2>Become a Customer</h2>
                              <h4>Tell us about yourself</h4>

                                <div class="form-group">
                                  <label>Business Name</label>
                                  <input type="text" name="business_name" id="business_name" class="form-control">
                                     {{ $errors->first('business_name') }}
                                </div>
                                <div class="form-group">
                                  <label>Street Address</label>
                                  <textarea name="address1" id="address1" class="form-control mb-3"></textarea>
                                    {{ $errors->first('address1') }}
                                  <textarea  name="address2" id="address2" class="form-control "></textarea>
                                </div>
                                <div class="form-group">
                                  <label>Country</label>
                                  <select name="country_id" id="country_id" class="form-control mb-3">
                                  <option value="">Select Country</option>
                                  <?php foreach($country as $values){?>
                                    <option value="{{$values->id}}">{{$values->name}}</option>
                                  <?php } ?>
                                  </select>
                                    {{ $errors->first('country_id') }}
                                  
                                </div>

                                <div class="threerow">
                                  <div class="form-group ">
                                    <label>City</label>
                                    <input type="text" class="form-control" name="city" id="city">
                                       {{ $errors->first('city') }}
                                  </div>
                                  <div class="form-group">
                                    <label>State</label>
                                    <input type="text" class="form-control" name="state" id="state">
                                      {{ $errors->first('state') }}
                                  </div>
                                  <div class="form-group">
                                    <label>Zip</label>
                                    <input type="text" class="form-control" name="zip" id="zip">
                                      {{ $errors->first('zip')  }}
                                  </div>
                                </div>
                                <div class="register-btns">
                                <a id="continue_form2" class="continue-btn">CONTINUE</a>
                                <a id="back_first" class="alrdy-acc-btn">BACK</a>
                                </div>

                          </div>
                      </div>
                      </form>
                </div>
               

              
                <div class="col-md-7" id="step3" style="display:none">
                <form method="POST" action="{{ route('register') }}" name="registeruser" id="registeruser" class="registeruser">
                                   @csrf
                    <input type="hidden" name="name" id="name_val" >
                    <input type="hidden" name="email" id="email_val" >
                    <input type="hidden" name="phone" id="phone_val" >
                    <input type="hidden" name="password" id="password_val" >
                     <input id="password-confirm" type="hidden" class="form-control" name="password_confirmation" >
                    
                    <input type="hidden" name="business_name" id="business_name_val" >
                    <input type="hidden" name="address1" id="address1_val" >
                    <input type="hidden" name="address2" id="address2_val" >
                    <input type="hidden" name="country_id" id="country_id_val" >
                    <input type="hidden" name="city" id="city_val" >
                    <input type="hidden" name="state" id="state_val" >
                    <input type="hidden" name="zip" id="zip_val" >
                    
                     <div class="register-right">
                          <div class="registerform">
                                <div class="step-outer">
                                <ul>
                                <li><a href="register1.html" >1</a></li>
                                <li><a href="register2.html" >2</a></li>
                                <li><a href="register3.html" class="active">3</a></li>
                                </ul>
                              </div>
                              <h2>Become a Customer</h2>
                              <h4>Confirm your details</h4>

                                <div class="form-group">
                                  <label>Full Name</label>
                                  <p class="name_dis"></p>
                                </div>
                                <div class="form-group">
                                  <label>Email Address</label>
                                  <p class="email_dis"></p>
                                </div>
                                <div class="form-group">
                                  <label>Phone Number</label>
                                  <p class="phone_dis"></p>
                                </div>
                                <div class="form-group">
                                  <label>Business Name</label>
                                  <p class="bes_dis"></p>
                                </div>
                                <div class="form-group">
                                  <label>Street Address</label>
                                  <p class="addr_dis"></p>
                                </div>
                                <div class="form-group">
                                  <label>Country</label>
                                  <p class="country_dis"></p>
                                </div>
                                <div class="threerow">
                                  <div class="form-group ">
                                    <label>City</label>
                                    <p class="city_dis">
                                    </p>
                                  </div>
                                  <div class="form-group">
                                    <label>State</label>
                                    <p class="state_dis">Kerala</p>
                                  </div>
                                  <div class="form-group">
                                    <label>Zip</label>
                                    <p class="zip_dis"></p>
                                  </div>
                                </div>
                                <div class="register-btns">
                                <a id="regformsubmit" class="register-btn2">Register</a>
                                <a id="back_second"  class="alrdy-acc-btn">Back</a>
                                </div>

                          </div>
                      </div>
                      </form>  
                </div>

             

                <div class="col-md-5">
                     <div class="register-left">
                          <div class="register-imgtxt">
                              <div class="register-caption">
                                  <h2>More products, more choice</h2>
                                  <ul>
                                  <li>32 major product categories</li>
                                  <li>24,000 + products</li>
                                  <li>99.8% order accuracy</li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                </div>

              </div>
            </div>
        </section >


    @endsection
    @section('scripts')
    <script>
      //  

    $( document ).ready(function() {

        $('#continue_form1').click(function() {
            
           // var validator = $(".registeruser").validate({
               var form = $(".registeruser1");
      form.validate({
                rules: {
                    name: {
                        required: true,
                    },

                    email: {
                       required:true,
                       email   : true,
                    },


                    phone: {
                        required: true,
                    },
                    password: {
                        required: true,
                         minlength : 5,
                    }

                },
                messages: {
                    name: {
                        required: "Name is required!",
                        checkTags: "Name can not allow script tag(s)!"
                    },
                    email: {
                        required:"Email is required!",
                        email   : "Please enter a valid email address!"
                    },


                    phone: {
                        required: "Phone is required!"
                    },
                      password: {
                        required: "Password is required!",
                          minlength: "Minimum length 5!"
                    }


                }
            }); 

             if(form.valid() === true) {
                  $("#name_val").val($("#name").val());
                  $("#email_val").val($("#email").val());
                  $("#phone_val").val($("#phone").val());
                  $("#password_val").val($("#password").val());
                  $("#password-confirm").val($("#password").val());
                
                 
                 $(".name_dis").html($("#name").val());
                 $(".phone_dis").html($("#phone").val());
                 $(".email_dis").html($("#email").val());
               
                
                $("#step1").hide();
                $("#step2").show();
            } else {

                validator.focusInvalid();
                return false;
            }
        });

   

        $('#continue_form2').click(function() {   
             
           // var validators = $(".registeruser").validate({
              var form = $(".registeruser2");
      form.validate({
                rules: {
                    business_name: {
                        required: true,
                    },

                    address1: {
                       required:true,

                    },
                    country_id: {
                        required: true,
                    },
                   city: {
                        required: true,
                    },
                   state: {
                        required: true,
                    },
                    zip: {
                        required: true,
                    }

                },
                messages: {
                    business_name: {
                        required: "Business name is required!",

                    },
                    address1: {
                        required:"Address is required!",

                    },
                    
                    country_id: {
                        required: "Country is required!",
                    },
                    city: {
                        required: "City is required!",
                    },
                    state: {
                        required: "State is required!",
                    },
                    zip: {
                        required: "Zip is required!",
                    }


                }
            }); 

             if(form.valid() === true) {
                 
                 $("#business_name_val").val($("#business_name").val());
                  $("#address1_val").val($("#address1").val());
                 $("#address2_val").val($("#address2").val());
                  $("#city_val").val($("#city").val());
                 $("#state_val").val($("#state").val());
                 $("#zip_val").val($("#zip").val());
                 
                 $(".bes_dis").html($("#business_name").val());
                 $(".addr_dis").html($("#address1").val()+','+$("#address2").val());
                 $(".city_dis").html($("#city").val());
                 $(".state_dis").html($("#state").val());
                 $(".zip_dis").html($("#zip").val());
                 

                //    alert($( "#country_id option:selected" ).val())
                //   alert($( "#country_id option:selected" ).text())
                //   alert($( "#country_id" ).val())
                 $("#country_id_val").val($( "#country_id" ).val());
                 $(".country_dis").html($( "#country_id option:selected" ).text());
                 

                 
                 
                $("#step2").hide();
                $("#step3").show();
            } else {

                validator.focusInvalid();
                return false;
            }
        });

$('#regformsubmit').click(function() {  
    $("#registeruser").submit();
});
$('#back_first').click(function() {  
    $("#step2").hide();
     $("#step1").show();
});
$('#back_second').click(function() {  
    $("#step2").show();
     $("#step3").hide();
});

    });
    </script>

    @endsection

