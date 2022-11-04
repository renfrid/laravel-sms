@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">File SMS</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">SMS Logs</a></li>
                        <li class="breadcrumb-item active">File SMS</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">File SMS</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <a href="{{ asset('assets/sample/Import_file_sms_sample.xlsx') }}" class="font-weight-600">
                                    Download Sample File</a>
                            </div>
                        </div>
                    </div>
                    <!--./row -->

                    <div class="row">
                        <div class="col-lg-12">
                            @if (session()->get('success'))
                                <div class="alert alert-success">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            @if (session()->get('validation_errors'))
                                @foreach (session()->get('validation_errors') as $error)
                                    <div style="color: red;">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->

                    {{ Form::open(['url' => route('sms-logs.send-file-sms'), 'method' => 'POST', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                    {{ Form::token() }}

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label>Attachment <span class="text-danger">*</span></label>
                                {{ Form::file('attachment', ['class' => 'form-control', 'required=""']) }}
                                <span class="text-danger">{{ $errors->first('attachment') }}</span>
                            </div>
                        </div>
                        <!--./col-lg-6 -->
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
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Schedule</label><br />
                                {{ Form::checkbox('schedule', 1, old('schedule'), ['id' => 'schedule']) }} YES
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Schedule Date</label>
                                {{ Form::date('schedule_at', old('schedule_at'), ['class="form-control"', 'min' => date('Y-m-d')]) }}
                                <span class="text-danger">{{ $errors->first('schedule_at') }}</span>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label>Schedule Time</label>
                                @php
                                    $_options = [];
                                    $range = range(strtotime('06:00'), strtotime('23:00'), 30 * 60);
                                    foreach ($range as $time) {
                                        $_options[date('H:i', $time)] = date('H:i', $time);
                                    }
                                    $_options = ['' => '-- Select --'] + $_options;
                                @endphp
                                {{ Form::select('schedule_time', $_options, old('schedule_time'), ['class' => 'form-control']) }}
                                <span class="text-danger">{{ $errors->first('schedule_time') }}</span>
                            </div>
                        </div>
                    </div> <!-- /.row -->

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <a href="{{ url('sms-logs/lists') }}"
                                    class="btn btn-outline-danger btn-xs text-medium">Cancel</a>
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
