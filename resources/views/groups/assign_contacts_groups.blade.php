@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Assign Contacts to Group</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Assign Contacts to Group</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Assign Contacts to Group</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif

                    {{ Form::open(['url' => route('contacts-groups.store-contacts-groups'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Group Name <span class="text-danger">*</span></label>
                                @php
                                    $_options = [];
                                    foreach ($groups as $val) {
                                        $_options[$val->id] = $val->name;
                                    }
                                    $_options = ['' => '-- Select -- '] + $_options;
                                @endphp
                                {{ Form::select('group_id', $_options, old('group_id'), ['class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('group_id') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Contacts <span class="text-danger">*</span></label>
                                @php
                                    $_options = [];
                                    foreach ($contacts as $val) {
                                        $_options[$val->id] = $val->name . ' - ' . $val->phone;
                                    }
                                @endphp
                                {{ Form::select('contact_ids[]', $_options, old('contact_ids[]'), ['class="form-control select2"', 'multiple=""', 'data-placeholder="Select Address Book(s)..."']) }}
                                <span class="text-danger">{{ $errors->first('contact_ids') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row mt-2">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <a href="{{ url('contact-groups') }}"
                                    class="btn btn-outline-danger btn-xs text-medium">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-xs text-medium">Submit</button>
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
