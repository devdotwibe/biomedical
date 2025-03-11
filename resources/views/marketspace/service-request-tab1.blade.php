@if(count($data)>0)
    @foreach($data as $val)
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
                  <h5>Criteria Preference </h5>
                 {{ $val->preference }}
                </div>

                <div class="action">
                @if($val->auth_by_user=='Y')

                @if($val->status=='Progress' && $marketspace->id==$val->service_staff)
                <button class="job-accept accept_job" data-id="{{$val->id}}">Approve</button> <button data-modal="1" data-id="{{$val->id}}" class="job-reject reject_job_staff">Reject</button>
                @endif
    
                @if($val->status=='Accept_staff' && $marketspace->id==$val->service_staff)
                
                <span class="accept_req_sec" style="color:green">Accept request send to customer after verify customer will contact soon.</span>
                @endif
    
    
                @endif
    
                @if($val->auth_by_user=='N' && $marketspace->id!=$val->marketspace_id)
                <button class="job-accept accept_job_auth" data-id="{{$val->id}}">Approve</button> <button data-id="{{$val->id}}" class="job-reject reject_job_auth">Reject</button>
                @endif

                @php $service_staffs= App\Marketspace::get_service_accept_staff_marketspace($val->marketspace_id,$val->product_id); @endphp
                @if(count($service_staffs)>0 && $marketspace->user_type=="Hire")
                <a class="viewbid" href="{{ route('viewbid',$val->marketspace_id) }}" target="_blank" data-id="{{$val->id}}">View Bid</a> 
                @endif

                
              </div>


              </div>

            </div>


           

          </div>
      </div>
      @endforeach
      @endif
      @if(count($data)==0)
        <p class="no_result">No Result</p>
      @endif

    </div>
