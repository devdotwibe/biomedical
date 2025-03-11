
<!DOCTYPE html>
<!-- saved from url=(0041)https://biomedicalengineeringcompany.com/ -->
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
      rel="shortcut icon"
      type="image/png"
      href="https://biomedicalengineeringcompany.com/images/favicon.png"
    />

    <link href="fonts/font_staging.css" rel="stylesheet" />
    <link href="css/font-awesome.min_staging.css" rel="stylesheet" />
    <link href="css/bootstrap.min_staging.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="css/cssmenu_staging.css" />
    <link href="css/slick.min_staging.css" rel="stylesheet" />
    <link href="css/cssmenu.css" rel="stylesheet" />
    <link href="css/style.css" rel="stylesheet" />
    <link href="css/slick_staging.css" rel="stylesheet" />
    <link href="css/slick-theme_staging.css" rel="stylesheet" />

    <link href="css/style_staging.css" rel="stylesheet" />
    <link href="css/responsive_staging.css" rel="stylesheet" />
    <link href="css/productlisting_staging.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="css/lightbox.min_staging.css" />

    <meta
      name="csrf-token"
      content="igb9tQSHMiSnkbCLN5Ui6qHCmtZhJRQXgiFlYrVJ"
    />
    <meta name="description" content="Bezone" />
    <meta name="keywords" content="Bezone" />

    <title>Beczone | About</title>
  </head>

  <body>
    <header class="main-header" id="myHeader">
      <div class="headtop-wrp">
        <div class="container">
          <div class="row">
            <div class="col-md-3 headtop-lft">
              <div class="ddle-rating">
                <a href="https://biomedicalengineeringcompany.com/"
                  ><img src="images/google-rating.png"
                /></a>
              </div>
              <ul class="social-ul">
                <li>
                  <a href="https://biomedicalengineeringcompany.com/"
                    ><i class="fa fa-facebook" aria-hidden="true"></i
                  ></a>
                </li>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/"
                    ><i class="fa fa-instagram" aria-hidden="true"></i
                  ></a>
                </li>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/"
                    ><i class="fa fa-whatsapp" aria-hidden="true"></i
                  ></a>
                </li>
              </ul>
            </div>
            <div class="col-md-9 headtop-rgt">
              <p>
                <span>BEC Helthcare (I) Pvt. Ltd.</span>
                <span> H.O.: 39/878A2, YMJ West Lane,</span>
                <span>Opp. JLN Stadium, Palarivattom</span>
              </p>
              <p>Speak To Us Today 000 0000 0000</p>
            </div>
          </div>
        </div>
      </div>
      <div class="navigation-wrap bg-light start-header start-style">
        <div class="container">
          <div class="row">
            <div class="col-12">
              <nav class="navbar navbar-expand-md navbar-light">
                <a
                  class="navbar-brand"
                  href="https://biomedicalengineeringcompany.com/"
                  ><img src="images/bec-logo.png"
                /></a>
                <button
                  class="navbar-toggler"
                  type="button"
                  data-toggle="collapse"
                  data-target="#navbarSupportedContent"
                  aria-controls="navbarSupportedContent"
                  aria-expanded="false"
                  aria-label="Toggle navigation"
                >
                  <span class="navbar-toggler-icon"></span>
                </button>

                <div
                  class="collapse navbar-collapse"
                  id="navbarSupportedContent"
                >
                  <ul class="navbar-nav ml-auto py-4 py-md-0">
                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                      <a
                        class="nav-link"
                        href="https://biomedicalengineeringcompany.com/"
                        >Home</a
                      >
                    </li>
                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                      <a
                        class="nav-link"
                        href="https://biomedicalengineeringcompany.com/about"
                        >About</a
                      >
                    </li>
                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                      <a
                        class="nav-link"
                        href="https://biomedicalengineeringcompany.com/#"
                        >Services</a
                      >
                    </li>
                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                      <a
                        class="nav-link"
                        href="https://biomedicalengineeringcompany.com/allproducts"
                        >Products</a
                      >
                    </li>

                    <li class="nav-item pl-4 pl-md-0 ml-0 ml-md-4">
                      <a
                        class="nav-link"
                        href="https://biomedicalengineeringcompany.com/contact-us"
                        >Contact</a
                      >
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

    <section class="hero-banner bg-light  content-wraper"> <!--py-3-->
      <div class=" hero-container" >     <!--removed container-->
        <div class=" align-items-center"> <!--removed row-->
          <div class="banner-img ">
            <div class="inr-bnr-head ">
            <div class="container ">
              <div class="row inr-bnr-row">
              <div class="col-md-12 ">
               <h1>About Us</h1>
               </div>
              </div>
            </div>
            </div>
               <div class="inner-bnr-img ">
                  <img src="images/about_banner.jpg" class="" style="width: 100%;" alt="" />
                </div>
          </div>
          <div class="banner-txt  ">
            <div class="container">
                <div class="row inr-bnr-row">
                <div class="col-md-12 ">
            <!-- <h1 class="mt-3">Web Designing & Development</h1> -->
            <p class="lead text-secondary ">
              <span class="orange-txt">Biomedical Engineering Company</span> is an Indian enterprise focused on
              the commercialization of high quality medical products and
              services to private and government hospitals.
            </p>
            </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Management-Team Section -->
    <section class="management-team bg-light content-wraper">
      <div class="container">
        <h2>Management Team</h2>
        <div class="team align-items-center">
          <div class="member">
            <div class="member-img">
              <img src="images/team/ajith.jpg" alt="member_image" />
            </div>
            <div class="member-txt">
            <span>Cheif Excecutive Officer</span>
            <h3>Mr. Ajith George Mathew</h3>
            </div>
          </div>


          <div class="member">
            <div class="member-img">
            <img src="images/team/renjith.jpg" alt="member_image" />
            </div>
            <div class="member-txt">
            <span>Director</span>
            <h3>Mr. Renjith Joseph Mathew</h3>
          
            </div>
          </div>

         
        </div>
      </div>
    </section>

    <!-- About us section -->
    <section class="about-us bg-light py-5 about-wrapper">
      <div class="container">
        <div class="row ">
          <div class="col-lg-4 about-col1">
          <img src="images/about-img1.png" alt="" />
          
          </div>
          <div class="col-lg-8 about-col2">
          <h2>About Us</h2>
            <p class="lead "> <!--my-5-->
              Our organization is focused on satisfying the needs of our
              customers, helping them to improve the healthy quality of their
              patients. Our personal motivation consists in offering the best
              solutions to our customers. Aa our slogan well reflects, what
              gives meaning to Biomedical Engineering Company is our "We value
              life".
            </p>
            <p class="lead "> <!--my-5-->
              Biomedical Engineering Company owes its history to its founder and
              owner. Mr. Ajith George Mathew who has been linked to the health
              care sector for more than 25 years.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Timeline -->
    <section class="timeline-section content-wraper" id="timeline">
      <div class="container">
        <div class="row timeline-row">
        <div class="col-md-12 timeline-col">
          <ul class="timeline first-ul">
              <li>
                  <div class="timeline-panel ">
                      <div class="card  ">
                        <div class="card-body subject">
                          <h5 class="card-title">Proximity</h5><hr>
                          <p class="card-text">Direct relationship with the healthcare institutions. This proximity allows us to offer them solutions and recognize important market niches. In the same way Biomedical Engineering Company is a good partner for manufacturers, developing complete product ranges, anticipating customer needs and offering true business opportunities and great added value.</p>
                        </div>
                      </div>
                  </div>
              </li>
              <li class="timeline-inverted">
                  <div class="timeline-panel">
                      <div class="card  ">
                        <div class="card-body subject">
                          <h5 class="card-title">Social and Medical Credibility</h5><hr>
                          <p class="card-text">As a distributor, we work hand in hand with the manufacturer to reinforce its brand image, to improve its marketing actions and increase sales to benifit of both companies.</p>
                        </div>
                      </div>
                  </div>
              </li>
              <li>
                  <div class="timeline-panel">
                      <div class="card  ">
                        <div class="card-body subject">
                          <h5 class="card-title">Personalized Service</h5><hr>
                          <p class="card-text">Our service team treats the needs of our clients in a ersonalized way. In addition, out Technical Assistance Service ensures the correct maintenance of the equipment and guarantees the perfect use of the equipment aquired at all times.</p>
                        </div>
                      </div>
                  </div>
              </li>
          </ul>
            
             </div>
            </section> 

            <div class="timeline-bg-wrap">
              <div class="container">
               <div class="row timeline-row">
                <div class="col-md-12 timeline-col">
                 <div class="timeline-bg">
                  <img src="images/timeline-section.svg" alt="" />
                 </div>
                </div>
             </div>
            </div>
            </div>

            <section class="timeline-section content-wraper bottom-timeline"  >
            <div class="container">
            <div class="row timeline-row">
              <div class="col-md-12 timeline-col">
            <ul class="timeline second-ul">
             
                <li class="timeline-inverted">
                  <div class="timeline-panel">
                        <div class="card-body subject">
                          <h5 class="card-title">Warranty</h5><hr>
                          <p class="card-text">We are synonymous with prestige and trust. We select the best brands and products of each speciality. Neither our suppliers nor our brands compete with each other.</p>
                        </div>
                      </div>
                </li>
                
                <li class="timeline-inverted">
                    <div class="timeline-panel">
                        <div class="card-body subject">
                            <h5 class="card-title">Exclusiveness</h5><hr>
                            <p class="card-text">We work with exclusive brands such as Wipro GE Healthcare [USA], Masimo Corporation [USA], Ambu A/S [Denmark], Faith MicroSolutions [India], Hitech Metals and Medical Equipment [India].</p>
                        </div>
                        </div>
                    </div>
                </li>
            </ul>
            </div>
        </div>
      </div>
  </section>

    <!-- Stand By section -->
    <section class="stand-by bg-light  content-wraper">   <!-- py-5 -->
      <div class="container">
        <div class="row">
          <div class="col-md-6 stand-by-left"> <!--stand-by-left-->
            <h3>We stand by</h3>
            <ul>
              <li>Commitment</li>
              <li>Quality</li>
              <li>Training</li>
              <li>Operativity</li>
              <li>Excellence of service</li>
              <li>Respect for the environment</li>
            </ul>
          </div>
          <div class="col-md-6 stand-by-right"> <!--stand-by-right-->
            <p>
              The future of Biomedical Engineering Company consists in offering
              integral solutions of medical equipment for the case of health in
              India with the latest in offering and minimal fuzz. Our guarantee
              is wide and varying: team, commitment, coperate responsibility,
              technological innovation, transparency, business strength and
              experience.
            </p>
          </div>
        </div>
      </div>
    </section>

    <!-- Service Team -->
    <!-- <section class="service-team  content-wraper">
      <div class="container">
        <div class="service-head text-center">
          <h2>Service Team</h2>
        </div>
        <div class="items">
          <div class="card">
              <div class="card-body">
               
                  <div class="row">
                      <div class="col-sm-12 "> <img class="profile-pic" src="images/service/service_team (2).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Jawahar Adi Raj</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/service/service_team (3).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Simoes, Salome</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/service/service_team (1).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Lee-Walsh, Natalie</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                 
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/service/service_team (4).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">A N Sadanandan</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                 
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/service/service_team (2).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Jawahar Adi Raj</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </section> -->

