@extends('layouts.alert')
@extends('layouts.app')
@section('content')
<div class="py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="modal modal-signin position-static d-block py-3" tabindex="-1" role="dialog" id="modalSignin">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content rounded-5 shadow-sm">
                            <div class="modal-header p-4 pb-4 border-bottom-0">
                                <h2 class="fw-bold mb-0">Login</h2>
                            </div>

                            <div class="modal-body p-4 pt-0">
                                <form class="form-horizontal" role="form" method="POST" action="{{ route('login') }}">
                                    {{ csrf_field() }}
                                    <div class="form-floating mb-3">
                                        <input id="email" type="text" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}" aria-describedby="basic-addon1" placeholder="Email/ Username" autofocus>
                                        <label for="email">Email address</label>
                                        @if ($errors->has('email'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('email') }}
                                        </div>
                                        @endif
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input id="password" type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" name="password" value="{{ old('password') }}" aria-describedby="basic-addon1" placeholder="Password">
                                        <label for="password">Password</label>
                                        @if ($errors->has('password'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('password') }}
                                        </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <!-- An element to toggle between password visibility -->
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" onclick="myPassword()" id="defaultCheck1">
                                                <label for="defaultCheck1">
                                                    Show Password
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <a class="float-end" href="{{ route('password.request') }}">Lupa Password?</a>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <button class="w-100 my-3 btn btn-lg rounded-4 btn-primary" type="submit">Masuk</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    function myPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
</script>
@endsection