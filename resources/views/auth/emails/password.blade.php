<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="{{ asset('frontend/images/favicon.ico') }}" type="image/x-icon" />
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>

    <table cellpadding="0" cellspacing="0" border="0" bgcolor="#ecf0f1" width="600">
        <tr>
            <td colspan="2"><img src="{{asset('frontend/images/logo-3.png')}}"></td>
        </tr>
        <tr>
            <td colspan="2" style="font-size:14px;font-family:arial,verdana;">
                <table cellpadding="0" align="center" cellspacing="0" border="0" bgcolor="#ffffff"  width="72%" style="margin:0 auto 60px;">
                    <tr>
                        <td></td>
                        <td height="20">&nbsp;</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td width="5%"></td>
                        <td width="90%" style="font-size:14px;font-family:arial,verdana;"> Hi <b>{{ $user->getFirstname() }}</b></td>
                        <td width="5%"></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td height="20">&nbsp;</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="font-size:14px;font-family:arial,verdana;">
                            @if($user instanceof \App\Entity\admin)
                                Click here to reset your password: <a href="{{ $link = url('admin/password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> Click Here </a>
                            @else
                                Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> Click Here </a>
                            @endif</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td height="20">&nbsp;</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td style="font-size:14px;font-family:arial,verdana;">Thank you<br> <a href="#" style="color: #494E38;"><b>Gillie Team </b></a></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td height="20">&nbsp;</td>
                        <td></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center" height="30"></td>
        </tr>
        <tr>
            <td align="center" colspan="2"></td>
        </tr>
        <tr>
            <td style=" font-family:arial,verdana;    background: #494E38;color: white; text-align: center; padding: 15px 0; font-size: 12px;" colspan="2"> Copyright &#169; 2016 Gillie</td>
        </tr>
    </table>
</body>
</html>