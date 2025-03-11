@extends('layouts.app')

<?php
$title       = 'About';
$description = 'About';
$keywords    = 'About'

//$site_key = 6Lebh5gUAAAAAFOdThuFk5x2PEXmx3xk__WHDmpR
//$secret = 6Lebh5gUAAAAACK8c9BMZCZw_F8rAzGeQh-7jys-;
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')


  <section class="inner-cnt-wrapper" style="margin-top:180px">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center" >
                   <h3>Privacy Notice</h3>
<br>
                   <p>Direct relationship with the healthcare institutions. This proximity allows us to offer them solutions and recognize important market niches. In the same way Biomedical Engineering Company is a good partner for manufacturers, developing complete product ranges, anticipating customer needs and offering true business opportunities and great added value.</p>
                </div>
            </div>
            <?php /* ?>
            <div class="row contact-partner">
                 <div class="col-md-4 contact-box  ml-auto">
                    <a href="#"><span class="icon-out"><img src="assets/images/handshake.png" alt=""></span><span class="partnrt-txt">Distribution partner</span></a>
                </div>
                 <div class="col-md-4 contact-box mr-auto ">
                    <a href="#"><span class="icon-out"><img src="assets/images/verified-contact.png" alt=""></span><span class="partnrt-txt">Contact persons</span></a>
                </div>
          </div>
          <?php */ ?>
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

      $.fancybox({
            content:msg,
            afterLoad:function() {       
            },
            beforeClose:function() {
            }
      });
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
                interested: {
                    required: true
                },
                name: {
                    required: true,
                    checkTags: true
                },
                postcode: {
                    required: true,
                    checkTags: true
                },
                country: {
                    required: true,
                    checkTags: true
                },
                email: {
                   required:true,
                   email   : true
                },
                message: {
                    required: true,
                    checkTags: true,
                    maxlength:1000,
                },
                company: {
                    checkTags: true,
                },
                phone: {
                    checkTags: true,
                },
                // "hidden-grecaptcha": {
                //   required: true
                // }
                // security_code: {
                //     required: true,
                //     checkTags: true
                // }
            },
            messages: {
                interested: {
                    required: "Interested in is required!"                 
                },
                email: {
                    required:"Email is required!",
                    email   : "Please enter a valid email address!"
                },
                name: {
                    required: "Name is required!",
                    checkTags: "Name can not allow script tag(s)!"
                },
                postcode: {
                    required: "Postal code is required!",
                    checkTags: "Postal code can not allow script tag(s)!"
                },
                country: {
                    required: "Country is required!",
                    checkTags: "Country can not allow script tag(s)!"
                },
                message: {
                    required: "Message is required!",
                    checkTags: "Message can not allow script tag(s)!",
                    maxlength: "Message should not be more than {0} characters!"
                },

                company: {
                    checkTags: "Company can not allow script tag(s)!"
                },
                phone: {
                    checkTags: "Phone can not allow script tag(s)!"
                },
                // "hidden-grecaptcha": {
                //   required: "Captcha is required!",
                // }
                
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