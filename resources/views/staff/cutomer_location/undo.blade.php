



    <tr class="staff-main" data-staff="{{$staff->id}}" >

        <th>  {{$staff->name}} </th>
        
        <th>
          <select name="country" id="country_staff{{ $staff->id }}" class="form-control {{ optional($staff->customerlocationstaff)->country_id }}" data-staff="{{ $staff->id }}" onchange="FilterState(this)">

            <option value="" id="country{{ $staff->id }}">Select Country</option>

            @foreach($country as $item)

              <option value="{{ $item->id }}" {{ $item->id == optional($staff->customerlocationstaff)->country_id ? 'selected' : '' }}>{{ $item->name }} </option>

            @endforeach

        </select>

      </th>

      <th  data-staff="{{ $staff->id }}">

        <select name="state" id="staff_state{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control"  onchange="FilterDistrict(this)">

          <option value="" id="state{{ $staff->id }}" >Select State</option>

            @if(!empty(optional($staff->customerlocationstaff)->state_id))

              @foreach($state as $item)

              <option value="{{ $item->id }}" {{ $item->id == optional($staff->customerlocationstaff)->state_id ? 'selected' : '' }}>{{ $item->name }} </option>

              @endforeach

            @endif

        </select>
        
      </th>

      <th>

          <select name="district[]" id="staff_district{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control district_multi_select"  onchange="FilterTaluk(this)" multiple="multiple">

            <option value="selected" id="district{{ $staff->id }}" >Select All District</option>

            <option value="unselected" id="" >UnSelect All District</option>


            @if(!empty(optional($staff->customerlocationstaff)->district_id))

                @foreach($district->where('state_id',optional($staff->customerlocationstaff)->state_id) as $item)

                    @php
                        $districtIds = json_decode(optional($staff->customerlocationstaff)->district_id, true);
                    @endphp

                  <option value="{{ $item->id }}"  {{ is_array($districtIds) && in_array($item->id, $districtIds) ? 'selected' : '' }} >{{ $item->name }} </option>

                @endforeach
            @endif

          </select>

      </th>

      <th> 

        <select name="taluk[]" id="staff_taluk{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control taluk_multi_select"  onchange="FilterCustomer(this)" multiple="multiple">

          <option value="selected" id="taluk{{ $staff->id }}" >Select Taluk</option>

          <option value="unselected" id="" >UnSelect All Taluk</option>

           @if(!empty(optional($staff->customerlocationstaff)->taluk_id))

           @php
                $districtids = json_decode(optional($staff->customerlocationstaff)->district_id, true);
            @endphp

              @foreach($taluk->whereIn('district_id',$districtids) as $item)

                  @php
                      $talukids = json_decode(optional($staff->customerlocationstaff)->taluk_id, true);
                  @endphp

                <option value="{{ $item->id }}"  {{ is_array($talukids) && in_array($item->id, $talukids) ? 'selected' : '' }} >{{ $item->name }} </option>

              @endforeach

            @endif

        </select>

      </th>

      <th>

        <select name="customer[]" id="staff_customer{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control customer_multi_select"  multiple="multiple" onchange="BrandFilter(this)">

          <option value="" id="customer{{ $staff->id }}" >Select Customer</option>

            @if(!empty(optional($staff->customerlocationstaff)->customer_id))

              @php
                  $talukids = json_decode(optional($staff->customerlocationstaff)->taluk_id, true);
              @endphp

              @foreach($customer->whereIn('taluk_id',$talukids)  as $item)

                  @php
                      $cutomers = json_decode(optional($staff->customerlocationstaff)->customer_id, true);
                  @endphp

                <option value="{{ $item->id }}"  {{ is_array($cutomers) && in_array($item->id, $cutomers) ? 'selected' : '' }} >{{ $item->business_name }} </option>

              @endforeach

            @endif

        </select>

      </th>

      <th>
          <select name="brand[]" id="staff_brand{{ $staff->id }}"  data-staff="{{ $staff->id }}" class="form-control brand_multi_select" onchange="ShowSave(this)" multiple="multiple">

            <option value="" id="brand{{ $staff->id }}">Select Brand</option>

              @if(!empty(optional($staff->customerlocationstaff)->brand_id))

                @foreach($brands as $item)

                  @php
                      $brandids = json_decode(optional($staff->customerlocationstaff)->brand_id, true);
                  @endphp

                  <option value="{{ $item->id }}"  {{ is_array($brandids) && in_array($item->id, $brandids) ? 'selected' : '' }} > {{ $item->name }} </option>

                @endforeach

              @endif

          </select>

      </th>

      <th>
        
        <button style="display:none;" type="button" class="savebtn{{ $staff->id }}"  class="btn btn-success" data-staff="{{ $staff->id }}" onclick="SubmitLocation(this)">Save</button>

        <button style="display:none;" type="button" class="savebtn{{ $staff->id }}"  class="btn btn-danger" data-staff="{{ $staff->id }}" onclick="CancelAjaxRow(this)">Cancel</button>

      </th>


    </tr> 
