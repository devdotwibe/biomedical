@extends('staff/layouts.app')

@section('title', 'Manage Service')

@section('content')

<section class="content-header">
    <h1>
      Manage Service
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Service</li>
    </ol>
</section>

<!-- Main content -->

<section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">

            @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <!-- /.box-header -->

            <div class="box-body">

              <form name="dataForm" id="dataForm" method="post" action="" />
                @csrf
                <table id="cmsTable" class="table table-bordered table-striped data-">
                  <thead>
                    <tr class="headrole">
                          <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                          <th>No.</th>
                          <th>In Ref.No</th>
                          <th>Ex Ref.No</th> 
                          <th>Customer Name</th>
                          <th>Contact Details</th>
                          <th>Eqpt Name</th>
                          <th>Eqpt Status</th>
                          <th>Mac.cur Status</th>
                          <th>Ser.Eng</th>
                          <th>Created By</th>
                          <th>Created Date</th>
                          <th>Status</th>
                          <th class="alignCenter">Action</th>
                      </tr>
                  </thead>
                  <tbody>
                     
                  </tbody>   
                </table>
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

@endsection



@section('scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

jQuery(document).ready(function() {
       
       //var service_id = $('#service_id').val();
       //var url = "{{ route('admin.service-create',"+service_id+") }}";
       var url = '{{ route("staff.service-staffIndex") }}';
           //url = url.replace(':id', service_id);
       //alert(url)
       var oTable = $('#cmsTable').DataTable({
           processing:true,
           serverSide:true,
          
           ajax:{
             url:url,
           },

           initComplete: function(settings) {

            var info = this.api().page.info();
            var api = this.api();

            if (info.pages > 1) {

                $(".dataTables_paginate").show();
            } else {
                $(".dataTables_paginate").hide();

            }

            var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
            $(".col-sm-7").append(searchInput);

            if (info.pages > 1) {

                searchInput.on('input', function() {

                    var searchValue = $(this).val().toLowerCase();

                    var pageNum = searchValue - 1;

                    api.page(pageNum).draw('page');
                });
            }


            if (info.recordsTotal == 0) {

                $(".dataTables_info").hide();
            } else {
                $(".dataTables_info").show();
            }
            },

            createdRow: function(row, data, dataIndex) {

            // $(row).find('td').each(function(i, e) {

            //     $(e).attr('data-th', theader[i]);
                
            // });
            },
            drawCallback: function() {

            },
           columns:[
             {
                 "data": 'DT_RowIndex',
                 orderable: false, 
                 searchable: false
             },
          
             
             {
               data:'in_ref_no',
               name:'in_ref_no',
               orderable: true, 
                searchable: true,
             },
           
             {
               data:'ex_ref_no',
               name:'ex_ref_no',
               orderable: true, 
                searchable: true,
             },
             {
              "class" : "mobile_view", 
               data:'customer',
               name:'customer',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'contact_person',
               name:'contact_person',
               orderable: true, 
                 searchable: true,
             },
             {
              "class" : "mobile_view", 
               data:'equipment_name',
               name:'equipment_name',
               orderable: true, 
                 searchable: true,
             },
            {
              "class" : "mobile_view", 
               data:'equipment_status',
               name:'equipment_status',
               orderable: true, 
                 searchable: true,
             },

             {
              "class" : "mobile_view", 
               data:'machine_status',
               name:'machine_status',
               orderable: true, 
                 searchable: true,
             },

             {
               data:'engineer',
               name:'engineer',
               orderable: true, 
                 searchable: true,
             },
             {
              "class" : "mobile_view", 
               data:'created_by',
               name:'created_by',
               orderable: true, 
                 searchable: true,
             },
             {
              "class" : "mobile_view", 
               data:'created_at',
               name:'created_at',
               orderable: true, 
                 searchable: true,
             },
             
             {
               data:'status',
               name:'status',
               orderable: true, 
                 searchable: true,
             },

             {
              "class" : "mobile_view",  
               data:'action',
               name:'action',
               orderable: true, 
                 searchable: true,
             }

           ]
            
 
         });
 
         $('#cmsTable').on('click', '.deleteItem', function () {
      
          var id= $(this).attr('id');
        var url = $(this).attr('href');
        $('#btnDeleteItem').attr('data-id', id);
        $('#btnDeleteItem').attr('data-href', url);
        $('#modalDelete').modal();        
        return false;

      });

  });
  
</script>
  @endsection