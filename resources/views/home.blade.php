@extends('layouts.app')
<?php
$title       = 'Home';
$description = ($cms->meta_description != '') ? $cms->meta_description: setting('META_DESCRIPTION');
$keywords    = ($cms->meta_keywords != '') ? $cms->meta_keywords: setting('METAKEYWORDS');
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')

<section class="index-banner">
  <div id="carousel" class="carousel slide" data-ride="carousel">
     <!-- Indicators -->
     <ol class="carousel-indicators">
        <li data-target="#carousel" data-slide-to="0" class="active"></li>
        <li data-target="#carousel" data-slide-to="1"></li>
        <li data-target="#carousel" data-slide-to="2"></li>
      </ol> <!-- End of Indicators -->
      <div class="carousel-inner" role="listbox">

        <div class="carousel-item active" style="background: url(../images/index-banner-bg.png) no-repeat center center;
      background-size: cover !important;">
            <div class="carousel-caption d-none d-md-block ">
              <div class="container">
                <div class="bnr-content text-center">
                 
                      <div class="bnr-srip-outer">
                      <div class="bnr-srip-left">
                        <h2><span class="bold-txt">QUALITY</span> <span class="ornage-color">PARTS</span> <br> 
                        EXPECTED RESULTS</h2>
                      </div>
                      <div class="bnr-srip-right">
                         <div class="bnr-srip-img">
                          <img src="<?php echo asset("public/images/bnr-srip-img.png") ?>" alt="">
                        </div>
                        <div class=" srip-icon-one">
                          <img src="<?php echo asset("public/images/srip-icon-one.svg") ?>" alt="">
                        </div>
                        <div class=" srip-icon-two">
                          <img src="<?php echo asset("public/images/srip-icon-two.svg") ?>" alt="">
                        </div>
                        <div class=" srip-icon-three">
                          <img src="<?php echo asset("public/images/srip-icon-three.svg") ?>" alt="">
                        </div>
                      </div>
                      <p>Biomedical Engineering Company is an Indian enterprise focused on the commercialization of high quality medical products and services</p>
                  <!-- <a href="#" class="view-btn">VIEW ALL</a> -->
                </div>
              </div>
            </div>
        </div>
      </div>
      </div>
      <!-- <div class="bnr-btn-wraper">
          <div class="container">
              <ul>
                  <li class="color1"><a href="#">Have a Question?</a></li>
                   <li class="color2"><a href="#">Offers</a></li>
                   <li class="color3"><a href="#">Meet the Team</a></li>
                   <li class="color4"><a href="#">Contact Us</a></li>
              </ul>
      </div>
    </div> -->
    </section>
    <section class="about-list-wraper">
      <div class="container  ">
        <div class="row about-row">
          <div class="col-md-6 ">
            <div class="about-col ">
              <ul>
                <li>
                  <a href="#"><img src="<?php echo asset("public/images/about-icon-one.svg") ?>" alt="">
                    <h4>Proximity</h4>
                </a>
                </li>
                <li>
                  <a href="#"><img src="<?php echo asset("public/images/about-icon-two.svg") ?>" alt=""> 
                  <h4>Social And Medical Credibility </h4>

                  </a>
                </li>
                <li>
                  <a href="#"><img src="<?php echo asset("public/images/about-icon-three.svg") ?>" alt="">
                  <h4>Personalized Service </h4>
                </a>
                </li>
                <li>
                  <a href="#"><img src="<?php echo asset("public/images/about-icon-four.svg") ?>" alt="">
                  <h4>Warranty </h4>

                  </a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-6 ">
            <div class="about-co2 ">
              <h4> About Us </h4>
              <h2>Biomedical Engineering Company</h2>
              <p> Our company is based on the belief that our customers needs are of the utmost importance. Our entire team is dedicated to meeting those needs. As a result, 
                a high percentage of our business is from repeat customers and referrals.</p>
                <a href="#" class="viewmore-btn">View More </a>
            </div>
       </div>
        </div>
      </div>
    </section>




    <!-- <section class="wel-wrapper">
      <div class="container sml-container text-center">
        <div class="row">
          <div class="col-md-12">
            <h2>About  Biomedical Engineering Company </h2>
            <p>  {!!html_entity_decode(setting('ABOUT'))!!} </p>
              <div class="selectfeild">
                  <div class="selectBox">
    <div class="selectBox__value">I would like to</div>
    <div class="dropdown-menu">
      <a href="#" class="dropdown-item active">I would like to</a>
      @foreach ($categories as $cat)
      <a attr-id="{{ url('products/'.$cat->catslug) }}" class="dropdown-item" >{{$cat->catname}}</a>
      @endforeach


    </div>
  </div>
                  <div class="go-btn"><a >Go</a></div>
              </div>
          </div>
        </div>
          </div>
