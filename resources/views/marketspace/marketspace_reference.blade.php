@extends('layouts.appmasterspace')
<?php
$title       = 'Skills';
$description = 'Skills';
$keywords    = 'Skills';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white custom-nav">
            <!-- <i class="fa fa-bars mobile-menu"></i> -->
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
            <div class="collapse navbar-collapse" id="user-img">
                    <ul class="ml-auto right-ul ">
                     
                      
                          @if(session('MARKETSPACE_ID')==0)
                          <li class="nav-item">
                              <a class="nav-link" href="{{ route('marketspace/login') }}">{{ __('login') }}</a>
                          </li>
                           @endif
                          @if(session('MARKETSPACE_ID')>0)
                        
                         @php
                         $marketspace =App\Marketspace::where('id', '=', session('MARKETSPACE_ID'))->first();
                         @endphp
                           
                          {{-- <li class="nav-item">
                              <a class="nav-link creat-service" href="{{ route('marketspace/iblist') }}">
                                  Create a Service
                              </a>
                          </li> --}}
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle user-prfile" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                <img src="{{ asset('images/profile-img.jpg') }}"><span class="droparrow">
                                <img src="{{ asset('images/drop-arrow1.svg') }}" alt=""> 
                            </span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                   
                                <a class="dropdown-item" href="#">
                                    View Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                   Email Notification
                                    </a>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    Dashboard
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/editprofile') }}">
                                    Edit Profile
                                    </a>
                                    @if($marketspace->user_type=="Work")
                                    <a class="dropdown-item" href="{{ route('marketspace/kyc') }}">
                                    KYC Verification
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/availabledate') }}">
                                    Available Date
                                    </a>
                                    <a class="dropdown-item" href="{{ route('marketspace/location') }}">
                                    Available Location
                                    </a>
                                    @endif
                                   
                                    <a class="dropdown-item" href="{{ route('marketspace/allservicerequest') }}">
                                    Service Request
                                    </a>
                                    @if($marketspace->user_type=="Hire")
                                    <a class="dropdown-item" href="{{ route('marketspace/iblist') }}">
                                     IB
                                    </a>
                                    @endif
                                  

                                

                                    <a class="dropdown-item" href="#">
                                    Account
                                    </a>

                                    <a class="dropdown-item" href="{{ route('marketspace/logout') }}" >
                                        {{ __('Logout') }}
                                    </a>

                                  
                                </div>

                                
                                
                            </li>


                        

                        @endif
                    </ul>
                  </div>
        </nav>
    </header>

    <div class="container-fluid height100">
        <div class="row dashboard-row">
            <aside class="col-md-2" id="sidebar">
                <div class="sidebar-row1">
                    <div class="prfl-col1">
                        <span id="imagesec"  class="prfl-img">
                    @if($marketspace->image!='')
					          <img src="{{ asset('storage/app/public/user/'.$marketspace->image) }}" id="category-img-tag"  />
					          @endif
				          	@if($marketspace->image=='')
					          <img src="{{ asset('images/noimage.jpg') }}" id="category-img-tag">
					          @endif
                </span>
                        <h3>@if($marketspace) @if($marketspace->name!='') {{$marketspace->name}} @endif @if($marketspace->name=='') User @endif @endif</h3>
                    </div>    
                </div>
                <div class="sidebar-row2">
                    <div class="bio-col1">
                        <h3>Bio</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                            Mauris laoreet mi id nulla ullamcorper, eget elementum nisl aliquet. 
                            Orci varius natoque penatibus et magnis dis parturient monte
                        </p>
                    </div>
                    <div class="bio-col1">
                        <h3>Location</h3>
                        <p>Ernakulam-686 442 
                        </p>
                    </div>
                    <div class="bio-col1">
                        <h3>Contact No</h3>
                        <p>0844 8634 343
                        </p>
                    </div>
                </div>
                
            </aside>
            <main class="col-md-10" id="main">
                <section class="content-wrap">
                    <div class="content-col1">
                        <h2>Skills</h2>
                        
                    </div>
                    
                </section>
                <div class="right-side-bar">
                    <ul class="rgt-bar">
                      @if($marketspace->user_type=="Work")
                        <li>
                            <a href="#" class="skills-btn">
                                <span class="rgt-bar-img"><img src="{{ asset('images/skills-icon.png') }}" alt=""></span>
                                <span class="rgt-bar-txt">Skills </span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="work-btn">
                                <span class="rgt-bar-img"><img src="{{ asset('images/work-icon.png') }}" alt=""></span>
                                <span class="rgt-bar-txt">Work <br>Experience</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="education-btn">
                                <span class="rgt-bar-img"><img src="{{ asset('images/education-icon.png') }}" alt=""></span>
                                <span class="rgt-bar-txt">Education <br> Qualification</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="training-btn">
                                <span class="rgt-bar-img"><img src="{{ asset('images/education-icon.png') }}" alt=""></span>
                                <span class="rgt-bar-txt">Training <br>Attended</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="reference-btn">
                                <span class="rgt-bar-img"><img src="{{ asset('images/reference-icon.png') }}" alt=""></span>
                                <span class="rgt-bar-txt">Reference</span>
                            </a>
                        </li>
                        @else  
                        <li>
                          <a href="#" class="skills-btn">
                              <span class="rgt-bar-img"><img src="{{ asset('images/skills-icon.png') }}" alt=""></span>
                              <span class="rgt-bar-txt">IB </span>
                          </a>
                      </li>
                      @endif
                    </ul>
                </div>
        </div>
    </div>

@endsection

<style>
.main-footer{display:none;}
</style>