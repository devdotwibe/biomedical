    <!-- Main content -->
<div class="op-products"></div>

      <div class="row">
        <div class="col-md-9">

          <div class="box">

            @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">



              <form name="dataForm" id="dataForm" method="post" />
              @csrf

              <table class="table table-bordered table-striped data-">
                <thead>
                <tr class="headrole">
                    <th>No.</th>
                    <th>Product</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @if(sizeof($products)>0)
                      @foreach($products as $op)
                      <tr id="">
                        <td><span class="slNo">{{$i++}} </span></td>
                        <td>{{$op->name}}</td>
                      </tr>

                      @endforeach
                    @else
                    <tr>
                      <td colspan="11">No Records found</td>
                    </tr>
                    @endif
                
                
                </tbody>
              </table>
              <br><br><br>

               
              
            </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>

        
      </div>
      <!-- /.row -->




