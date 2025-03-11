@extends('layouts.app')

<?php
$title       = $cms->name;
$description = ($cms->meta_description != '') ? $cms->meta_description: setting('META_DESCRIPTION');
$keywords    = ($cms->meta_keywords != '') ? $cms->meta_keywords: setting('METAKEYWORDS');

//$site_key = 6Lebh5gUAAAAAFOdThuFk5x2PEXmx3xk__WHDmpR
//$secret = 6Lebh5gUAAAAACK8c9BMZCZw_F8rAzGeQh-7jys-;
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')

<section class="contact-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-6 ">
<div class="contact-left">
<div class="contact-head">
                <h3>{{ $cms->title }}</h3>
                <p>Our experts are pleased to answer you individual questions. Please make an appointment with us.</p>                </div><div class="contact-content">


                    <ul class="contact-item">
                        <li>
                            <div class="contct-icons"><img src="images/location-icon.svg" alt="location-icon"></div>
                            <div class="contct-right">
                            <p><?php echo nl2br(setting('ADDRESS')) ?> </p>
                            </div>
                        </li>
                        <li>
                            <div class="contct-icons"><img src="images/call-icon.svg" alt="call-icon"></div>
                            <div class="contct-right">
                            <a href="callto:<?php echo setting('PHONE') ?>"><?php echo setting('PHONE') ?></a>
                            </div>
                            
                        </li>
                     
                        <li>
                            <div class="contct-icons"> <img src="images/mail-icon.svg" alt="mail-icon"> </div>
                            <div class="contct-right">
                            <a href="mailto:<?php echo setting('CONTACT_EMAIL_ID') ?>"><?php echo setting('CONTACT_EMAIL_ID') ?></a>
                            </div>
                        
                        </li>
                    </ul>
                    
                    
                    
                    
                    
                    
                    
                    

                </div>
<div class="contact-map">


    <iframe width="374" height="227" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d15716.811527806782!2d76.2930961!3d10.0000937!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7c56d263dce9bc92!2sBiomedical%20Engineering%20Company!5e0!3m2!1sen!2sin!4v1646832788391!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
   
         </div>       
             </div></div>



<?php /*
             <div class="col-md-6 ">
<div class="contact-right">

                <form name="frm_contact" id="frm_contact" method="POST" action="{{ route('contactus.store')}}" autocomplete="off">
                @csrf
                <div class="col-md-12 contact-form">
                  <h3>Tell us what you are interested in</h3>

          @if(session("success"))
            <span>{{session("success")}} </span>
          @endif
                  <!-- <div class="radio-sec">
                    <label class="radio-box">
                        
                      <input type="radio"  value="Concrete moisture" checked="checked" name="interested" id="interested1">
                      <span class="radio-content"> Concrete moisture </span>
                      <span class="checkmark"></span>
                    </label>
                    <label class="radio-box">
                        
                      <input type="radio" value="Bulk solid moisture" name="interested" id="interested2">
                      <span class="radio-content">Bulk solid moisture </span>
                      <span class="checkmark"></span>
                    </label>
                    <label class="radio-box">
                    
                      <input type="radio" value="Soil moisture" name="interested" id="interested3">
                      <span class="radio-content">Soil moisture</span>
                      <span class="checkmark"></span>
                    </label>
                    <label class="radio-box">
                    
                      <input type="radio" value="TRIME products" name="interested" id="interested4">
                      <span class="radio-content">TRIME products</span>
                      <span class="checkmark"></span>
                    </label>
                    {{ $errors->first('interested') }}
                  </div> -->

                  <div class="form-group half-width">
                      <!-- <label >Name*</label> -->
                      <input type="text" placeholder="Name *" class="input-field" name="name" id="name" value="{{ old('name') }}" maxlength="100" />
                       {{ $errors->first('name') }}
                  </div>
                  <div class="form-group half-width">
                      <!-- <label >Company</label> -->
                      <input type="text" placeholder="Company" class="input-field" name="company" id="company" value="{{ old('company') }}" maxlength="100" />
                      {{ $errors->first('company') }}
                  </div>
                  <div class="form-group half-width">
                      <!-- <label >E-mail*</label> -->
                      <input type="text" placeholder="Email *" class="input-field" name="email" id="email" value="{{ old('email') }}" maxlength="100" >
                       {{ $errors->first('email') }}
                  </div>
                  <div class="form-group half-width">
                      <!-- <label >Phone</label> -->
                      <input type="text" placeholder="Phone *" class="input-field" name="phone" id="phone" value="{{ old('phone') }}" maxlength="50" >
                      {{ $errors->first('phone') }}
                  </div>
                  <div class="form-group half-width">
                      <!-- <label >Postal code*</label> -->
                      <input type="text" placeholder="Postal Code"  class="input-field" name="postcode" id="postcode" value="{{ old('postcode') }}" maxlength="20">
                      {{ $errors->first('postcode') }}
                  </div>
                  <div class="form-group half-width">
                      <!-- <label >Country*</label> -->
                      <input type="text" placeholder="Country" class="input-field" name="country" id="country" value="{{ old('country') }}" maxlength="100">
                      {{ $errors->first('country') }}
                  </div>
                  <div class="form-group full-width">
                      <!-- <label >Message</label> -->
                      <textarea placeholder="Message" class="input-field" type="text" name="message" id="message" maxlength="1000">{{ old('message') }}</textarea>
                      {{ $errors->first('messages') }}
                  </div>
                  <!-- <div class="checkbox-wrap">
                    <label class="check-container">
                         
                      <input type="checkbox" name="call_me" id="call_me" value="Y" />
                      <span class="check-words">Please make an appointment or call me back.</span>
                      <span class="checkmark"></span>
                    </label>
                  </div> -->

                  <div class="form-group full-width captchaOuter {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                      {!! app('captcha')->display() !!}



                      @if ($errors->has('g-recaptcha-response'))

                          <span class="help-block">
                              <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                          </span>

                      @endif 
                  </div>


                    <div class="form-group full-width submitBtnOuter">
                      <input type="button" class="send-btn" value="Send" id="btn_submit" name="btn_submit">
                  </div>

                </div>
                <input type="hidden" name="from" id="from" value="4" />
              </form>
              </div></div> 

              */?>
         </div>
    </div>
