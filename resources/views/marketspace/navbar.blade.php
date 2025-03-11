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
       @if($marketspace->user_type=="Hire")
    <li class="nav-item">
        <a class="nav-link creat-service" href="{{ route('marketspace/request-service') }}">
            Create a Service
        </a>
    </li>
    @else
    <li class="nav-item">
        <a class="nav-link creat-service" href="{{ route('marketspace/allservicerequest') }}">
            Service Request
        </a>
    </li>
    @endif
      <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle user-prfile" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          
          @if($marketspace->image!='')
          <img src="{{ asset('storage/app/public/user/'.$marketspace->image) }}" width="50" height="50"  />
          @endif
          @if($marketspace->image=='')
          <img src="{{ asset('images/profile-img.jpg') }}" >
          @endif
          <span class="droparrow">
          <img src="{{ asset('images/drop-arrow1.svg') }}" alt=""> 
      </span>
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
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
              <a class="dropdown-item" href="{{ route('marketspace-ib') }}">
               IB
              </a>
              @endif
            

              <a class="dropdown-item" href="{{ route('marketspace/logout') }}" >
                  {{ __('Logout') }}
              </a>

            
          </div>

          
          
      </li>


  

  @endif
</ul>