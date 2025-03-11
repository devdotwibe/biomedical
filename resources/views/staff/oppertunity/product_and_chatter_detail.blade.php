<?php
function custom_number_format($number) {
    $decimal = (string)($number - floor($number));
    $money = floor($number);
    $length = strlen($money);
    $delimiter = '';
    $money = strrev($money);

    for ($i = 0; $i < $length; $i++) {
        if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
            $delimiter .= ',';
        }
        $delimiter .= $money[$i];
    }

    $result = strrev($delimiter);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);

    if ($decimal != '0') {
        $result = $result . $decimal;
    }

    return $result;
}
?>

<table width="100%" class="prolist">
 <tbody>
<tr>
   <td> 

   @php
           $staff_id = session('STAFF_ID');

   @endphp

<table width="100%" style="border: 1px solid #ccc !important;" class="procontent">
<tbody>
<tr>
<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Product</th>
<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Quantity</th>
<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Unit Price</th>

@if($staff_id == 35 )

<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;" class="last_row">Previous Amount</th>
<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;" class="last_row">Hike(%)</th>
@endif

<th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;" class="last_row">Net Amount</th>
</tr>
@foreach($oppertunity_product as $result_val)
        <tr style="border-bottom: 1px solid #ccc !important;">
        @php $cn  = App\Product::where('id',$result_val->product_id)->first(); @endphp
              

          <td> @if($cn)
               @php echo $cn['name'].','; @endphp
               @endif</td>
          <td>{{$result_val->quantity}}</td>
          <td>{{custom_number_format($result_val->sale_amount)}}</td>

          @if($staff_id == 35 )

          <td>{{$result_val->oldprice}}</td>
          <td>{{$result_val->hike}}</td>

          @endif

          <td  class="last_row">{{custom_number_format($result_val->amount)}}</td>
        </tr>

        
        @endforeach 
        @if($staff_id == 35 )

        <tr>
          <td colspan="6">
              @if($oppertunity->comments)
              <button type="button" class="editCommentBtn" data-id="{{ $oppertunity->id }}">
                <span class="glyphicon glyphicon-pencil"></span>
            </button>
                  <strong>Comment:</strong> 
                  <p id="commentText_{{ $oppertunity->id }}">{{ $oppertunity->comments }}</p>
                 
                
                              @else
                  <label for="comment_{{ $oppertunity->id }}">Add Comment:</label>
                  <textarea name="comment" id="comment_{{ $oppertunity->id }}" class="form-control"></textarea>
                  <button type="button" class="btn btn-primary saveCommentBtn" data-id="{{ $oppertunity->id }}">Save</button>
              @endif
              <span id="savedComment_{{ $oppertunity->id }}"></span>
          </td>
      </tr>
      
      @endif
 

        </tbody>
  </table>

  
  
  @if(sizeof($chatter)>0)
  <br>
