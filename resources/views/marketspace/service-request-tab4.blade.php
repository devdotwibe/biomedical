<div id="tab_4" class="services-tab__tab-item">
  @if(count($data_completed)>0)
  @foreach($data_completed as $val)
    
  <div class="hire-card">
          
    <div class="hire-details">
      <div class="hire-content">

        <div class="firstsec-ser">
        <div class="hire-head">
        <div class="h-idtitle">
          <div class="jobid">
          <img src="{{ asset('images/job-icon.svg') }}" alt="job-icon"> 
           Job ID: {{$val->id}}
          </div>
         </div>
        <div class="hire-date" >
           {{ \Carbon\Carbon::parse($val->created_at)->format('M D')}} @ {{ \Carbon\Carbon::parse($val->created_at)->format('H:i A')}}
        </div>
    </div>
          <div class="job-title leftsecdiv" id="hiretitle{{$val->id}}">
            <h5>Service Title</h5>
            {{$val->title}}
          </div>
          <div class="job-datadesc leftsecdiv" id="hiredesc{{$val->id}}">
            <h5>Description</h5>
            <p >{{$val->description}}</p>
          </div>
        </div>

        <div class="secondsec-ser">
          <div class="job-data" id="hiredate{{$val->id}}">
            <h5> Service Date</h5>
            {{$val->service_date}}
          </div>
          <div class="job-data" id="hiretime{{$val->id}}">
            <h5> Service Time</h5>
            {{$val->start_time}}
          </div>
          <div class="job-data" id="hiretype{{$val->id}}">
            <h5> Service Machine</h5>
            @php echo App\Product::get_product_details($val->product_id);@endphp
          </div>
       
          <div class="job-data" id="hiretype{{$val->id}}">
            <h5>Accept Date </h5>
           {{ $val->service_approve_date }}
          </div>


       

          <div class="action">
         
         
          
        </div>


        </div>

      </div>


     

    </div>
</div>
    @endforeach
    @endif
    @if(count($data_completed)==0)
      <p class="no_result">No Result</p>
    @endif
  </div>