<!--     
    <section class="service-team  technical-team content-wraper">
      <div class="container">
        <div class="service-head text-center">
          <h2>Technical Team</h2>
        </div>
        <div class="items">
          <div class="card">
              <div class="card-body">
                
                  <div class="row">
                      <div class="col-sm-12 "> <img class="profile-pic" src="images/technical/technical_team (3).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Simoes, Salome</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                 
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/technical/technical_team (4).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Mr. Shobana Kamineni</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                  
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/technical/technical_team (2).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Mr. Vinayak Chatterjee</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                 
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/technical/technical_team (1).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Lee-Walsh, Natalie</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="card">
              <div class="card-body">
                 
                  <div class="row">
                      <div class="col-sm-12"> <img class="profile-pic" src="images/technical/technical_team (3).png"> </div>
                      <div class="col-sm-12">
                          <div class="profile">
                              <h4 class="cust-name">Simoes, Salome</h4>
                              <p class="cust-profession">Designation</p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
        </div>
      </div>
    </section> -->

    <footer class="main-footer">
      <section class="footer-wrapper">
        <div class="container">
          <div class="row frt-row">
            <div class="col-md-3">
              <h3>CONTACT US</h3>
              <p>
                BEC Helthcare (I) Pvt. Ltd. H.O.: 39/878A2, YMJ West Lane, Opp.
                JLN Stadium, Palarivattom Cochin - 682 025<br /><br />

                BEC Helthcare (I) Pvt. Ltd. Door No. 20 Adbul Aziz Street Nagar
                Chennai, Tamil Nadu - 600 017
              </p>
              <a
                class="footer-logo"
                href="https://biomedicalengineeringcompany.com/"
                ><img src="images/logo.png"
              /></a>
            </div>
            <div class="col-md-3">
              <h3>USEFUL LINKS</h3>
              <ul>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/#">HOME</a>
                </li>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/#"
                    >ABOUT US</a
                  >
                </li>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/#"
                    >CONTACT US</a
                  >
                </li>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/#"
                    >SERVICES</a
                  >
                </li>
                <li>
                  <a href="https://biomedicalengineeringcompany.com/#">FAQ</a>
                </li>
              </ul>
            </div>
            <!-- <div class="col-md-3">
              <h3>PRODUCTS</h3>
              <ul>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/patient-monitoring"
                    >Patient Monitoring</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/cssd"
                    >CSSD</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/infection-control"
                    >Infection Control</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/urology"
                    >Urology</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/sports-medicine"
                    >Sports Medicine</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/rheumatology"
                    >Rheumatology</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/radiology"
                    >Radiology</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/pulmonology"
                    >Pulmonology</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/physiotherapy-rehabilitation"
                    >Physiotherapy &amp; Rehabilitation</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/pathology"
                    >Pathology</a
                  >
                </li>
              </ul>
            </div>
            <div class="col-md-3">
              <h3>SUPPLIERS</h3>
              <ul>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/medrena"
                    >MedRena</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/marrsis-disha"
                    >Marrsis Disha</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/faith-microsolutions"
                    >Faith MicroSolutions</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/fisher-paykel"
                    >Fisher &amp; Paykel</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/stryker-usa"
                    >Stryker USA</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/microvention-terumo"
                    >Microvention Terumo</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/faith-biotech-india"
                    >Faith Biotech India</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/comen"
                    >COMeN</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/vyaire-medical-bd-carefusion-usa"
                    >Vyaire Medical (BD CareFusion) USA</a
                  >
                </li>
                <li>
                  <a
                    data-scroll="scrollTo"
                    href="https://biomedicalengineeringcompany.com/products/masimo-corporation"
                    >Masimo Corporation</a
                  >
                </li>
              </ul>
            </div> -->
          </div>
        </div>
      </section>
      <section class="cpyrght-wrapper">
        <div class="container">
          <div class="row cpyrght-row">
            <div class="col-md-8 text-left">
              <p>© 2020 BEC Helthcare (I) Pvt. Ltd. | All Rights Reserved</p>
            </div>
            <div class="col-md-4 text-right">
              <p>
                Site by<a href="http://dotwibe.com/"
                  ><img src="images/dotwibe-logo.png" alt=""
                /></a>
              </p>
            </div>
          </div>
        </div>
      </section>
    </footer>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min_staging.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/popper.min_staging.js"></script>
    <script src="js/bootstrap.min_staging.js"></script>

    <script src="js/slick.min_staging.js"></script>

    <script src="js/lightbox.min_staging.js"></script>
    <script src="js/jquery.easing.min_staging.js"></script>
    <script src="js/jquery.magnific-popup.min_staging.js"></script>
    <script src="js/scripts_staging.js"></script>

    <link rel="stylesheet" href="js/jquery-ui_staging.css" />
    <script src="js/jquery-ui.min_staging.js"></script>
    <script src="js/bootstrap.bundle.min_staging.js"></script>
    <script src="js/scroll_staging.js"></script>
  </body>
</html>
