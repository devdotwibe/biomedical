@extends('layouts.app')

<?php
$title       = 'HeRO'; 
$description = 'Hero'; 
$keywords    = 'Hero'; 

?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')


<section class="landbnr-wraper">
    <div class="container">
        <div class="row landbnr-row">
            <div class="col-md-6 landbnr-col1">
                <h1><span class="hd-bdr">What is </span>HeRO Monitoring</h1>
                <p>HeRO is a non-invasive, continuous risk assessment for infection that has been proven to reduce all cause VLBW mortality by over 22% and sepsis mortality by 40%. 
                    The signs and symptoms of Hospital Acquired Infections are subtle and non-specific Prevention and early detection are key to improving outcomes. Without any additional leads, 
                    HeRO analyzes the data from your existing monitor and reports a HeRO Score, which ranges from 0-7, 
                    reflecting the relative probability of that infant to have a diagnosis of infection in the next 24 hours.</p>
                <a class="get-btn" href="#headingDiv">Get In Touch <span><img src="images/right-arrow-whie.svg" alt="" ></span></a>
            </div>
            <div class="col-md-6 landbnr-col2">
                <img src="images/Landing Image.png" alt="" >
            </div>
        </div>
        <div class="row pulse-row">
              <div class="col-md-6 pulse-col1 mr-auto">
                <div class="puls-animation">
                  <img src="images/pulse-animation.gif" alt="" >
                </div>
              </div>
            </div>
    </div>
</section>

<section class="intro-wraper">
    <div class="container big-container">
        <div class="row intro-row">
            <div class="col-md-6 intro-col1 m-auto text-center">
                <h1>HeRO Whiteboard Introduction</h1>
                <p>Welcome to the HeRO Whiteboard. In this series, we will present the product and some of the literature about its efficacy and usage.</p>
                <div class="intro-video">
                   
                        <iframe class="embed-responsive-item"id="ytplayer" type="text/html" width="640" height="360" src="https://www.youtube.com/embed/sMGH7-9TGIw?&autoplay=1&loop=1&rel=0&showinfo=0&color=white&iv_load_policy=3&playlist=sMGH7-9TGIw"
      frameborder="0" allowfullscreen></iframe>  
                    </div>
            </div>
        </div>
        <div class="row youtube-row">
            <div class="col-md-4 ">
               
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=gAXMuHzhEyY" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/gAXMuHzhEyY/maxresdefault.webp"  class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 1 - <span>Overview</span></h2>
            </div>
            <div class="col-md-4 ">
           
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=NiYKkNWYM9Y" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/NiYKkNWYM9Y/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 2 - <span>Cytokines & HRV</span></h2>
            </div>
            <div class="col-md-4 ">
            
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=7eklcNh2o94" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/7eklcNh2o94/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 3 - <span>Predictive Accuracy for Infection</span></h2>
            </div>

            <div class="col-md-4 ">
           
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=pDyMFQaMMhI" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/pDyMFQaMMhI/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 4 - <span>HeRO Randomized Controlled Trial</span></h2>
            </div>
            <div class="col-md-4 ">
            
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=Z7mqb83Hdxs" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/Z7mqb83Hdxs/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 5 - <span>Neurodevelopment</span></h2>
            </div>
            <div class="col-md-4 ">
           
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=XX6P3-3q7U4" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/XX6P3-3q7U4/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                  <h2>HeRO Whiteboard Episode 6 - <span>Testing and Antibiotic Stewardship</span></h2>
            </div>


            <div class="col-md-4 ">
           
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=lER1ZjjKya4" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/lER1ZjjKya4/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 7 - <span>Intubation  Extubation</span></h2>
            </div>
            <div class="col-md-4 ">
           
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=hC39Z7J5NQo" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/hC39Z7J5NQo/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 8 - <span>Product and Installation</span></h2>
            </div>
            <div class="col-md-4 ">
           
                <div class="popup-gallery">
                    <a href="https://www.youtube.com/watch?v=imgfzfKHFMY" class="video" title="This is a video">
                        <img src="https://i.ytimg.com/vi_webp/imgfzfKHFMY/maxresdefault.webp" class="img-responsive">
                        <button type="button" class="btn btn-play">
        <span class="glyphicon glyphicon-play" aria-label="Play"></span>
      </button>
                    </a>
                 </div>
                 <h2>HeRO Whiteboard Episode 9 - <span>The Case for HeRO</span></h2>
            </div>
       </div>
    </div>
</section>



