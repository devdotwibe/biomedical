<div class="row">
<div class="col-md-12 task-single-col-left"  >
  <div class="clearfix"></div>

  <table id="chattertable" class="table table-bordered table-striped data-">
    <thead>
      <th>Id</th>
      <th>Products</th>
      <th>Quantity</th>
      <th>Sale Amount</th>
      <th>Amount</th>
      
    </thead>
    <tbody>
       @if(sizeof($order)>0)
        @php $i = 1; @endphp
        @foreach($order as $ord)
        <tr id="trr_{{$ord->id}}">
          <td>{{$i++}}</td>
          <td>{{$ord->product->name}}</td>
          <td>{{$ord->quantity}}</td>
          <td>{{$ord->sale_amount}}</td>
          <td>{{$ord->amount}}</td>
        </tr>
        @endforeach
      @else
      <tr>
        <td>No record found</td>
      </tr>
      @endif
    
    </tbody>
  </table>
</div>

</div>