<table width="100%" style="border: 1px solid #ccc !important;" class="procontent">
<tbody>
<tr>
      <th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Id</th>
      <th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Contact</th>
      <th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Comment</th>
      <th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;">Date</th>
      <th style="border-bottom: 1px solid #ccc !important;border-top: 1px solid #ccc !important;padding: .5rem .5rem!important;"  class="last_row">Status</th>
      </tr>
      @if(sizeof($chatter)>0)
        @php $i = 1; @endphp
        @foreach($chatter as $chat)
        <tr style="border-bottom: 1px solid #ccc !important;">
          <td>{{$i++}}</td>
          <td>
            @php 
               $chat->contact_person_id   = explode(",",$chat->contact_person_id);
            @endphp
            @foreach($chat->contact_person_id as $ids)
               @php $cn  = App\Contact_person::where('id',$ids)->first(); @endphp
               @if($cn)
               @php echo $cn['name'].','; @endphp
               @endif
            @endforeach 
            
          </td>
          <td>{{$chat->comment}}</td>
          <td>{{date('Y-m-d',strtotime($chat->created_at))}}</td>
      

          <td  class="last_row">
          @if($chat->email_status=="Yes")
            E-mail  <br>   @endif
            @if($chat->call_status=="Yes")
            Call  <br>   @endif
            @if($chat->visit_status=="Yes")
            Visit    @endif
          </td>

        </tr>
        @endforeach
      @else
      <tr>
        <td>No record found</td>
      </tr>
      @endif
    </tbody>
  </table>
  @endif

  @if($oppertunity->deal_stage==6)
    <table  width="100%" style="border: 1px solid #ccc !important;" class="procontent">
     <tbody>
      <tr>
        <th>Brand</th>  
        <td>{{$oppertunity->lost_brand}}</td>
      </tr>
      <tr>
        <th>Equipemnt</th>  
        <td>{{$oppertunity->loast_equipemnt}}</td>
      </tr>  
      <tr>
        <th>Reason</th>  
        <td>{{$oppertunity->reason	}}</td>
      </tr>
     </tbody>
    </table>
  @endif

  @if($oppertunity->deal_stage==7)
    <table  width="100%" style="border: 1px solid #ccc !important;" class="procontent">
     <tbody>
      <tr>
        <th>Reason</th>  
        <td>{{$oppertunity->reason	}}</td>
      </tr>
     </tbody>
    </table>
  @endif

  @if($oppertunity->deal_stage==8)
    <table  width="100%" style="border: 1px solid #ccc !important;" class="procontent"> 
      <thead>
        <tr>
          <th>Order No</th>
          <th>Order Date</th>
          <th>Payment Term</th>
          <th>Delivery Date</th>
          <th>Warrenty Term</th>
          <th>Supply</th>
          <th>Remark</th>
        </tr>
      </thead>
     <tbody>
      @foreach($oppertunity_order as $r)
      <tr>
        <td>{{$r->order_no}}</td>
        <td>{{$r->order_date}}</td>
        <td>{{$r->payment_term}}</td>
        <td>{{$r->delivery_date}}</td>
        <td>{{$r->warrenty_terms}}</td>
        <td>{{$r->supplay	}}</td>
        <td>{{$r->remark}}</td>
      </tr>
      @endforeach
     </tbody>
    </table>
  @endif
  </td>
   </tr>
 </tbody>
</table>






<script>
  function saveComment(opportunityId) {
      const comment = $(`#comment_${opportunityId}`).val();
      $.ajax({
          url: '/staff/save-comment',
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
          data: { product_id: opportunityId, comment: comment },
          success: function (data) {
              if (data.success) {
                  $(`#commentText_${opportunityId}`).text(data.comment);
                  $(`#savedComment_${opportunityId}`).text('');
                  $(`#comment_${opportunityId}`).closest('td').html(
                      `<strong>Comment:</strong> <p id="commentText_${opportunityId}">${data.comment}</p>
                      <button type="button" class="editCommentBtn" data-id="${opportunityId}">
                      <span class="glyphicon glyphicon-pencil"></span>
                      </button>`                       
                  );
                  alert(data.message);
              } else {
                  alert(data.message);
              }
          },
          error: function (err) {
              console.error('Error:', err);
          }
      });
  }
  
  function editComment(opportunityId) {
      const currentComment = $(`#commentText_${opportunityId}`).text();
      $(`#commentText_${opportunityId}`).closest('td').html(
          `<label for="comment_${opportunityId}">Edit Comment:</label>
           <textarea name="comment" id="comment_${opportunityId}" class="form-control">${currentComment}</textarea>
           <button type="button" class="btn btn-primary saveCommentBtn" data-id="${opportunityId}">Save</button>`
      );
  }
  
  $(document).on('click', '.saveCommentBtn', function () {
      saveComment($(this).data('id'));
  });
  
  $(document).on('click', '.editCommentBtn', function () {
      editComment($(this).data('id'));
  });
  </script>
  