<section class="products-wraper">
    <div class="container ">
        <div class="row head-row">
            <div class="col-md-12 text-center">
                <h2>Our Products</h2>
            </div>
        </div>
        <div class="row intro-row">
            <div class="col-md-6 intro-col1  text-center">
                <div class="intro-img">
                    <img src="images/hero-solo.png" alt="" >
                </div>
                <div class="intro-txt">
                <h2>HeRO Solo</h2>
                <p>A standalone HeRO monitor for every bed.</p>
                <a class="get-btn" href="#headingDiv">View Product</a>
            </div>
        </div>
        <div class="col-md-6 intro-col1  text-center">
                <div class="intro-img">
                    <img src="images/hero-duet.png" alt="" >
                </div>
                <div class="intro-txt">
                <h2>HeRO Duet</h2>
                <p>HeRO is available as a bedside monitor, as a two patient solution with HeRO duet.</p>
                <a class="get-btn" href="#headingDiv">View Product</a>
            </div>
            </div>
       
           
            <div class="col-md-6 intro-col1  text-center">
                <div class="intro-img">
                    <img src="images/hero-es.png" alt="" >
                </div>
                <div class="intro-txt">
                <h2>HeRO ES</h2>
                <p>Uniquely for the Philips MX platform of monitors, MPSC offers HeRO ES, which with the iPC.</p>
                <a class="get-btn" href="#headingDiv">View Product</a>
            </div>
            </div>
            <div class="col-md-6 intro-col1  text-center">
                <div class="intro-img">
                    <div class="slider-demo-outer">
                    <div class="slider-demo">
                    <div class="slide" >
                     <div class="slide-img" >
                         <img src="images/hero-symphony1.png" alt="Anaesthesia">
                    </div>
                  </div>
                  <div class="slide" >
                    <div class="slide-img" >
                        <img src="images/hero-symphony2.png" alt="Anaesthesia">
                   </div>
                 </div>
                 <div class="slide" >
                    <div class="slide-img" >
                        <img src="images/hero-symphony3.png" alt="Anaesthesia">
                   </div>
                 </div>
 
                </div>
                 </div>
                </div>
                <div class="intro-txt">
                <h2>HeRO Symphony</h2>
                <p>HeRO Symphony represents the highest level of capability that HeRO offers Like Extensive storage of data, 
                    Central Monitoring, Export data to EMR/EHS, Remote access to data, Supports Patient transfer, Patient reports.</p>
                <a class="get-btn" href="#headingDiv">View Product</a>
            </div>
        </div>
        </div>
    </div>
</section>

<section class="testi-wraper">
    <div class="container">
        <div class="row testi-row">
            <div class="col-md-8 testi-col1 m-auto text-center">
                <h1>Testimonials</h1>
                <p>See what people are saying </p>
                <div class="testi-video">
                <iframe class="embed-responsive-item"id="ytplayer" type="text/html" width="640" height="360" src="https://www.youtube.com/embed/7pkfrcAzQpM?&autoplay=1&loop=1&rel=0&showinfo=0&color=white&iv_load_policy=3&playlist=7pkfrcAzQpM"
      frameborder="0" allowfullscreen></iframe>  
                
                    </div>
            </div>
        </div>
    </div>
</section>
<section class="land-form-wraper" id="headingDiv">
    <div class="container">
        <div class="row land-form-row">
            <div class="col-md-6 land-form-col1  text-center m-auto" >
                <h2>Enquire Now</h2>
                <p>Our experts are pleased to answer you individual questions. Please make an appointment with us.</p>

                @if(session()->has('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <form action="{{route('hero.submit')}}" method="POST" class="form">
                    @csrf
                    <div class="form-group">
                        <input type="text" class="form-control" name="name" placeholder="Name">
                        <small class="error text-danger">{{ $errors->first('name') }}</small>
                    </div>
                    <div class="form-group">
                        <input type="email" name="email" class="form-control half-size" placeholder="Email Id">
                        <small class="error text-danger">{{ $errors->first('email') }}</small>
                    </div>
                    <div class="form-group">
                        <input type="text" name="phone" class="form-control half-size" placeholder="Phone Number">
                        <small class="error text-danger">{{ $errors->first('phone') }}</small>
                    </div>
                    <div class="form-group">
                        <textarea type="text" name="message" class="form-control" placeholder="Message"></textarea>
                        <small class="error text-danger">{{ $errors->first('message') }}</small>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="submit-btn" value="Submit">
                    </div>
                </form>
            </div>

        </div>
    </div>
</section> 

@endsection

@section('scripts')
<script>
$(document).on('click', 'a[href^="#"]', function (event) {
    event.preventDefault();
    
    $('html, body').animate({
        scrollTop: $($.attr(this, 'href')).offset().top
    }, 1500);
});
$(function(){
    @if ($errors->any()||session()->has('success'))
    $('html, body').animate({
        scrollTop: $('.form').offset().top-100
    }, 1500);
    @endif
})
</script>
@endsection

