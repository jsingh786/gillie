@include('layouts.backend.header')
@section('pageTitle', 'Reset Password')
<div class="row">

    <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-2 col-md-4 col-md-offset-4">

        <div class="login-panel panel panel-default">
            <div class="panel-heading">Please Enter Your Email</div>
            @if (session('status'))
                <div style="color:green;font-weight:bold;text-align:center ">
                    {{ session('status') }}
                </div>
            @endif
            <div class="panel-body">
                <form role="form" method="POST" action="{{ url('admin/password/email') }}" >
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-5 control-label">E-Mail Address</label>

                            <div class="col-md-7">
                                <input id="email" type="email" class="form-control" name="email" placeholder="Enter your email address" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-7 col-md-offset-5" style="margin-top:10px">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->

@include('layouts.backend.footer')



