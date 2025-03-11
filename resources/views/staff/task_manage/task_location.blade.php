@extends('staff/layouts.app')

@section('title', 'Staff Task Location')

@section('content')

    <section class="content-header">
        <h1> Staff Task Location </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Staff Task Location</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content  manage-staff-wrap">
        <div class="row manage-staff">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">  
                                    <select name="category" class="form-control" id="category">
                                        <option value="">All</option>
                                        <option value="Staff Service Team" @if(request('category','')=="Staff Service Team") selected @endif >Staff Service Team</option>
                                        <option value="Staff Service Support Team" @if(request('category','')=="Staff Service Support Team") selected @endif >Staff Service Support Team</option>
                                        <option value="Staff Sales Team" @if(request('category','')=="Staff Sales Team") selected @endif >Staff Sales Team</option>
                                        <option value="Staff Sales Support Team" @if(request('category','')=="Staff Sales Support Team") selected @endif >Staff Sales Support Team</option>
                                        <option value="Staff Admin Team" @if(request('category','')=="Staff Admin Team") selected @endif >Staff Admin Team</option>
                                        <option value="Outsource Sales Team" @if(request('category','')=="Outsource Sales Team") selected @endif >Outsource Sales Team</option>
                                        <option value="Outsource Service Team" @if(request('category','')=="Outsource Service Team") selected @endif >Outsource Service Team</option>
                                        <option value="Direct Company Staff" @if(request('category','')=="Direct Company Staff") selected @endif >Direct Company Staff</option>
                                    </select> 
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <input type="text" name="date" class="form-control" id="date" value="{{request('date',date('Y-m-d'))}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row"> 
                            <div class="col-xs-12"> 
                                <table class="table">
                                    @foreach ($staff as $item)
                                        @if (in_array($item->staff_category, [
                                                'Staff Service Team',
                                                'Staff Service Support Team',
                                                'Staff Sales Team',
                                                'Staff Sales Support Team',
                                                'Staff Admin Team',
                                                'Outsource Sales Team',
                                                'Outsource Service Team',
                                                'Direct Company Staff',
                                            ]))
                                            <tbody class="staff-filter-list-item staff-task-time" data-cat="{{ $item->staff_category }}" data-staff="{{$item->id}}">
                                                <tr>
                                                    <th colspan="4">{{ ucfirst($item->name) }}</th>
                                                </tr>
                                                <tr>
                                                    <th>Sl.No</th>
                                                    <th>Task</th>
                                                    <th>Working Time</th>
                                                    <th>Travel Time</th>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        The data for the '{{ ucfirst($item->name) }}' may be not loaded
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>Total</th>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody> 
                                        @endif
                                    @endforeach 
                                </table> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row"> 
                            <div class="col-xs-12"> 
                                <div class="list-group">
                                    @foreach ($staff as $item)
                                        @if (in_array($item->staff_category, [
                                                'Staff Service Team',
                                                'Staff Service Support Team',
                                                'Staff Sales Team',
                                                'Staff Sales Support Team',
                                                'Staff Admin Team',
                                                'Outsource Sales Team',
                                                'Outsource Service Team',
                                                'Direct Company Staff',
                                            ]))
                                            <div class="list-group-item staff-filter-list-item" data-cat="{{ $item->staff_category }}" >
                                                <h3 id="location-staff-{{$item->id}}" class="location-staff" data-staff="{{$item->id}}">{{ ucfirst($item->name) }}</h3>
                                                <div id="location-map-{{$item->id}}" class="location-map" data-staff="{{$item->id}}">
                                                    <span> The data for the selected staff may be not loaded.</span>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@section('scripts') 

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script async defer>
function ucfirst(str) {
    if (!str) return str;
    return str.charAt(0).toUpperCase() + str.slice(1);
}
function mintostring(min){
    if(min<60){
        return `${min} Minute`
    } 
    let h = Math.floor(min/60);
    let m = Math.floor(min%60);
    return `${h} Hour ${m} Minute`
}
async function selectfilterstafftime() {
    $.get("{{route('staff.manage-staff-task-time_staff')}}",{
        category:$('#category').val(),
        date:$('#date').val() 
    },function(res){
        $.each(res,function(k,v){
            $(`.staff-task-time[data-staff="${v.staff.id}"]`).html(`
                <tr>
                    <th colspan="4">${ ucfirst(v.staff.name) }</th>
                </tr>
                <tr>
                    <th>Sl.No</th>
                    <th>Task</th>
                    <th>Working Time</th>
                    <th>Travel Time</th>
                </tr>             
            `)
            if(v.travels.length>0){
                $.each(v.travels,function(tk,tv){ 

                        $(`.staff-task-time[data-staff="${v.staff.id}"]`).append(`            
                            <tr>
                                <th>${tk+1}</th>
                                <td>${tv.taskname}</td>
                                <td>${mintostring(tv.taskTime||0)}</td>
                                <td>${mintostring(tv.travelTime||0)}</td>
                            </tr>
                        `) 
                })
            }else{
                $(`.staff-task-time[data-staff="${v.staff.id}"]`).append(`  
                    <tr>
                        <td colspan="4">
                            The data for the '${ ucfirst(v.staff.name) }' may be not loaded
                        </td>
                    </tr>
                `)
            }
            $(`.staff-task-time[data-staff="${v.staff.id}"]`).append(`            
                <tr>
                    <th>Total</th>
                    <td></td>
                    <td>${mintostring(v.totalTaskTime||0)}</td>
                    <td>${mintostring(v.totalTravelTime||0)}</td>
                </tr>
            `)
        })
    },'json')
}
async function selectfilterstaff(){ 
    $('.location-map').html('<span> The data for the selected staff may be not loaded.</span>')
    $('.location-staff').show()
    $.get("{{route('staff.manage-staff-task-location_staff')}}",{
        category:$('#category').val(),
        date:$('#date').val() 
    },function(res){
        if(res){
            $.each(res,function(k,staff){   
                if(staff.office_work.length>0||staff.travel.length>0){
                    $(`.location-map[data-staff="${staff.id}"]`).html(``)
                } 
                if(staff.travel){
                    $.each(staff.travel,function(trvlk,travel){ 
                        $(`.location-map[data-staff="${staff.id}"]`).append(` 
                            <div class="row">
                                <div class="col-xs-12"> 
                                    <div class="map-title">
                                        <h4> ${travel.task_name},${travel.travel_task_childname}</h4>
                                    </div> 
                                    <div class="row location-map-travel" data-travel="${travel.id}-${travel.travel_task_id}">
                                    <div>
                                </div>
                            </div>
                        `)
 
                        if(travel.travel_start_latitude&&travel.travel_start_longitude){ 
                            $(`.location-map-travel[data-travel="${travel.id}-${travel.travel_task_id}"]`).append(`                            
                                <div class="col-md-3"> 
                                    <div class="map-title">
                                        <h6>Travel start at ${travel.start_time_travel}</h6>
                                    </div>
                                    <div class="staff-location-map">
                                        <br><iframe src="https://maps.google.com/maps?q=${travel.travel_start_latitude},${travel.travel_start_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                    </div>
                                </div>
                            `)
                        }
   

                        if(travel.travel_end_latitude&&travel.travel_end_longitude){ 
                            $(`.location-map-travel[data-travel="${travel.id}-${travel.travel_task_id}"]`).append(`                            
                                <div class="col-md-3"> 
                                    <div class="map-title">
                                        <h6>Travel end at ${travel.end_time_travel}</h6>
                                    </div>
                                    <div class="staff-location-map">
                                        <br><iframe src="https://maps.google.com/maps?q=${travel.travel_end_latitude},${travel.travel_end_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                    </div>
                                </div>
                            `)
                        }
                        $.each(travel.childtravel,function(trchk,trchild){ 
                            $(`.location-map[data-staff="${staff.id}"]`).append(` 
                                <div class="row">
                                    <div class="col-xs-12"> 
                                        <!--<div class="map-title">
                                            <h4> ${travel.task_name},${travel.travel_task_childname}</h4>
                                        </div> -->
                                        <div class="row location-map-ctravel" data-ctravel="${trchild.id}-${trchild.travel_task_id}">
                                        <div>
                                    </div>
                                </div>
                            `)
                            if(trchild.travel_start_latitude&&trchild.travel_start_longitude){ 
                                $(`.location-map-ctravel[data-ctravel="${trchild.id}-${trchild.travel_task_id}"]`).append(`                            
                                    <div class="col-md-3"> 
                                        <div class="map-title">
                                            <h6>Travel start at ${trchild.start_time_travel}</h6>
                                        </div>
                                        <div class="staff-location-map">
                                            <br><iframe src="https://maps.google.com/maps?q=${trchild.travel_start_latitude},${trchild.travel_start_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                        </div>
                                    </div>
                                `)
                            }

                            if(trchild.travel_end_latitude&&trchild.travel_end_longitude){ 
                                $(`.location-map-ctravel[data-ctravel="${trchild.id}-${trchild.travel_task_id}"]`).append(`                            
                                    <div class="col-md-3"> 
                                        <div class="map-title">
                                            <h6>Travel end at ${trchild.end_time_travel}</h6>
                                        </div>
                                        <div class="staff-location-map">
                                            <br><iframe src="https://maps.google.com/maps?q=${trchild.travel_end_latitude},${trchild.travel_end_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                        </div>
                                    </div>
                                `)
                            }

                        })

                        if(travel.staff_task_time){ 
                            $(`.location-map[data-staff="${staff.id}"]`).append(` 
                                <div class="row">
                                    <div class="col-xs-12"> 
                                        <div class="map-title">
                                            <h4> ${travel.task_name}</h4>
                                        </div> 
                                        <div class="row location-map-task" data-task="${travel.id}-${travel.travel_task_id}">
                                        <div>
                                    </div>
                                </div>
                            `)
                            if(travel.staff_task_time.start_latitude&&travel.staff_task_time.start_longitude){ 
                                var starttime=new Date(travel.staff_task_time.start_time) 
                                 $(`.location-map-task[data-task="${travel.id}-${travel.travel_task_id}"]`).append(`                            
                                    <div class="col-md-3"> 
                                        <div class="map-title">
                                            <h6>Check-In :${starttime.toLocaleString('en-IN',{hour: 'numeric',minute: 'numeric',hour12: true})}</h6>
                                        </div>
                                        <div class="staff-location-map">
                                            <br><iframe src="https://maps.google.com/maps?q=${travel.staff_task_time.start_latitude},${travel.staff_task_time.start_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                        </div>
                                    </div>
                                `)
                            }

                            if(travel.staff_task_time.end_latitude&&travel.staff_task_time.end_longitude){ 
                                var endtime=new Date(travel.staff_task_time.end_time) 
                                 $(`.location-map-task[data-task="${travel.id}-${travel.travel_task_id}"]`).append(`                            
                                    <div class="col-md-3"> 
                                        <div class="map-title">
                                            <h6> Check-Out :${endtime.toLocaleString('en-IN',{hour: 'numeric',minute: 'numeric',hour12: true})}</h6>
                                        </div>
                                        <div class="staff-location-map">
                                            <br><iframe src="https://maps.google.com/maps?q=${travel.staff_task_time.end_latitude},${travel.staff_task_time.end_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                        </div>
                                    </div>
                                `)
                            } 
                        }
                        $.each(travel.travel_task_child,function(tskk,child){ 

                            if(child.staff_task_time){
                                $(`.location-map[data-staff="${staff.id}"]`).append(` 
                                    <div class="row">
                                        <div class="col-xs-12"> 
                                            <div class="map-title">
                                                <h4> ${child.task_name}</h4>
                                            </div> 
                                            <div class="row location-map-child" data-child="${travel.id}-${child.task_id}">
                                            <div>
                                        </div>
                                    </div>
                                `)
                                
                                if(child.staff_task_time.start_latitude&&child.staff_task_time.start_longitude){ 
                                    var starttime=new Date(child.staff_task_time.start_time) 
                                    $(`.location-map-child[data-child="${travel.id}-${child.task_id}"]`).append(`                            
                                        <div class="col-md-3"> 
                                            <div class="map-title">
                                                <h6> Check-In :${starttime.toLocaleString('en-IN',{hour: 'numeric',minute: 'numeric',hour12: true})}</h6>
                                            </div>
                                            <div class="staff-location-map">
                                                <br><iframe src="https://maps.google.com/maps?q=${child.staff_task_time.start_latitude},${child.staff_task_time.start_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                            </div>
                                        </div>
                                    `)
                                }

                                if(child.staff_task_time.end_latitude&&child.staff_task_time.end_longitude){ 
                                    var endtime=new Date(child.staff_task_time.end_time) 
                                    $(`.location-map-child[data-child="${travel.id}-${child.task_id}"]`).append(`                            
                                        <div class="col-md-3"> 
                                            <div class="map-title">
                                                <h6> Check-Out : ${endtime.toLocaleString('en-IN',{hour: 'numeric',minute: 'numeric',hour12: true})}</h6>
                                            </div>
                                            <div class="staff-location-map">
                                                <br><iframe src="https://maps.google.com/maps?q=${child.staff_task_time.end_latitude},${child.staff_task_time.end_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                            </div>
                                        </div>
                                    `)
                                } 
 
                            }
                        })
                    })
                }
                if(staff.office_work){
                    $.each(staff.office_work,function(ovk,office){
                        $(`.location-map[data-staff="${staff.id}"]`).append(` 
                            <div class="row">
                                <div class="col-xs-12"> 
                                    <div class="map-title">
                                        <h4> ${office.task_name}</h4>
                                    </div> 
                                    <div class="row location-map-office" data-office="${office.id}">
                                    <div>
                                </div>
                            </div>
                        `)
 
                        if(office.start_latitude&&office.start_longitude){ 
                            $(`.location-map-office[data-office="${office.id}"]`).append(`                            
                                <div class="col-md-3"> 
                                    <div class="map-title">
                                        <h6>Check-in : ${office.start_time}</h6>
                                    </div>
                                    <div class="staff-location-map">
                                        <br><iframe src="https://maps.google.com/maps?q=${office.start_latitude},${office.start_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                    </div>
                                </div>
                            `)
                        }

                        if(office.end_latitude&&office.end_longitude){ 
                            $(`.location-map-office[data-office="${office.id}"]`).append(`                            
                                <div class="col-md-3"> 
                                    <div class="map-title">
                                        <h6>Check-Out : ${office.end_time}</h6>
                                    </div>
                                    <div class="staff-location-map">
                                        <br><iframe src="https://maps.google.com/maps?q=${office.end_latitude},${office.end_longitude}&hl=es&z=15&output=embed" loading="lazy" allowfullscreen="" referrerpolicy="no-referrer-when-downgrade" frameborder="0" seamless="seamless" allow="fullscreen" width="100%" height="400px"></iframe>
                                    </div>
                                </div>
                            `)
                        }
 
                    }) 
                }
            }); 
        }
    },'json') 
}
$(function(){
    $('#category').change(function(){
        if($(this).val()==""){
            $('.staff-filter-list-item').show()
        }else{
            $('.staff-filter-list-item').hide()
            $(`.staff-filter-list-item[data-cat="${$(this).val()}"]`).show()
        }
        selectfilterstaff()
        selectfilterstafftime()
    })
    $('#date').datepicker({ 
        dateFormat:'yy-mm-dd', 
    }).change(function(){
        selectfilterstaff()
        selectfilterstafftime()
    })
    $('#category').change()
})
</script>

@endsection