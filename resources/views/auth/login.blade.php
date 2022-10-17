@extends('layouts.app-login')

@section('content')
    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position bg-primary" id="auth-particles">
            {{-- <div class="bg-overlay"></div> --}}

            {{-- <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div> --}}
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center text-white-50">
                            <div>
                                <a href="#" class="d-inline-block auth-logo">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="" height="120">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium text-white">Economic and Social Research Foundation</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-2">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">SMS Platform</h5>
                                    <p class="text-muted">Sign in to continue to SMS Platform.</p>
                                </div>
                                <div class="p-2 mt-4">

                                    @if (session()->get('success'))
                                        <div class="alert alert-success">
                                            {{ session()->get('success') }}
                                        </div><br />
                                    @elseif(session()->get('danger'))
                                        <div class="alert alert-danger">
                                            {{ session()->get('danger') }}
                                        </div><br />
                                    @endif

                                    <div id="login_alert"></div>

                                    <form autocomplete="on" id="LoginForm">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="text" class="form-control" id="email" name="email"
                                                placeholder="Enter email...">
                                        </div>

                                        <div class="mb-3">
                                            <div class="float-end">
                                                <a href="#" class="text-muted">Forgot password?</a>
                                            </div>
                                            <label class="form-label" for="password-input">Password</label>
                                            <div class="position-relative auth-pass-inputgroup mb-3">
                                                <input type="password" class="form-control pe-5"
                                                    placeholder="Enter password" id="password-input" name="password">
                                                <button
                                                    class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                                                    type="button" id="password-addon"><i
                                                        class="ri-eye-fill align-middle"></i></button>
                                            </div>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value=""
                                                id="auth-remember-check">
                                            <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                        </div>

                                        <div class="mt-4">
                                            <button class="btn btn-primary w-100" type="submit" id="btnSubmit">
                                                Sign In <i class="loading-spinner fa fa-lg fa fa-spinner fa-spin"></i>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                        <div class="mt-4 text-center">
                            <p class="mb-0">Don't have an account ? <a href="#"
                                    class="fw-semibold text-primary text-decoration-underline"> Signup </a> </p>
                        </div>

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>
                                    document.write(new Date().getFullYear())
                                </script> SMS Platform. Developed <i class="mdi mdi-brain text-danger"></i>
                                by ESRF ICT Department.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //validate form and submit
            $("#LoginForm").validate({
                errorClass: "text-danger",
                errorElement: "span",
                rules: {
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true
                    }
                },
                messages: {
                    email: {
                        required: "Email required",
                        email: "Invalid email provided",
                    },
                    password: {
                        required: "Password required"
                    }
                },
                submitHandler: function(form, e) {
                    e.preventDefault();

                    // Activate the loading spinner
                    $('.loading-spinner').toggleClass('active');

                    // Get form
                    var form = $(form)[0];

                    // FormData object 
                    var data = new FormData(form);

                    //append token
                    data.append("_token", "{{ csrf_token() }}");

                    // disabled the submit button
                    $("#btnSubmit").prop("disabled", true);

                    //post data 
                    $.ajax({
                        type: "POST",
                        url: "{{ url('login') }}",
                        data: data,
                        processData: false,
                        contentType: false,
                        cache: false,
                        timeout: 800000,
                        success: function(response) {
                            //activate submit button
                            $("#btnSubmit").prop("disabled", false);

                            //deactivate loading spinner
                            $('.loading-spinner').toggleClass('active');

                            $.notify(response.success_msg, 'success');
                            setTimeout(function() {
                                window.location.href = '/dashboard';
                            }, 500);
                        },
                        error: function(response) {
                            //activate submit button
                            $("#btnSubmit").prop("disabled", false);

                            // Deactivate Loading Spinner
                            $('.loading-spinner').toggleClass('active');

                            //error
                            $('#login_alert').html('<div class="alert alert-danger">' +
                                response.responseJSON.errors + '</div>')
                        }
                    });
                    return false;
                },
            });
        });
    </script>
@endsection
