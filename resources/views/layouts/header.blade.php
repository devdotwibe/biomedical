<?php
if(\Route::current()->getName() != 'search.index') {
   \Session::put('search_word', '');
}
/*
@if (\Request::is('search'))  
  Companies menu
@endif*/
use Illuminate\Support\Facades\Route;
echo $currentPath= Route::getFacadeRoot()->current()->uri();
?>
<header id="myHeader">
      <div class="headtop-wrp">
        <div class="container">
            <div class="row">
              <div class="col-md-3 headtop-lft">
                <div class="ddle-rating">
                  <a href=""><img src="{{ asset('images/google-rating.png') }}"></a>
                </div>
                <ul class="social-ul">
                  <li><a href=""><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                  <li><a href=""><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                  <li><a href=""><i class="fa fa-whatsapp" aria-hidden="true"></i></a></li>
                </ul>
              </div>
              <div class="col-md-9 headtop-rgt">
                <p><span>Biomedical Engineering Company</span> <span>	H.O.: 39/878A2, YMJ West Lane,</span>	<span>Opp. JLN Stadium, Palarivattom</span></p>
                <p>Speak To Us Today <?php echo setting('PHONE') ?></p>
                <!-- <div class="search-container">
                      <form action="/search" method="get">
                        <input class="search expandright" id="searchright" type="search" name="search_word" placeholder="Search">
                        <label class="button searchbutton" for="searchright"><span class="mglass"><i class="fa fa-search" aria-hidden="true"></i></span></label>
                      </form>
                    </div> -->
              </div>

              
           </div>
          </div>
      </div>
        <div class="navigation-wrap bg-light start-header start-style">
          <div class="container">
            <div class="row">
              <div class="col-12">
                <nav class="navbar navbar-expand-md navbar-light">          
                  <a class="navbar-brand" href="{{url('/')}}" ><img src="{{ asset('images/bec-logo.png') }}"></a>              
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                  
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto py-4 py-md-0">
                     <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ request()->is('home') ? 'active' : ''}}">
                        <a class="nav-link" href="{{url('/')}}">Homessss</a>
                      </li>
                      <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ request()->is('about') ? 'active' : ''}}">
                        <a class="nav-link" href="{{url('/about')}}">About</a>
                      </li>
                      <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 ">
                        <a class="nav-link" href="#">Services</a>
                      </li>
                      <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ request()->is('allproducts') ? 'active' : ''}}">
                        <a class="nav-link" href="{{url('/allproducts')}}">Products</a>
                      </li>
                      <!-- <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ request()->is('marketspace/search') ? 'active' : ''}}">
                        <a class="nav-link" href="{{url('marketspace/search')}}">Find Service Engineer</a>
                      </li> -->
                      
                      <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 {{ request()->is('contact-us') ? 'active' : ''}}">
                        <a class="nav-link" href="{{url('/contact-us')}}">Contact</a>
                      </li>
                     

                            

                       
                    </ul>

                    <ul class="navbar-nav-right">
                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4 search-li">
                      <div class="search-container">
                      <form action="/search" method="get">
                        <input class="search expandright" id="searchright" type="search" name="search_word" placeholder="Search">
                        <label class="button searchbutton" for="searchright"><span class="mglass"><i class="fa fa-search" aria-hidden="true"></i></span></label>
                      </form>
                    </div>
                      </li>

                          @guest
                          <li class="nav-item login-li">
                              <a class="nav-link" href="">{{ __('login') }}</a>
                          </li>
                           
                          @else
                        
                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                                <a class="nav-link" href="{{ route('quote') }}">Quote</a>
                            </li>

                            <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                              @php 
                                $citem    = App\Cart::where([['user_id',Auth::user()->id],['status','add']])->count();
                              @endphp
                                <a class="nav-link" href="{{ route('mycart') }}">My Cart({{$citem}})</a>
                            </li>


                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                                
                            </li>
                            @endguest

                    </ul>
                  </div>
                  
                </nav>    
              </div>
            </div>
          </div>

      </div>
    </header>


