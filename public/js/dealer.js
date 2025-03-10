
function deleteItem(url)
{
    $('#btnDeleteItem').attr('data-href', url);
    $('#modalDelete').modal('show')
}
function deleteItemConfirm(btn) {
    url=btn.attr('data-href')      
    $.ajax({
        url: url,
        type: 'post',
        data:{
            _method:"DELETE"
            },
        success: function (data) {
            if (data['success']) {
                popup_notifyMe('success', data['success']); 
            } else if (data['error']) {
                popup_notifyMe('warning', data['error']); 
            } else {
                popup_notifyMe('danger', 'Whoops Something went wrong.');  
            }
        },

        error: function (data) {
            popup_notifyMe('danger', data.responseText);
        },
        complete: function(data) {
            $('#dealerProductTable').DataTable().ajax.reload();    
            $('#modalDelete').modal('hide')        
        }
    });    
}

function popup_notifyMe(type, msg) {
    if(type == 'success') {
        var icon = 'glyphicon glyphicon-ok';
        var title = 'Success';
    } else {
        var icon = 'glyphicon glyphicon-warning-sign';
        if(type == 'warning') {
            var title = 'Warning';
        } else {
            var title = 'Error';
        }
    }

    $.notify({
        icon: icon,
        title: '<strong>'+title+'</strong><br/>',
        message: msg
    },{
        type: type,
        delay: 3000,
    });
}