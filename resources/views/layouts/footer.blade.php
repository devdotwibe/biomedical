<section class="footer-wrapper">
      <div class="container">
        <div class="row frt-row">

          <div class="col-md-2 ftr-col1">
          <h3>Our Locations</h3>
            <p>Biomedical Engineering Company ( BEC)<br>
            H.O.: 39/878A2, YMJ West Lane,<br>
            Opp. JLN Stadium, Palarivattom<br>
            Cochin - 682 025<br><br>

            Biomedical Engineering Company ( BEC)<br>
            NO. 79/33,Cheran Towers, 78, Government Arts College Road, Coimbatore - 641 018<br>
            Ph: 9600002371</p>

             <div class="social">
                <h3>Connect with us</h3>
                <ul>
                  <li><a href="#"><img src="{{ asset('images/facebook-icon.svg') }}"></a></li>
                  <li><a href="https://api.whatsapp.com/send?phone=918921065594" target="_blank"><img src="{{ asset('images/whatsapp-icon.svg') }}"></a></li>
                </ul>
               
              </div>
        </div>
          <div class="col-md-2  ftr-col2">
            <h3>Quick Links</h3>
            <ul>
              <li><a href="#">Home</a></li>
              <li><a href="#">About Us</a></li>
              <li><a href="#">Contat Us</a></li>
              <li><a href="#">Services</a></li>
              <li><a href="#">FAQ</a></li>

            </ul>

          </div>
          <div class="col-md-2 ftr-col3">
            <h3>Products</h3>

            <ul>
            <?php 
              if(count($siteData['prod_category']) > 0) 
              { 
                
                        foreach($siteData['prod_category'] as $item) { ?>
                             <li><a data-scroll="scrollTo" href="{{ url('products/'.$item->catslug) }}">{{ $item->catname }}</a></li> 
                        <?php 
                        } 
              }
              ?>
              


            </ul>
           
          </div>
          <div class="col-md-2 ftr-col4">
            <h3>Suppliers</h3>

            <ul>
            <?php 
              if(count($siteData['brand']) > 0) 
              { 
                
                        foreach($siteData['brand'] as $item) { ?>
 <li><a data-scroll="scrollTo" href="{{ url('products/'.$item->brandslug) }}">{{ $item->brandname }}</a></li> 
                         <?php 
                        } 
              }
              ?>
            
            </ul>
            
          </div>
          
        </div>
        <div class="row">
            <div class="col-md-12">
              <div class="social-icons">
                  <a href="https://api.whatsapp.com/send?phone=918921065594" target="_blank"><img src="{{ asset('images/whatsapp.png') }}"></a>
              </div>
            </div>
        </div>
      </div>
    </section>
    <section class="cpyrght-wrapper">
      <div class="container">
        <div class="row cpyrght-row">
          <div class="col-md-8 text-left mr-auto">
            <p> © {{date("Y")}} Biomedical Engineering Company | All Rights Reserved</p>
          </div>
          <!-- <div class="col-md-4 text-right">
            <p>Site by<a href="http://dotwibe.com/"><img src="{{ asset('images/dotwibe-logo.png') }}" alt=""></a></p>
          </div> -->
        </div>
      </div>
    </section>
   
   