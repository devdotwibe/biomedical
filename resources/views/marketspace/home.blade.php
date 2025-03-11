@extends('layouts.appmasterspace')
<?php
$title       = 'MarketSpace';
$description = 'MarketSpace';
$keywords    = 'MarketSpace';
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<section>
        <div class="hireBanner">
            <img src="{{ asset('images/hireBanner.png') }}" alt="hirebanner">
            <div class="bannerContent">
                <div class="container">
                    <div class="row">
                        <div class="hireContent"><h1>Find & Hire <br>
                            Expert Freelancers</h1>
                            <p>Work with the best freelance talent on our secure, 
                            flexible and cost-effective platform.</p>
                        </div>
                    </div>
                </div>
            </div>

            
        </div>
    </section>

    <section class="hireFeature">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="{{ asset('images/Woman-using-mobile.png') }}" alt="Woman-using-mobile">
                </div>
                <div class="col-md-8">
                    <div class="feature">
                        <div class="feature-title">
                        <h2>Need Something done?</h2>
                        </div>
                        
                        <div class="feature-lists">
                        <div class="column">
                            <div class="featureList">
                                <h4>Post a job</h4>
                                <p>Its free and easy to post a job. Simply fill in a title, description and budget and competitive bids come within minutes.</p>
                            </div>
                        </div>
                        <div class="column">
                            <div class="featureList">
                                <h4>Pay safely</h4>
                                <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, curs</p>
                            </div>
                        </div>
                        <div class="column">
                            <div class="featureList">
                                <h4>Choose freelancers</h4>
                                <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, curs</p>
                            </div>
                        </div>
                        <div class="column">
                            <div class="featureList">
                                <h4>We’re here to help</h4>
                                <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, curs</p>
                            </div>
                        </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="greatAbout">
        <div class="container">
            <h2>What’s great about it?</h2>
            <div class="qltyContainer">
                <div class="quality">
                    <h4>Browse portfolios</h4>
                    <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, cursus nam</p>
                </div>
                <div class="quality">
                    <h4>Fast bids</h4>
                    <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, cursus nam</p>
                </div>
                <div class="quality">
                    <h4>Quality Work</h4>
                    <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, cursus nam</p>
                </div>
                <div class="quality">
                    <h4>Track Progress</h4>
                    <p>Elementum facilisis vel ut eget posuere quam vehicula ut pulvinar diam amet netus magna facilisis rutrum euismod tellus, cursus nam</p>
                </div>
            </div>
            
        </div>
    </section>

    <section class="freelancList">
        <div class="container">
            <h2>Make it Real with Freelancer</h2>
            <div class="listFreelance">
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail2.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail3.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail4.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail5.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail6.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail7.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
                <div class="freelanceItem">
                    <img src="{{ asset('images/freelancerThumbnail8.png') }}" alt="freelancerThumbnail">
                    <h5>Aenean viverra orci sit placerat</h5>
                </div>
            </div>
        </div>
    </section>

    <section class="category-list">
        <div class="container">
            <h2>Get work done in different categories</h2>

            <div class="Category-container">
                <div class="category-tile"><a href="#">Sollicitudin volutpat</a> </div>
                <div class="category-tile"><a href="#">Sit in sagittis</a></div>
                <div class="category-tile"><a href="#">Neque congue non</a></div>
                <div class="category-tile"><a href="#">Auctor tempus, dis</a></div>
                <div class="category-tile"><a href="#">odio</a></div>
                <div class="category-tile"><a href="#">Sollicitudin volutpat</a></div>
                <div class="category-tile"><a href="#">odio</a></div>
                <div class="category-tile"><a href="#">Neque congue non</a></div>
                <div class="category-tile"><a href="#">Auctor tempus, dis</a></div>
                <div class="category-tile"><a href="#">Sit in sagittis</a></div>
              </div>
        </div>
    </section>

    <section class="twocolbanner">
        <div class="container">
            <div class="row">
                <div class="column-banner">
                    <img src="{{ asset('images/professionalondeman.png') }}" alt="professionalondeman">
                    <div class="bannerad"> 
                    <h5>1000 of professionals on demand</h5>
                    <a href="#">View More</a>
                </div>
                </div>

                <div class="column-banner">
                    <img src="{{ asset('images/getmoredoneforless.png') }}" alt="getmoredoneforless.png">
                    <div class="bannerad">  
                        <h5>Company budget? Get more done for less</h5>
                        <a href="#">Contact Us</a>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

@endsection


@section('scripts')

@endsection