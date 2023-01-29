@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="modal modal-signin position-static d-block py-3" tabindex="-1" role="dialog" id="modalSignin">
                <div class="modal-dialog" role="document">
                    <div class="modal-content rounded-5 shadow">
                        <div class="modal-header p-4 pb-4 border-bottom-0">
                            <h2 class="fw-bold mb-0">Lupa Password</h2>
                        </div>
                        <div class="modal-body p-4 pt-0">

                            @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                            @endif

                            <form class="form-horizontal" role="form" method="POST" action="{{ route('password.email') }}">
                                {{ csrf_field() }}

                                <div class="form-group mb-3">
                                    <label for="email" class="control-label">Alamat E-mail</label>
                                    <input id="email" type="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                    <div class="invalid-feedback">
                                        {{ $errors->first('email') }}
                                    </div>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        Kirim Password Reset Link
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection