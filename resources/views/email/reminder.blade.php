@extends('email.emailhead')

@section('content')
    <!-- header-->
    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="wrapper"
        style=" background-color:#fff; color:#000">
        <tbody>
            <tr>

                <td>
                    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="wrapper"
                        style=" background-color:#fff; margin-top:10px; color:#000">

                        <tr>
                            <td style="height:30px;">Date</td>
                            <td style="height:30px;">{{  $reminder->remind_date }} </td>
                        </tr>

                        <tr>
                            <td style="height:30px;">Title</td>
                            <td style="height:30px;">{{ $reminder->title }} </td>
                        </tr>
                        <tr>
                            <td style="height:30px;">Content</td>
                            <td style="height:30px;">{{ $reminder->content }} </td>
                        </tr>
                        <tr>
                            <td style="height:30px;">Status</td>
                            <td style="height:30px;">{{ $reminder->status  }} </td>
                        </tr>
                        <tr>
                            <td style="height:30px;">Link</td>
                            <td style="height:30px;">{{url('/admin/reminder') }} </td>
                        </tr>  
                    </table>
                </td>

            </tr>


        </tbody>
    </table>
    <!-- header -->
@endsection
