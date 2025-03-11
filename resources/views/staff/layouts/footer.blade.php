

<script>
   /* function checkSession() {
      var APP_URL = {!! json_encode(url('/')) !!}
      var url = APP_URL+'/admin/check_session';
      jQuery.ajax({
      type: "POST",
      cache: false,
      url: url,
      data:{
        type:'checksession'
      },
      success: function (data)
      {  //  alert(data)
       console.log(data)
       if(data>0)
       {
        location.reload();
       }
        //window.location.href=APP_URL+'/admin/AllTask';
      },
      error: function (data) {
location.reload();
}
    });

    }
    setInterval(checkSession, 5000);*/
</script>

    <div class="pull-right hidden-xs">
<!--      <b>Version</b> 2.4.0-->
    </div>
    <strong>Copyright &copy; <?php echo date('Y') ?> <a href="http://www.dotwibe.com/">dotwibe</a>.</strong> All rights
    reserved.
