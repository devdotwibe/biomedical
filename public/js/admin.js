var data_table_refresh = null;

function deleteItem(url, table) {
    $('#btnDeleteItem').attr('data-href', url);
    $('#modalDelete').modal('show')
    data_table_refresh = $(table).DataTable();
}

function urlaction(url, table) {
    $.ajax({

        url: url,
        type: 'post',
        data: {
            verify: "verify"
        },
        success: function(data) {
            if (data['success']) {
                popup_notifyMe('success', data['success']);
                $(table).DataTable().ajax.reload();;
            } else if (data['error']) {
                popup_notifyMe('warning', data['error']);
            } else {
                popup_notifyMe('danger', 'Whoops Something went wrong.');
            }
        },

        error: function(data) {
            popup_notifyMe('danger', data.responseText);
        }
    });
}

function verifydealer(url) {

    $.ajax({

        url: url,
        type: 'post',
        data: {
            verify: "verify"
        },
        success: function(data) {
            if (data['success']) {
                popup_notifyMe('success', data['success']);
                location.reload();
            } else if (data['error']) {
                popup_notifyMe('warning', data['error']);
            } else {
                popup_notifyMe('danger', 'Whoops Something went wrong.');
            }
        },

        error: function(data) {
            popup_notifyMe('danger', data.responseText);
        }
    });
}
$(document).ready(function() {
    $('#frm_change_password').validate({ // initialize the plugin
        errorElement: 'span',
        rules: {
            current_password: {

                required: true,
                // checkTags: true
            },
            password: {
                required: true,
                //checkWhitespace:true,
                minlength: 5,
                maxlength: 15,
            },
            confirm_password: {
                required: true,
                maxlength: 15,
                minlength: 5,
                equalTo: '#password',
            }
        },

        messages: {
            current_password: {
                required: "Current password is required!",
                checkTags: "Current password can not allow script tag(s)!"

            },
            password: {
                required: "New password is required!",
                checkWhitespace: "New password can not allow white space(s)!",
                minlength: "New password should be atleast {0} characters!",
                maxlength: "New password not more than {0} characters!",
                checkTags: "New password can not allow script tag(s)!"
            },
            confirm_password: {
                required: "Confirm password is required!",
                checkWhitespace: "Confirm password can not allow white space(s)!",
                minlength: "Confirm password should be atleast {0} characters!",
                maxlength: "Confirm password not more than {0} characters!",
                equalTo: "Please enter the same password!",
                checkTags: "Confirm password can not allow script tag(s)!"
            }
        }
    });

    $('#frm_settings').validate({ // initialize the plugin
        errorElement: 'span',
        rules: {},

        messages: {}
    });

    $('#frm_cms').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            title: {
                required: true,
                maxlength: 100
            },
            description: {
                required: function(textarea) {
                    CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                    return editorcontent.length === 0;
                }
            }
        },

        messages: {
            name: {
                required: "Page name is required!",
            },
            title: {
                required: "Title is required!",
            },
            description: {
                required: "Content is required!",
            }
        }
    });

    $('#frm_banner').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            title: {
                required: true,
                maxlength: 100
            },
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            title: {
                required: "Title is required!",
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#frm_industry').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            title: {
                required: true,
                maxlength: 100
            },
            description: {
                required: function(textarea) {
                    CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                    return editorcontent.length === 0;
                }
            },
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            name: {
                required: "Page name is required!",
            },
            title: {
                required: "Title is required!",
            },
            description: {
                required: "Content is required!",
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#frm_dcate').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 100
            }
        },

        messages: {
            name: {
                required: "Category name is required!",
            }
        }
    });

    $('#frm_downloads').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            category_id: {
                required: true,
            },
            title: {
                required: true,
                maxlength: 50
            },
            download_title: {
                required: true,
                maxlength: 50
            },
            file: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_file').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }

                },
                extension: "pdf|zip|xlsx"
            }
        },

        messages: {
            category_id: {
                required: "Category is required!",
            },
            title: {
                required: "Title is required!",
            },
            download_title: {
                required: "Download title is required!",
            },
            file: {
                required: "File is required!",
                extension: "Please upload a valid file. file type is not supported"
            }
        }
    });

    $('#frm_persons').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            designation: {
                required: true,
                maxlength: 100
            },
            email: {
                required: true,
                email: true
            },
            phone: {
                required: true,
                maxlength: 50
            },
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            name: {
                required: "Name is required!",
            },
            designation: {
                required: "Designation is required!",
            },
            email: {
                required: "Email is required!",
                email: "Email address is invalid!",
            },
            phone: {
                required: "Phone is required!"
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#frm_feedbacks').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            designation: {
                required: true,
                maxlength: 100
            },
            description: {
                required: true,
                maxlength: 1000
            }
        },

        messages: {
            name: {
                required: "Name is required!",
            },
            designation: {
                required: "Designation is required!",
            },
            description: {
                required: "Feeback is required!"
            }
        }
    });


    $('#frm_references').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            /*title: {
                required:true,
            },*/
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            // title: {
            //     required: "Name is required!",
            // }, 
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });



    $('#frm_history').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            title: {
                required: true,
                maxlength: 100
            },
            description: {
                required: true,
                maxlength: 1000
            },
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            title: {
                required: "Title is required!",
            },
            description: {
                required: "Description is required!",
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#frm_cooperations').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            // title: {
            //     required:true,
            // },
            description: {
                required: true,
                maxlength: 1000
            },
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            // title: {
            //     required: "Title is required!",
            // }, 
            description: {
                required: "Description is required!",
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#frm_cat').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            name: {
                required: true,
                maxlength: 100
            },
            description: {
                required: {
                    depends: function() {
                        if ($('#parent_id').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                maxlength: 1000
            },
            image_name: {
                required: {
                    depends: function() {
                        //alert($('#current_image').val());
                        if ($('#current_image').val() == '' && $('#parent_id').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            name: {
                required: "Name is required!",
            },
            description: {
                required: "Description is required!",
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#frm_products').validate({ // initialize the plugin
        errorElement: 'span',
        ignore: [],
        rules: {
            category_id: {
                required: true,
            },
            name: {
                required: true,
                // maxlength:100
            },
            title: {
                required: true,
                //  maxlength:100
            },
            slug: {
                required: true,
                // maxlength: 100
            },
            meta_description: {
                maxlength: 1000
            },
            meta_keywords: {
                maxlength: 1000
            },
            description: {
                required: function(textarea) {
                    CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                    return editorcontent.length === 0;
                }
            },
            short_title: {
                required: true,
                maxlength: 100
            },
            short_description: {
                required: function(textarea) {
                    CKEDITOR.instances[textarea.id].updateElement(); // update textarea
                    var editorcontent = textarea.value.replace(/<[^>]*>/gi, ''); // strip tags
                    return editorcontent.length === 0;
                }
            },
            image_name: {
                required: {
                    depends: function() {
                        if ($('#current_image').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            },
            banner_image: {
                required: {
                    depends: function() {
                        if ($('#current_banner').val() == '') {
                            return true;
                        } else {
                            return false;
                        }
                    }
                },
                extension: "jpeg|png|jpg"
            }
        },

        messages: {
            category_id: {
                required: "Category is required!",
            },
            name: {
                required: "Product name is required!",
            },
            title: {
                required: "Title is required!",
            },
            slug: {
                required: "Seo url is required!",
            },
            description: {
                required: "Description is required!",
            },
            short_title: {
                required: "Short title is required!",
            },
            short_description: {
                required: "Short description is required!",
            },
            image_name: {
                required: "Image is required!",
                extension: "Please upload a valid image. file type is not supported"
            },
            banner_image: {
                required: "Banner image is required!",
                extension: "Please upload a valid image. file type is not supported"
            }
        }
    });

    $('#parent_id').on('change', function() {
        //alert($(this).val());
        if ($(this).val() == '') {
            $('.hideRow').show();
        } else {
            $('.hideRow').hide();
        }
    });


});

function popup_notifyMe(type, msg) {
    if (type == 'success') {
        var icon = 'glyphicon glyphicon-ok';
        var title = 'Success';
    } else {
        var icon = 'glyphicon glyphicon-warning-sign';
        if (type == 'warning') {
            var title = 'Warning';
        } else {
            var title = 'Error';
        }
    }

    $.notify({
        icon: icon,
        title: '<strong>' + title + '</strong><br/>',
        message: msg
    }, {
        type: type,
        delay: 3000,
    });
}


$(function() {
    // $('#example1').DataTable();
    //$('#cmsTable').DataTable({
    //      'paging'      : true,
    //      'lengthChange': false,
    //      'searching'   : false,
    //      'ordering'    : true,
    //      'info'        : true,
    //      'autoWidth'   : false
    // });

    //    $('#cmsTable').DataTable( {
    //        "processing": true,
    //        "serverSide": true,
    //        "ajax": "../server_side/scripts/server_processing.php"
    //    } );

    //$('#btn_deleteAll').hide();

    $('#selectAll').click(function() {
        if ($(this).hasClass('allChecked')) {
            $('.table').find('input[type="checkbox"]').prop('checked', false);
        } else {
            $('.table').find('input[type="checkbox"]').prop('checked', true);
        }
        $(this).toggleClass('allChecked');

        var check_count = 0;
        $('.dataCheck').each(function() {
            if ($(this).prop('checked')) {
                check_count++;
            }
        });

        if (check_count > 0) {
            // $('#btn_deleteAll').show();
        } else {
            //$('#btn_deleteAll').hide();
        }
    });

    $('.dataCheck').click(function() {
        var check_count = 0;
        $('.dataCheck').each(function() {
            if ($(this).prop('checked')) {
                check_count++;
            }
        });

        if (check_count > 0) {
            //$('#btn_deleteAll').show();
        } else {
            // $('#btn_deleteAll').hide();
        }
    });

    $('.deleteItem').on('click', function(e) {
        var id = $(this).attr('id');
        var url = $(this).attr('href');
        $('#btnDeleteItem').attr('data-id', id);
        $('#btnDeleteItem').attr('data-href', url);
        $('#modalDelete').modal();
        return false;
    });

    $('#btnDeleteItem').on('click', function(e) {
        $('#modalDelete').modal('hide');
        var url = $(this).attr('data-href');

        $.ajax({

            url: url,
            type: 'post',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: {
                _method: "DELETE"
            },
            success: function(data) {
                if (data['success']) {

                    $('#cmsTable').DataTable().ajax.reload();
                    $("#" + data['tr']).slideUp("slow");
                    popup_notifyMe('success', data['success']);
                    if (data_table_refresh != null) {
                        data_table_refresh.ajax.reload();
                    }
                } else if (data['error']) {
                    popup_notifyMe('warning', data['error']);
                } else {
                    popup_notifyMe('danger', 'Whoops Something went wrong.');
                }
            },

            error: function(data) {
                popup_notifyMe('danger', data.responseText);
            }
        });
    });


    $('#btnDeleteAll').on('click', function(e) {
        $('#dataForm').submit();
    });

    //Status Change
    $('.statusItem').on('click', function() {
        var id = $(this).attr('data-id');
        var from = $(this).attr('data-from');
        var elemId = $(this).attr('id');

        var url = APP_URL + '/admin/ajaxChangeStatus';
        $.ajax({
            url: url,
            type: 'POST',
            //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { id: id, from: from },
            success: function(data) {
                if (data['success']) {

                    if (data['status'] == 'Y') {
                        $('#' + elemId).removeClass('btn-danger');
                        $('#' + elemId).addClass('btn-success');
                        $('#' + elemId).html('<span class="glyphicon glyphicon-ok"></span>');
                        $('#' + elemId).attr('title', 'Active');
                    } else {
                        $('#' + elemId).removeClass('btn-success');
                        $('#' + elemId).addClass('btn-danger');
                        $('#' + elemId).html('<span class="glyphicon glyphicon-ban-circle"></span>');
                        $('#' + elemId).attr('title', 'Inactive');
                    }

                    popup_notifyMe('success', data['success']);
                } else if (data['error']) {
                    popup_notifyMe('warning', data['error']);
                } else {
                    popup_notifyMe('danger', 'Whoops Something went wrong.');
                }
            },

            error: function(data) {
                popup_notifyMe('danger', data.responseText);
            }
        });
    });

    //Staff Status Change
    $('.statusChange').on('click', function() {
        var id = $(this).attr('data-id');
        var from = $(this).attr('data-from');
        var elemId = $(this).attr('id');

        var url = APP_URL + '/ajaxChangeStatus';
        $.ajax({
            url: url,
            type: 'POST',
            //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { id: id, from: from },
            success: function(data) {
                if (data['success']) {

                    if (data['status'] == 'Y') {
                        $('#' + elemId).removeClass('btn-danger');
                        $('#' + elemId).addClass('btn-success');
                        $('#' + elemId).html('<span class="glyphicon glyphicon-ok"></span>');
                        $('#' + elemId).attr('title', 'Active');
                    } else {
                        $('#' + elemId).removeClass('btn-success');
                        $('#' + elemId).addClass('btn-danger');
                        $('#' + elemId).html('<span class="glyphicon glyphicon-ban-circle"></span>');
                        $('#' + elemId).attr('title', 'Inactive');
                    }

                    popup_notifyMe('success', data['success']);
                } else if (data['error']) {
                    popup_notifyMe('warning', data['error']);
                } else {
                    popup_notifyMe('danger', 'Whoops Something went wrong.');
                }
            },

            error: function(data) {
                popup_notifyMe('danger', data.responseText);
            }
        });
    });

    //Default Staus Change
    $('.defItem').on('click', function() {
        var id = $(this).attr('data-id');
        var from = $(this).attr('data-from');
        var elemId = $(this).attr('id');

        if (!$(this).hasClass('btn-success')) {

            var url = APP_URL + '/admin/ajaxChangeDefaultStatus';
            $.ajax({
                url: url,
                type: 'POST',
                //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: { id: id, from: from },
                success: function(data) {
                    if (data['success']) {

                        if (data['status'] == 'Y') {
                            $('#' + elemId).removeClass('btn-danger');
                            $('#' + elemId).addClass('btn-success');
                            $('#' + elemId).html('<span class="glyphicon glyphicon-ok"></span>');
                            $('#' + elemId).attr('title', 'Yes');
                        } else {
                            $('#' + elemId).removeClass('btn-success');
                            $('#' + elemId).addClass('btn-danger');
                            $('#' + elemId).html('<span class="glyphicon glyphicon-ban-circle"></span>');
                            $('#' + elemId).attr('title', 'No');
                        }

                        popup_notifyMe('success', data['success']);

                        window.location.reload(true);
                    } else if (data['error']) {
                        popup_notifyMe('warning', data['error']);
                    } else {
                        popup_notifyMe('danger', 'Whoops Something went wrong.');
                    }
                },

                error: function(data) {
                    popup_notifyMe('danger', data.responseText);
                }
            });
        }

    });




});


function deleteAll(from) {
    var check_count = 0;
    $('.dataCheck').each(function() {
        if ($(this).prop('checked')) {
            check_count++;
        }
    });

    if (check_count == 0) {
        //alert("Please select an item to delete."); 
        popup_notifyMe('danger', 'Please select an item to delete.');
    } else {
        $('#modalDeleteAll').modal();
        return false;
        //$('#dataForm').submit();
    }
}


$(document).ready(function() {
    // $('.openTable').on('click',  function () {
    //     var tr = $(this).closest('tr');
    //     var row = oTable.row( tr );

    //     var id = $(tr).attr('data-id');
    //     var from = $(tr).attr('data-from');

    //     if ( row.child.isShown() ) {
    //         row.child.hide();
    //         tr.removeClass('shown');
    //     }
    //     else {
    //         var resp = getRowDetails(id, from, row,tr);
    //     }
    // } );
});


function getRowDetails(id, from, row, tr) {

    var url = APP_URL + '/admin/ajaxDataDetails';
    $.ajax({
        url: url,
        type: 'POST',
        //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { id: id, from: from },
        success: function(data) {
            if (data['success']) {
                var status = data['data'];
                //console.log(status['data'].name);
                row.child(status).show();
                tr.addClass('shown');
                return 1;
            } else if (data['error']) {
                popup_notifyMe('warning', data['error']);
            } else {
                popup_notifyMe('danger', 'Whoops Something went wrong.');
            }
        },

        error: function(data) {
            popup_notifyMe('danger', data.responseText);
        }
    });
    return false;
}

//update order tables
function updateOrder(updateTable, orderBy, ids) {
    $.ajax({
        url: APP_URL + '/admin/updateOrder',
        type: 'POST',
        //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        data: { updateTable: updateTable, orderBy: orderBy, ids: ids },
        success: function(data) {

        },

        error: function(data) {
            popup_notifyMe('danger', data.responseText);
        }
    });
}