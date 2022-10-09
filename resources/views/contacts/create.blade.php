@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Contacts</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboards</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('contacts.index') }}">Contacts</a></li>
                        <li class="breadcrumb-item active">Register New Contact</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Register New Contact</h4>
                    <div class="flex-shrink-0">
                        
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif

                    {{ Form::open(['url' => route('contacts.store'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Contact Name <span class="text-danger">*</span></label>
                                {{ Form::text('name', old('name'), ['class="form-control"', 'placeholder="Write a contact name..."', 'required' => '']) }}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Contact Phone <span class="text-danger">*</span></label>
                                {{ Form::text('phone', old('phone'), ['class="form-control"', 'placeholder="Write a contact phone..."', 'required' => '']) }}
                                <span class="text-danger">{{ $errors->first('phone') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Contact Group</label>
                                <table>
                                    <tr>
                                        @php $serial = 0; @endphp
                                        @foreach ($groups as $val)
                                            @if ($serial % 6 == 0)
                                    </tr>
                                    <tr>
                                        @endif
                                        <td>
                                            {{ Form::checkbox('group_ids[]', $val->id, old('group_ids')) }}
                                            <small class="font-weight-500">{{ $val->name }}</small>&nbsp;&nbsp;
                                        </td>
                                        @php $serial++; @endphp
                                        @endforeach
                                    </tr>
                                </table>

                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <a href="{{ url('contacts') }}" class="btn btn-outline-danger btn-xs text-medium">Cancel</a>
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