</section> -->


<section class="sevice-wrapper">
  <div class="container  ">
      <div class="row sevice-slider-row">
          <div class="col-md-12  ">


          <div id="slider-overlay">
	  <div class="slides">
  @foreach ($categories as $cat)
    <div class="slide" >
<a href="{{ url('products/'.$cat->catslug) }}">
        
       <div class="slide-img" >
       @if($cat->catimage!='')
                <img src="<?php echo asset("public/storage/category/$cat->catimage") ?>" alt="{{$cat->catname}}">
                @endif
                @if($cat->catimage=='')
                <img src="{{ asset('images/no-image.jpg') }}" alt="">
                @endif
      </div>
      <div class="servslide-text">	
          <h2>{{$cat->catname}}</h2>
        </div>
</a>
    </div>
    @endforeach
  </div>
  </div>
    </div>
  </div>


  <div class="row sevice-slider-row2">
       <div class="col-md-12  ">
  <div id="slick-caption-list">
	<div class="slides slick-dots-thumb">
  @foreach ($categories as $cat)
    <div class="slide" >
<a href="{{ url('products/'.$cat->catslug) }}">
        
       <div class="slide-img" >
       @if($cat->catimage!='')
                <img src="<?php echo asset("public/storage/category/$cat->catimage") ?>" alt="{{$cat->catname}}">
                @endif
                @if($cat->catimage=='')
                <img src="{{ asset('images/no-image.jpg') }}" alt="">
                @endif
      </div>
      <div class="servslide-text">	
          <h2>{{$cat->catname}}</h2>
        </div>
</a>
    </div>
    @endforeach
  </div>
  </div>


   </div>
  </div>
  </div>
</section>

<section class="testi-wrapper">
<div class="container  ">
      <div class="row testi-row">
          <div class="col-md-12  ">
            <h2>What Our Customers <br>
          Say About Us</h2>

  <div class="slider2">

  
  @foreach ($testimonial as $testi)
    <div class="slide" >
        <div class="testi-text text-left">	
        <span class="authorname">{{$testi->name}}</span>
          <span class="rating-icon"><img src="{{ asset('images/rating-icon.svg') }}"></span>
          <p> {{$testi->content}}</p>

          <!-- <a href="#" class="readmore-btn">READ MORE</a> -->
        </div>
    </div>
@endforeach
   
</div>
</div> 
</div>
  </div>
</section>

<?php /*
<section class="bluebox-wrapper">
<div class="container  ">
      <div class="row bluebox-row">
          <div class="col-md-6 bluebox-col1 ">
          <div class="contact-form">
            <h4>Contact</h4>
          <h2>Let's Talk</h2>
          <form name="frm_contact" id="frm_contact" method="POST" action="{{ route('contactus.requestsave')}}" autocomplete="off">
                @csrf
          <input type="text" class="form-control" placeholder="Full Name *" id="name" name="name">
          {{ $errors->first('name') }}
          <input type="text" class="form-control half-width" placeholder="Email *" id="email" name="email">
          {{ $errors->first('email') }}
          <input type="text" class="form-control half-width" placeholder="Phone No*" id="phone" name="phone">
          {{ $errors->first('phone') }}
          <textarea type="text" class="form-control" placeholder="Message" id="message" name="message"></textarea>
                      {!! app('captcha')->display() !!}



                      @if ($errors->has('g-recaptcha-response'))

                          <span class="help-block">
                              <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                          </span>

                      @endif 
               
          <input type="button" class="submit-btn" value="Submit" id="btn_submit" name="btn_submit">
          
        </form>
          @if(session("success"))
            <span>{{session("success")}} </span>
          @endif
         </div>
          </div> 
          <div class="col-md-6 bluebox-col2 ">
              <div class="map-outer">
              <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7858.372674820567!2d76.28771637758288!3d10.00146180863341!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b080d6c99c95e6d%3A0xe71e667973b7e7fb!2sBiomedical%20Engineering%20Company!5e0!3m2!1sen!2sin!4v1631271018228!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
              </div> 
          </div> 
      </div>
  </div>
</section>  */ ?>






