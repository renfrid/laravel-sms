@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Users</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active">Register New User</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Register New User</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif

                    {{ Form::open(['url' => route('users.store'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}

                    <div class="row">
                        <div class="col-lg-6 col-12">
                            <div class="mb-3">
                                <label>Full name <span class="text-danger">*</span></label>
                                {{ Form::text('name', old('name'), ['class="form-control"', 'placeholder="Write a full name..."']) }}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-4 -->

                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label>Email <span class="text-danger">*</span></label>
                                {{ Form::text('email', old('email'), ['class="form-control"', 'placeholder="Write a email address..."']) }}
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-4 -->

                        <div class="col-lg-3 col-12">
                            <div class="mb-3">
                                <label>Phone <span class="text-danger">*</span></label>
                                {{ Form::text('phone', old('phone'), ['class="form-control"', 'placeholder="Write a phone..."']) }}
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-4 -->
                    </div>
                    <!--./row -->

                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6 class="text-uppercase font-weight-600">Login Information</h6>
                            <hr />
                        </div>
                        <!--./col-md-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Roles <span class="text-danger">*</span></label>
                                <table>
                                    <tr>
                                        @php $serial = 0; @endphp
                                        @foreach ($roles as $role)
                                            @if ($serial % 6 == 0)
                                    </tr>
                                    <tr>
                                        @endif
                                        <td>
                                            {{ Form::checkbox('role_ids[]', $role->id, old('role_ids[]')) }}
                                            <label>{{ $role->description }}</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </td>
                                        @php $serial++; @endphp
                                        @endforeach
                                    </tr>
                                </table>
                                <span class="text-danger">{{ $errors->first('role_ids') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Password <span class="text-danger">*</span></label>
                                {{ Form::password('password', ['placeholder="Write a password..."', 'class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Confirm Password <span class="text-danger">*</span> </label>
                                {{ Form::password('password_confirm', ['placeholder="Confirm password..."', 'class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('password_confirm') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-danger text-medium">Cancel
                                </a>
                                <button type="submit" class="btn btn-primary text-medium">Submit</button>
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
