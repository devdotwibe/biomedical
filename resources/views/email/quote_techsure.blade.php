@extends('email.emailhead_techsure')

@section('content')


 <!-- header-->
 <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; color:#000">
                                        <tbody>
                                           	 <tr>
                                                    
                                            	   <td>
                                                   		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px; color:#000">
                                                           
                                                           {{-- <tr>
                                                                <td style="height:30px;">{{$name}} </td>
                                                            </tr>
                                                          
                                                            <tr>
                                                                <td style="height:30px;">{{$subject}} </td>
                                                            </tr> --}}
                                                            <tr>
                                                                <td style="height:30px;"><?=$desc?> </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="height:30px;"><a href="{{$url}}">{{$url}}</a> </td>
                                                            </tr>
                                                        </table>
                                                </td>
                                                
                                            </tr>
                                            
                                            
                                       </tbody>
                                    </table>
                                    <!-- header -->




@endsection