<?php /*
    <section class="map-wrapper">
      <div class="map-left">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7858.372674820567!2d76.28771637758288!3d10.00146180863341!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3b080d6c99c95e6d%3A0xe71e667973b7e7fb!2sBiomedical%20Engineering%20Company!5e0!3m2!1sen!2sin!4v1631271018228!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
      </div>
      <div class="contact-form-wrap">
        <div class="contact-form">
          <h2>Get in touch</h2>
          <form name="frm_contact" id="frm_contact" method="POST" action="{{ route('contactus.requestsave')}}" autocomplete="off">
                @csrf
          <input type="text" class="form-control" placeholder="Name *" id="name" name="name">
          {{ $errors->first('name') }}
          <input type="text" class="form-control" placeholder="Email *" id="email" name="email">
          {{ $errors->first('email') }}
          <input type="text" class="form-control" placeholder="Phone *" id="phone" name="phone">
          {{ $errors->first('phone') }}
          <textarea type="text" class="form-control" placeholder="Enquiry" id="message" name="message"></textarea>
                      {!! app('captcha')->display() !!}



                      @if ($errors->has('g-recaptcha-response'))

                          <span class="help-block">
                              <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                          </span>

                      @endif 
               
          <input type="button" class="submit-btn" value="Submit" id="btn_submit" name="btn_submit">
          
        </form>
          @if(session("success"))
            <span>{{session("success")}} </span>
          @endif
         </div>
      </div>
    </section> 


    <section class="logo-wrapper">
    <div class="container">
  <div class="slider3">

  @foreach ($brand as $brands)

    <div class="slide" >
        <div class="logo-img">
        @if($brands->image_name!='')
          
          <img src="<?php echo asset("public/storage/brand/$brands->image_name") ?>" alt="<?php echo $brands->name ?>">
          @endif
              
        </div>
    </div>
      @endforeach
  
      </div>

  </div>
</section> 


<section class="logo-wrapper">
  <div class="slider3">

  @foreach ($brand as $brands)
  @if($brands->image_name!='')
    <div class="slide" >
     
       <div class="logo-img" >
     
                <img src="<?php echo asset("public/storage/brand/$brands->image_name") ?>" alt="{{$brands->name}}">
             
               
      </div>

    </div>
    @endif
    @endforeach
  </div>
</section>  

*/ ?>


@endsection


@section('scripts')

 {!! NoCaptcha::renderJs() !!}

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>

function search_by_model()
{
$(".search_by_model_sec").show();
$(".search_by_filter_sec").hide();
// $(".search_by_filter").show();
// $(".search_by_model").hide();
$(".search_by_model").addClass("active");
$(".search_by_filter").removeClass("active");
}
function search_by_filter()
{
  $(".search_by_model_sec").hide();
$(".search_by_filter_sec").show();
// $(".search_by_filter").hide();
// $(".search_by_model").show();

$(".search_by_model").removeClass("active");
$(".search_by_filter").addClass("active");

}
$( document ).ready(function() {

 $("#brand_id").change(function(){
  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchbrand_category';
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      brand_id:$("#brand_id").val()
    },
    success: function( data ) {
      //alert( data.length );
      var proObj = JSON.parse(data);
        var  htmls=' ';
        htmls +="<option value=''>Select Care Area</option>";
         for (var i = 0; i < proObj.length; i++) {
          htmls +="<option value='"+proObj[i]["category_id"]+"'>"+proObj[i]["category_name"]+"</option>";
             }
     
        $("#category_id").html(htmls);
    }
    });

});



