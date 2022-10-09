@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Send Group SMS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">SMS Logs</a></li>
                        <li class="breadcrumb-item active">Send Group SMS</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Send Group SMS</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (session()->get('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->


                    {{ Form::open(['url' => route('sms-logs.send-group-sms'), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Group Name(s) <span class="text-danger">*</span></label>
                                @php
                                    $_options = [];
                                    foreach ($groups as $val) {
                                        $_options[$val->id] = $val->name;
                                    }
                                @endphp
                                {{ Form::select('group_ids[]', $_options, old('group_ids[]'), ['class="form-control select2"', 'multiple=""', 'data-placeholder="Select Address Book(s)..."']) }}
                                <span class="text-danger">{{ $errors->first('group_ids') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Sender Name <span class="text-danger">*</span></label>
                                @php
                                    $_options = ['' => '-- Select --'];
                                    
                                    foreach ($senders as $val) {
                                        $_options[$val->name] = $val->name;
                                    }
                                @endphp
                                {{ Form::select('sender', $_options, old('sender'), ['class="form-control"']) }}
                                <span class="text-danger">{{ $errors->first('sender') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->

                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label>Message Template </label>
                                @php
                                    $_options = ['' => '-- Select --'];
                                    
                                    foreach ($templates as $val) {
                                        $_options[$val->id] = $val->name;
                                    }
                                @endphp
                                {{ Form::select('template_id', $_options, old('template_id'), ['class="form-control" id="template_id"']) }}
                                <span class="text-danger">{{ $errors->first('template_id') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label>Message <span class="text-danger">*</span></label>
                                {{ Form::textarea('message', old('message'), ['class="form-control"', 'placeholder="Write message here..."', 'rows="4"', 'id="message"']) }}
                                <span class="text-danger">{{ $errors->first('message') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <a href="{{ url('sms-logs/lists') }}" class="btn btn-outline-danger btn-xs text-medium">Cancel</a>
                                <button type="submit" class="btn btn-primary btn-xs text-medium">Send</button>
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

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            //on change template id
            $('#template_id').on('change', function(e) {
                var templateId = $(this).val();

                //query for data
                $.ajax({
                    url: '/templates/' + templateId + '/get-data',
                    type: "get",
                    success: function(data) {
                        if (data.error == false)
                            $('#message').html(data.message);
                        else if (data.error == true)
                            $('#message').html("");
                    },
                    error: function(data) {
                        $('#message').html("");
                    }
                });
            });
        });
    </script>
@endsection
