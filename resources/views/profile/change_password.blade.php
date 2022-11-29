@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Profile</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboards</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">Profile</a></li>
                        <li class="breadcrumb-item active">Change Password</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row project-wrapper">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Change Password</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="offset-2 col-lg-8">
                            @if (session()->get('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{ Form::open(['url' => route('profile.change-password'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}

                    <div class="row">
                        <div class="offset-2 col-lg-8">
                            <div class="mb-3">
                                <label>Current Password <span class="text-danger">*</span></label>
                                {{ Form::password('current_password', ['placeholder="Write a current password..."', 'class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('current_password') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="offset-2 col-lg-8">
                            <div class="mb-3">
                                <label>New Password <span class="text-danger">*</span></label>
                                {{ Form::password('new_password', ['placeholder="Write a new password..."', 'class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('new_password') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="offset-2 col-lg-8">
                            <div class="mb-3">
                                <label>Confirm Password <span class="text-danger">*</span></label>
                                {{ Form::password('new_password_confirm', ['placeholder="Confirm password..."', 'class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('new_password_confirm') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="offset-2 col-lg-8">
                            <div class="mb-3">
                                <a href="#" class="btn btn-outline-danger btn-xs text-medium">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-xs text-medium">
                                    Submit
                                </button>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->
                    {{ Form::close() }}
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