$("#category_type_id").change(function(){
  $("#sub_category_id").hide();
  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchcattype';
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      category_type_id:$("#category_type_id").val()
    },
    success: function( data ) {
      //alert( data.length );
      var proObj = JSON.parse(data);
      console.log(proObj)
        var  htmls=' ';
        htmls +="<option value=''>Select Care Area</option>";
  
  $.each(proObj, function(k, v) {
    //alert(k+'--'+v)
    htmls +="<option value='"+k+"'>"+v+"</option>";
  });


        /* for (var i = 0; i < proObj.length; i++) {
          htmls +="<option value='"+proObj[i]+"'>"+proObj[i]+"</option>";
             }*/
     
        $("#category_id").html(htmls);
    }
    });

});

/*
$("#category_id").change(function(){
  if($("#category_id").val()=="")
  {
    $("#sub_category_id").hide();
  }
  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchsubcat';
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      categories_id:$("#category_id").val()
    },
    success: function( data ) {
      //alert( data.length );
      var proObj = JSON.parse(data);
        var  htmls=' ';
        htmls +="<option value=''>Select Parts And Accessories</option>";
         for (var i = 0; i < proObj.length; i++) {
          htmls +="<option value='"+proObj[i]["id"]+"'>"+proObj[i]["name"]+"</option>";
             }
     
        $("#sub_category_id").html(htmls);
        $("#sub_category_id").show();
        
    }
    });

});
*/


$("#product_type_id").change(function(){

  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchproduct_type';
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    //dataType: "json",
    data: {
      product_type_id:$("#product_type_id").val()
    },
    success: function( data ) {
      //alert( data.length );
      var proObj = JSON.parse(data);
        var  htmls=' ';
        htmls +="<option value=''>Select Model</option>";
         for (var i = 0; i < proObj.length; i++) {
          htmls +="<option value='"+proObj[i]["id"]+"'>"+proObj[i]["name"]+"</option>";
             }
     
        $("#product_id").html(htmls);
        
        
    }
    });

});



});

$( function() {
 
// Single Select
$( "#search_word" ).autocomplete({
 source: function( request, response ) {
  // Fetch data
  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchproducts';
  var search_word=$("#search_word").val();
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    dataType: "json",
    data: {
      search_word:search_word
    },
    success: function( data ) {
      response( data );
    }
    });
 },
 select: function (event, ui) {
  // Set selection
  $('#search_word').val(ui.item.label); // display the selected text
 
  return false;
 }
});



});

function split( val ) {
   return val.split( /,\s*/ );
}
function extractLast( term ) {
   return split( term ).pop();
}
 
$(document).ready(function () {
$('.go-btn').on('click', function (e) {
  var link=$(".dropdown-menu a.active").attr("attr-id");
//alert(link)
window.location.href =link;
});

});



function validateContact() {
  //$('#frm_contact').submit();
  
    var validator = $("#frm_contact").validate({
            ignore: "",
            errorElement:"label",
            rules: {
               
                name: {
                    required: true,
                    checkTags: true
                },
            
               
                email: {
                   required:true,
                   email   : true
                },
             
              
                phone: {
                    required: true,
                    checkTags: true,
                },
                message: {
                    checkTags: true,
                },
                "g-recaptcha-response": {
                  required: true
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
              
                email: {
                    required:"Email is required!",
                    email   : "Please enter a valid email address!"
                },
                name: {
                    required: "Name is required!",
                    checkTags: "Name can not allow script tag(s)!"
                },
            
                phone: {
                    required: "Phone Number is required!",
                    checkTags: "Phone can not allow script tag(s)!"
                },
                message: {
                    checkTags: "Message can not allow script tag(s)!"
                },
                "g-recaptcha-response": {
                  required: "Captcha is required!",
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


$(document).ready(function () {
  var success = "<?php echo session('success'); ?>";

  if(success != '') {
    
    $('html, body').animate({
        scrollTop: $("#frm_contact").offset().top
    }, 50);
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

      // $.fancybox({
      //       content:msg,
      //       afterLoad:function() {       
      //       },
      //       beforeClose:function() {
      //       }
      // });
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


</script>
@endsection