</section>

@endsection


@section('scripts')

 {!! NoCaptcha::renderJs() !!}

<script>

//   function recaptchaCallback() {
//       var response = grecaptcha.getResponse();
//       $("#hidden-grecaptcha").val(response);
//   }

  $(document).ready(function () {
  var success = "<?php echo session('success'); ?>";

  if(success != '') {

    var msg = '<div class="signup_now_outer">'+
              '<div class="signup_now_conent"> <br />'+
              '<center>'+success+'</center>'+
              '<br />'+
              '</div>'+
              '</div>';
        
        // <div class="form-row form-popup">
        //         <div class="popok-left">
        //             <input type="button" value="OK" class="c-submit-btn" name="popbtn_close" id="popbtn_close" onClick="closeFancybox();" tabindex="1000">
                 
        //               </div>
        //     </div>'

    //   $.fancybox({
    //         content:msg,
    //         afterLoad:function() {       
    //         },
    //         beforeClose:function() {
    //         }
    //   });
  }

    $.validator.addMethod("checkTags", function(value, element, arg){
        if(value.indexOf('<script>') > -1)
        {
            return false;
        } else {
            return true;
        }
    }, "Can not allow script tag(s)!");

    $('#name').bind('keyup blur',function(){ 
        var node = $(this);
        node.val(node.val().replace(/[^0-9a-zA-Z\-\.\ ]/g,'') ); }
    );
    
    $('#email, #security_code').bind('keyup blur',function(e){ 
        var node = $(this);
        if (e.keyCode == 32) {
            node.val(node.val().replace(/\s/g,''));
        }
    });

    $.validator.addMethod('nameCheck', function(name, element) {
        return this.optional(element) || name.match(/^[a-zA-Z0-9\-\.\ ]+$/i);
        }, 'Allowed alphabets, period, hyphen and numbers only!'
    );


    $(".input-field").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            validateContact();
        }
    }); 


    $('#btn_submit').on('click', function() {
         validateContact();
    });
});

function validateContact() {
  //$('#frm_contact').submit();
  
    var validator = $("#frm_contact").validate({
            ignore: "",
            errorElement:"label",
            rules: {
                // interested: {
                //     required: true
                // },
                name: {
                    required: true,
                    checkTags: true
                },
                postcode: {
                    checkTags: true
                },
                country: {
                    checkTags: true
                },
                email: {
                   required:true,
                   email   : true
                },
               /* message: {
                    required: true,
                    checkTags: true,
                    maxlength:1000,
                },*/
                company: {
                    checkTags: true,
                },
                phone: {
                    required: true,
                    checkTags: true,
                },
                "g-recaptcha-response": {
                  required: true
                }
                // security_code: {
                //     required: true,
                //     checkTags: true
                // }
            },
            messages: {
                // interested: {
                //     required: "Interested in is required!"                 
                // },
                email: {
                    required:"Email is required!",
                    email   : "Please enter a valid email address!"
                },
                name: {
                    required: "Name is required!",
                    checkTags: "Name can not allow script tag(s)!"
                },
                postcode: {
                    checkTags: "Postal code can not allow script tag(s)!"
                },
                country: {
                    checkTags: "Country can not allow script tag(s)!"
                },
               /* message: {
                    required: "Message is required!",
                    checkTags: "Message can not allow script tag(s)!",
                    maxlength: "Message should not be more than {0} characters!"
                },*/

                company: {
                    checkTags: "Company can not allow script tag(s)!"
                },
                phone: {
                    required: "Phone is required!",
                    checkTags: "Phone can not allow script tag(s)!"
                },
                "g-recaptcha-response": {
                  required: "Captcha is required!",
                }
                
                // security_code:{
                //     required: "Security code is required!",
                //     checkTags: "Security code can not allow script tag(s)!"
                // }

            }
        }); 

        if($("#frm_contact").valid()) {
            $('#frm_contact').submit();
        } else {
            validator.focusInvalid();
            return false;
        }
}
</script>

@endsection