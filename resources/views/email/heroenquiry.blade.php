@extends('email.emailhead')

@section('content')


 <!-- header-->
 <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; color:#000">
    <tbody>
            <tr>
                
                <td>
                    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px; color:#000">
                        
                        <tr>
                            <td style="height:30px;"></td>
                            <td style="height:30px;"></td>
                        </tr>
                    
                        <tr>
                            <td style="height:30px;">Name </td>
                            <td style="height:30px;">: {{$name}}</td>
                        </tr>
                        <tr>
                            <td style="height:30px;">Email </td>
                            <td style="height:30px;">: {{$email}}</td>
                        </tr>
                        <tr>
                            <td style="height:30px;">Phone </td>
                            <td style="height:30px;">: {{$phone}}</td>
                        </tr>
                        <tr>
                            <td style="height:30px;">Message </td>
                            <td style="height:30px;">: {{$messages}}</td>
                        </tr>
                        <tr>
                            <td style="height:30px;"></td>
                            <td style="height:30px;"></td>
                        </tr>
                    </table>
            </td>
            
        </tr>
        
        
    </tbody>
</table>
                                    <!-- header -->




@endsection