
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/favicon.png') }}"/>   
   
    <link href="{{ asset('fonts/font.css') }}" rel="stylesheet">
    <link href="{{ asset('fonts/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/cssmenu.css') }}">

    <link href="{{ asset('css/slick.min.css') }}" rel="stylesheet">

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('css/productlisting.css') }}" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/lightbox.min.css') }}">


    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <meta name="description" content="Site Under Construction" />
    <meta name="keywords" content="Site Under Construction" /> 

    <title><?php echo setting('SITE_NAME') ?> | Site Under Construction</title>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MC0FYBWF9V"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-MC0FYBWF9V');
    </script>
  </head>

<body>

<section class="inner-cnt-wrapper">
        <div class="container">
        
            <div class="row">
                <div class="col-md-12 text-center"><br><br><br><br><br><br><br><br>
                <h1>Site Under Construction</h1>
                </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center sub-headname detail-cntent">

                 <img src="{{ asset('images/warning.jpg') }}" />
                  <p>Sorry, the site is under construction..</p>
                  
              </div>
                
            </div>
            
        </div>
</section> 

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>    
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{ asset('js/popper.min.js') }}"></script>   
    <script src="{{ asset('js/bootstrap.min.js') }}"></script> 

    <script src="{{ asset('js/jquery-validate.js') }}"></script>
    <script src="{{ asset('js/jquery-validate-additional-methods.js') }}"></script>

    <script src="{{ asset('js/slick.min.js') }}"></script>


    <script src="{{ asset('js/lightbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/slick.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
