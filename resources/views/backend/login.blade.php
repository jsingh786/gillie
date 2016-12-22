@include('backend.header')

<div class="row">
    <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
            <div class="panel-heading">Log in</div>
            <div class="panel-body">
                <form role="form" method="POST" action="{{ url('/admin/login') }}" >
                    {{ csrf_field() }}
                    @if ($errors->has('Invalid'))
                        <span class="help-block" style="color:#a94442">
                            <strong>{{ $errors->first('Invalid') }}</strong>
                        </span>
                    @endif
                    <fieldset>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <input class="form-control" placeholder="E-mail" name="email" value="{{old('email')}}" type="email" autofocus="">
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <input class="form-control" placeholder="Password" name="password" type="password" value="">
                            @if ($errors->has('password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                            @endif
                        </div>
                        <div class="checkbox">
                            <label class="pull-right">
                                <a href="{{url('admin/password/email')}}">Forgot Password? Please Click here.</a>
                            </label>
                        </div>
                        <button type="submit"  class="btn btn-primary">Login</button>
                    </fieldset>
                </form>
            </div>
        </div>
    </div><!-- /.col-->
</div><!-- /.row -->

@include('layouts.backend.footer')



