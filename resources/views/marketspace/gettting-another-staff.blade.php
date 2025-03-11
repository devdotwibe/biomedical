<form autocomplete="off" role="form" name="frm_request" id="frm_request" method="post"  enctype="multipart/form-data" >
  @csrf
<div class="row" >
  <div class="form-group col-md-6">
    <label for="start_date">Select Service Date</label>
      <input type="text" name="start_date" id="start_date" class="form-control">
  </div>

          <div class="form-group col-md-6">
            <label for="start_date">Select Service Time</label>
                <input type="time" name="start_time" id="start_time" class="form-control">
        </div>
</div>

<div class="row" >
  <div class="form-check col-md-12">
  <label class="control-label">Criteria preference</label>
  <p>Drag and drop criteria based on higest to lowest priority</p>
 </div>

<div class="form-check col-md-12">
<ul id="sortable1" class="connectedSortable">
  <li class="ui-state-default"> Skills </li>
  <li class="ui-state-default">Quick Availability</li>
  <li class="ui-state-default">Lowest Rate</li>
  <li class="ui-state-default">Verified</li>

</ul>
</div>
</div>
</form>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" type="text/css" href="{{ asset('public/assets/multi/multi.min.css') }}" />
<script src="{{ asset('public/assets/multi/multi.min.js') }}"></script>


<style>
  #sortable1, #sortable2 {
    border: 1px solid #eee;
    width: 142px;
    min-height: 20px;
    list-style-type: none;
    margin: 0;
    padding: 5px 0 0 0;
    float: left;
    margin-right: 10px;
  }
  #sortable1 li, #sortable2 li {
    margin: 0 5px 5px 5px;
    padding: 5px;
    font-size: 1.2em;
    width: 120px;
  }
  </style>
    <script>
      $( function() {
    $( "#sortable1" ).sortable({
      connectWith: ".connectedSortable"
    }).disableSelection();
  } );
    //       var select = document.getElementById("preference_select");
    // multi(select, {
    //     enable_search: true
    // });

        // $('#start_date').datepicker({
        //             //dateFormat:'yy-mm-dd',
        //             dateFormat:'yy-mm-dd',
        //             changeYear: true,
        //             yearRange: "1990:2040",
        //             minDate: 0  
        //         });
      
 var eve=<?php echo $json_encode_date;?>;


	var datelist = eve;
	
$('#start_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
			changeMonth: true,
            changeYear: true,
			
			minDate: 0, 
			yearRange: "-0:+10",
		beforeShowDay: function(d) {
           
            var dmy = "";
            dmy += ("00" + d.getDate()).slice(-2) + "-";
            dmy += ("00" + (d.getMonth() + 1)).slice(-2) + "-";
            dmy += d.getFullYear();
            if ($.inArray(dmy, datelist) >= 0) {
                return [true, ""];
            }
            else {
                return [false, ""];
            }
        },
           // minDate: 0  
            
        });


        </script>