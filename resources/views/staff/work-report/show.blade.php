@extends('staff/layouts.app')
@section('title', 'Work Report')
@section('content')
    <section class="content-header">
        <h1>Work Report <span id="work_date_dis"></span> </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Work Report </li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12 workreport">
                <div class="box">
                    <div class="box-header">
                        <div class="option_section row">
                            <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                <select class="form-control" id="work-option">
                                    <option value="">Select Options</option>
                                    <option value="Request For Leave">Request For Leave</option>
                                    <option value="Work Update Office">Work Update Office</option>
                                    <option value="Work Update Field Staff">Work Update Field Staff</option>
                                </select>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="load_leave_option opt-section" style="display: none">
                                <div class="leave_section row pd-lr-none">
                                    <div class="form-check col-md-3 col-sm-6 col-lg-3 levedrop_sec_office">
                                        <label for="meter_start">Leave </label>
                                        <select class="form-control" name="staff_leave_office" id="staff_leave_office">
                                            <option value="">Select Leave</option>
                                            <option value="Half Day">Half Day</option>
                                            <option value="Full Day">Full Day</option>
                                        </select>
                                        <span class="error_message" id="staff_leave_office_message"
                                            style="display: none">Field is required</span>
                                    </div>
                                    <div class="form-check col-md-6 col-sm-6 col-lg-6 levedrop_sec_office">
                                        <label for="meter_start">Reason For Leave </label>
                                        <textarea class="form-check-input" name="reson_leave" id="reson_leave" value="" placeholder="Reason For Leave"></textarea>
                                        <span class="error_message" id="reson_leave_message" style="display: none">Field is
                                            required</span>
                                    </div>
                                    <div class="box-footer col-md-12 leave_section levedrop_sec_office"
                                        style="display:none;">
                                        <button type="button" class="mdm-btn submit-btn"
                                            onclick="submit_leave_office()">Submit</button>
                                        <div class="load_leave_office" style="display:none;">
                                            <img src="{{ asset('images/wait.gif') }}">
                                        </div>
                                    </div>
                                    <div class="leave_data_office"></div>
                                    <div class="dis_ajax_req_leave"></div>
                                </div>
                            </div>
                            <div class="work_officesection opt-section" style="display:none;">

                            </div>
                            <div class="work_fieldsection opt-section" style="display:none;">
                                <div class="travel-list">
                                    <table class="table" style="width:100%" id="travel-list-table">
                                        <thead>

                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>

    <div id="addtravel-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">End Travel</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form action="" method="POST" id="addtravel-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="addtravel-travel_type">Travel Type</label>
                                        <select class="form-control"  name="travel_type" id="addtravel-travel_type"  >
                                            <option value="">Select Travel Type</option>
                                            <option value="Bike">Bike</option>
                                            <option value="Car">Car</option>
                                            <option value="Train">Train</option>
                                            <option value="Bus">Bus</option>
                                            <option value="Auto">Auto</option>
                                        </select>
                                    </div>

                                    <div class="form-group car-bike-travel is-travel">
                                        <label for="addtravel-meter_start">Meter Reading </label>
                                        <input class="form-control" type="number" name="meter_start" id="addtravel-meter_start"
                                            value="" placeholder="Meter Reading">
                                        <small class="error-message text-danger" id="addtravel-meter_start-error"></small>
                                    </div>
                                    <div class="form-group is-travel">
                                        <label for="addtravel-start_time">Time </label>
                                        <input class="form-control page-timer-value" readonly type="text" readonly
                                            name="start_time" id="addtravel-start_time" value="<?php echo date('H:i'); ?>"
                                            placeholder="Meter Reading">
                                        <small class="error-message text-danger" id="addtravel-start_time-error"></small>
                                    </div>
                                    <div class="form-group no-car-bike-travel is-travel">
                                        <label for="addtravel-amount">Amount </label>
                                        <input class="form-control" type="number" name="amount" id="addtravel-amount"
                                            value="" placeholder="Amount">
                                        <small class="error-message text-danger" id="addtravel-amount-error"></small>
                                    </div>
                                    <div class="form-group is-travel">
                                        <label for="addtravel-fair_doc">Attach photo </label>
                                        <input class="form-control" type="file" name="fair_doc"
                                            id="addtravel-fair_doc" value="">
                                        <small class="error-message text-danger"
                                            id="addtravel-fair_doc-error"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row is-travel">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" id="addtravel-submit"> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="endtravel-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">End Travel</h4>
                </div>
                <div class="modal-body">
                    <div class="form">
                        <form action="" method="POST" id="endtravel-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group car-bike-travel">
                                        <label for="endtravel-meter_end">Meter Reading </label>
                                        <input class="form-control" type="number" name="meter_end" id="endtravel-meter_end"
                                            value="" placeholder="Meter Reading">
                                        <small class="error-message text-danger" id="endtravel-meter_end-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="endtravel-end_time">Time </label>
                                        <input class="form-control page-timer-value" readonly type="text" readonly
                                            name="end_time" id="endtravel-end_time" value="<?php echo date('H:i'); ?>"
                                            placeholder="Meter Reading">
                                        <small class="error-message text-danger" id="endtravel-end_time-error"></small>
                                    </div>
                                    <div class="form-group no-car-bike-travel">
                                        <label for="endtravel-amount">Amount </label>
                                        <input class="form-control" type="number" name="amount" id="endtravel-amount"
                                            value="" placeholder="Amount">
                                        <small class="error-message text-danger" id="endtravel-amount-error"></small>
                                    </div>
                                    <div class="form-group">
                                        <label for="endtravel-fair_doc_end">Attach photo </label>
                                        <input class="form-control" type="file" name="fair_doc_end"
                                            id="endtravel-fair_doc_end" value="">
                                        <small class="error-message text-danger"
                                            id="endtravel-fair_doc_end-error"></small>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-primary" id="endtravel-submit"> Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function addtraveldata(e, v) {
            amt = 0;
            kmdistance = "";
            if (v.travel_from_status == "Y" && (v.travel_type == "Bike" || v
                    .travel_type == "Car")) {
                km = (v.end_meter_reading || 0) - (v.start_meter_reading || 0);
                rate = 5;
                if (v.travel_type == "Bike") {
                    rate = 3;
                }
                amt = (km * rate);
                kmdistance = km;
            } else {
                amt = v.travel_end_amount || 0;
            }
            if (amt > 0) {
                amt = amt.toString().padStart(2, '0')
            }
            let stimg = "";
            if (v.travel_start_image) {
                let stimgurl =
                    `{{ asset('public/storage/comment/') }}/${v.v.travel_start_image}`;
                stimg =
                    `<a href="${stimgurl}" download><object data="${stimgurl}" width="50" height="50"></object> Download</a>`;
            }

            let enimg = "";
            if (v.travel_end_image) {
                let enimgurl =
                    `{{ asset('public/storage/comment/') }}/${v.v.travel_end_image}`;
                enimg =
                    `<a href="${enimgurl}" download><object data="${enimgurl}" width="50" height="50"></object> Download</a>`;
            }
            let taskname = "";
            let tasktime = "";
            let checkinstatus=false;
            if (v.travel_tasks) {
                let travelstatus=(v.travel_from_status == "Y")?true:false;
                if (v.child_travel) {
                    $.each(v.child_travel, function(rk, rv) {
                        if(v.travel_from_status != "Y"){
                            travelstatus=false;
                        }
                    })
                }
                $.each(v.travel_tasks, function(tk, tv) {
                    taskname += `<a class="popup" data-id="${tv.id}">${tv.name}</a><br>`;
                    if(v.travel_parent_id==0&&travelstatus){                        
                        if (tv.staff_task_time) {
                            if (tv.staff_task_time.start_time) {
                                var starttime = new Date(tv.staff_task_time.start_time)
                                tasktime +=
                                    `<span class="badge ">Started: ${starttime.toLocaleString('en-IN')}</span>`;
                            } else {
                                checkinstatus=true;
                                tasktime += '<button type="button" onclick="starthositalconversation(' + tv.id + ',' +v.expence_id +',this)" class="btn btn-primary btn-xs">Check-In</button>';
                            }
                            if (tv.staff_task_time.end_time) {
                                var endtime = new Date(tv.staff_task_time.end_time)
                                tasktime += `<span class="badge ">Ended: ${endtime.toLocaleString('en-IN')}</span>`;
                            } else {
                                checkinstatus=true;
                                tasktime += '<button type="button" onclick="endhositalconversation(' + tv.id + ',' +v.expence_id +',this)"  class="btn btn-primary btn-xs">Check-Out</button>';
                            }
                        } else {
                            checkinstatus=true;
                            tasktime += '<button type="button" onclick="starthositalconversation(' + tv.id + ',' +v.expence_id +',this)"  class="btn btn-primary btn-xs">Check-In</button>';
                        }
                        taskname+="<br>"
                    }
                })
            }
            let action = "";
            if (v.travel_from_status != "Y") {
                action = `
                <button type="button" class="btn btn-danger btn-sm" onclick="endTravel('${v.travel_type}','${v.endurl}')"> End Travel </button>
                `;
            } else {
                if(checkinstatus){

                    action = `
                    <button type="button" class="btn btn-primary btn-sm" onclick="addTravel('${v.addurl}')"> + Add Travel </button>
                    `;
                }
            }
            $(e).append(` 
                <tr> 
                    <td>${v.travel_type}</td>
                    <td>${v.start_meter_reading||"NA"}</td>
                    <td>${v.end_meter_reading||"NA"}</td>
                    <td>${kmdistance||"NA"}</td>
                    <td>${amt||"NA"}</td>
                    <td>${v.start_date}</td>
                    <td>${v.start_time_travel||"NA"}</td>
                    <td>${v.end_time_travel||"NA"}</td>
                    <td>${taskname}</td>
                    <td>${tasktime}</td>
                    <td>${stimg}</td>
                    <td>${enimg}</td> 
                    <td>${action}</td>
                </tr> 
            `)
            if (v.child_travel) {
                $.each(v.child_travel, function(rk, rv) {
                    addtraveldata(e, rv);
                })
            }
        }

        function change_options(opt) {
            $('.opt-section').hide();
            if (opt !== "") {
                if (opt == "Request For Leave") {
                    $('.load_leave_option').show()
                }
                if (opt == "Work Update Office") {
                    $('.work_officesection').show()
                }
                if (opt == "Work Update Field Staff") {
                    $('.work_fieldsection').show()

                    $.get("{{ route('staff.work-report.show', $cur_date->format('Y-m-d')) }}", {
                        option: opt,
                    }, function(res) {
                        if (res.length > 0) {
                            $('#travel-list-table').html(`
                            <thead class="travel-list-item">
                                <tr> 
                                    <th>Travel Type</th>
                                    <th>Start Reading</th>
                                    <th>End Reading</th>
                                    <th>kilometers</th>
                                    <th>Amount</th>
                                    <th>Start Date</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                    <th>Task</th>
                                    <th>Time</th>
                                    <th>Start Image</th>
                                    <th>End Image</th>
                                    <th></th>
                                </tr>
                            </thead>
                            `)
                            $.each(res, function(k, v) {
                                $('#travel-list-table').append(`
                                    <tbody id="travel-body-${v.expence_id}"> 
                                    </tbody>
                                `)
                                addtraveldata(`#travel-body-${v.expence_id}`, v);
                            })

                        } else {
                            $('#travel-list-table').html('')
                        }
                    }, 'json');
                }
            }
        }

        function addTravel(url) {
            $('#addtravel-form').attr("action", url) 
            $('#addtravel-meter_start').val('')
            $('#addtravel-fair_doc').val('')
            $('#addtravel-amount').val('')
            $('#addtravel-travel_type').val('').change()
            $('#addtravel-modal').modal('show')
        }

        function endTravel(travel_type, url) {
            $('#endtravel-form').attr("action", url)
            if (travel_type == "Bike" || travel_type == "Car") {
                $('.no-car-bike-travel').hide()
                $('.car-bike-travel').show()
            } else {
                $('.no-car-bike-travel').hide()
                $('.car-bike-travel').show()
            }
            $('#endtravel-meter_end').val('')
            $('#endtravel-fair_doc_end').val('')
            $('#endtravel-amount').val('')
            $('#endtravel-modal').modal('show')
        }
        function pagetimer() {
            var now = new Date();
            time = now.toLocaleString('en-IN', {
                hour: 'numeric',
                minute: 'numeric',
                hour12: true
            }).toUpperCase();
            $('.page-timer-text').text(time);
            $('.page-timer-value').val(time);
        }

        $(function() {
            setInterval(pagetimer, 1000);
            $('#work-option').change(function() {
                change_options(this.value)
            })
            $('#addtravel-travel_type').change(function(){
                let tvtype = $(this).val();
                if(tvtype){
                    $('.is-travel').show()
                    if (tvtype == "Bike" || tvtype == "Car") {
                        $('.no-car-bike-travel').hide()
                        $('.car-bike-travel').show()
                    } else {
                        $('.no-car-bike-travel').hide()
                        $('.car-bike-travel').show()
                    }
                }else{
                    $('.is-travel').hide()
                }
            })
            $('#addtravel-form').submit(function(e) {
                e.preventDefault();
                $('#addtravel-form .error-message').text('')
                $('#addtravel-submit').html(
                    ` Submit <img src="{{ asset('images/wait.gif') }}" alt="..." width="40"> `).prop(
                    'disabled', true)
                var formData = new FormData();
                formData.append('meter_start', $('#addtravel-meter_start').val());
                formData.append('amount', $('#addtravel-amount').val());
                formData.append('travel_type', $('#addtravel-travel_type').val());
                formData.append('start_time', $('#addtravel-start_time').val());
                if ($('#addtravel-fair_doc')[0].files.length > 0) {
                    formData.append('fair_doc', $('#addtravel-fair_doc')[0].files[0]);
                }
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        formData.append('travel_start_latitude', position.coords.latitude);
                        formData.append('travel_start_longitude', position.coords.longitude);

                        $.ajax({
                            type: "POST",
                            cache: false,
                            processData: false,
                            contentType: false,
                            url: $('#addtravel-form').attr("action"),
                            data: formData,
                            success: function(res) {
                                try {
                                    res = JSON.parse(res);
                                } catch (e) {}
                                if (res.success) {
                                    popup_notifyMe('success', res.success)
                                }
                                change_options('Work Update Field Staff');
                                $('#addtravel-modal').modal('hide')
                            },
                            error: function(xhr) {
                                const resText = xhr.responseText;
                                try {
                                    res = JSON.parse(resText);
                                    $.each(res.errors, function(k, v) {
                                        $(`#addtravel-${k}-error`).text(v[0]);
                                    })
                                } catch (e) {

                                }
                            },
                            complete: function(jqXHR, textStatus) {
                                $('#addtravel-submit').html(` Submit `).prop('disabled',
                                    false)
                            }
                        })

                    }, function(error) {
                        $('#addtravel-submit').html(` Submit `).prop('disabled', false)
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                popup_notifyMe('warning',
                                    "User denied the request for Geolocation.")
                                break;
                            case error.POSITION_UNAVAILABLE:
                                popup_notifyMe('warning', "Location information is unavailable.")
                                break;
                            case error.TIMEOUT:
                                popup_notifyMe('warning',
                                    "The request to get user location timed out.")
                                break;
                            case error.UNKNOWN_ERROR:
                                popup_notifyMe('warning', "An unknown error occurred.")
                                break;
                        }
                    });
                }
                return false;
            });
            $('#endtravel-form').submit(function(e) {
                e.preventDefault();
                $('#endtravel-form .error-message').text('')
                $('#endtravel-submit').html(
                    ` Submit <img src="{{ asset('images/wait.gif') }}" alt="..." width="40"> `).prop(
                    'disabled', true)
                var formData = new FormData();
                formData.append('meter_end', $('#endtravel-meter_end').val());
                formData.append('amount', $('#endtravel-amount').val());
                formData.append('end_time', $('#endtravel-end_time').val());
                if ($('#endtravel-fair_doc_end')[0].files.length > 0) {
                    formData.append('fair_doc_end', $('#endtravel-fair_doc_end')[0].files[0]);
                }
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        formData.append('travel_end_latitude', position.coords.latitude);
                        formData.append('travel_end_longitude', position.coords.longitude);

                        $.ajax({
                            type: "POST",
                            cache: false,
                            processData: false,
                            contentType: false,
                            url: $('#endtravel-form').attr("action"),
                            data: formData,
                            success: function(res) {
                                try {
                                    res = JSON.parse(res);
                                } catch (e) {}
                                if (res.success) {
                                    popup_notifyMe('success', res.success)
                                }
                                change_options('Work Update Field Staff');
                                $('#endtravel-modal').modal('hide')
                            },
                            error: function(xhr) {
                                const resText = xhr.responseText;
                                try {
                                    res = JSON.parse(resText);
                                    $.each(res.errors, function(k, v) {
                                        $(`#endtravel-${k}-error`).text(v[0]);
                                    })
                                } catch (e) {

                                }
                            },
                            complete: function(jqXHR, textStatus) {
                                $('#endtravel-submit').html(` Submit `).prop('disabled',
                                    false)
                            }
                        })

                    }, function(error) {
                        $('#endtravel-submit').html(` Submit `).prop('disabled', false)
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                popup_notifyMe('warning',
                                    "User denied the request for Geolocation.")
                                break;
                            case error.POSITION_UNAVAILABLE:
                                popup_notifyMe('warning', "Location information is unavailable.")
                                break;
                            case error.TIMEOUT:
                                popup_notifyMe('warning',
                                    "The request to get user location timed out.")
                                break;
                            case error.UNKNOWN_ERROR:
                                popup_notifyMe('warning', "An unknown error occurred.")
                                break;
                        }
                    });
                }
                return false;
            });
        })
    </script>
@endsection
