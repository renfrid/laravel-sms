@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Message Templates</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('templates.index') }}">Message Templates</a></li>
                        <li class="breadcrumb-item active">Create Template</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Create template</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif

                    {{ Form::open(['url' => route('templates.store'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Title <span class="text-danger">*</span></label>
                                {{ Form::text('name', old('name'), ['class="form-control"', 'placeholder="Write a template title..."']) }}
                                <span class="text-danger">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Message <span class="text-danger">*</span></label>
                                {{ Form::textarea('message', old('message'), ['class="form-control"', 'placeholder="Write template message..."', 'rows="3"']) }}
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <a href="{{ url('templates') }}" class="btn btn-outline-danger btn-xs text-medium">Cancel</a>
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
