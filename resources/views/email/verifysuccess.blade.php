

@extends('email.emailhead')



@section('content')




<style>
    .MsoNormal span, .MsoNormal {
    color: #000 !important;
}

</style>
 <!-- header-->

 <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff;">

                                        <tbody>

                                           	 <tr>

                                                    

                                            	   <td>

                                                   		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px;">

                                                	<tr>

                                                	<tr>

                                            			<td style="height:30px;">Hi {{$name}},

                                                    <br><br>

                                                    Your account Verification on Beczone conpleated. Your username is {{$email}}. You can access your account area to {{$maildata}} more at : <a href="{{$siteurl}}">load more..</a>
                                                     <br><br>

                                                    </td>

                                            		</tr>

                                                    

                                                 

                                                  

                                                     <tr>

                                                        <td style="height:30px;">
                                                        <br>
                                                        Best Wishes<br>
                                                        Beczone
                                                    </td>

                                                    </tr>

                                                </table>

                                                </td>

                                                

                                            </tr>

                                            

                                            

                                       </tbody>

                                    </table>









@endsection

                    


