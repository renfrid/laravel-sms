@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">SMS Logs</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">SMS Logs</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Advanced Search</h4>
                    <div class="flex-shrink-0">
                        @if (Auth::user()->hasRole('admin'))
                            <a href="{{ route('sms-logs.delete-all') }}" class="btn btn-outline-danger btn-sm delete">
                                <i class="bx bx-trash bx-xs text-danger"></i> Delete all
                            </a>
                        @endif
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            {!! Form::open(['url' => route('sms-logs.lists'), 'method' => 'POST', 'class' => 'form-horizontal']) !!}
                            {{ Form::token() }}

                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        {{ Form::date('start_at', old('start_at'), ['placeholder="Start Date..."', 'class="form-control"']) }}
                                    </div>
                                </div>
                                <!--./col -->

                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        {{ Form::date('end_at', old('end_at'), ['placeholder="End Date..."', 'class="form-control"']) }}
                                    </div>
                                </div>
                                <!--./col -->

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        @php
                                            $_options = [];
                                        @endphp

                                        @foreach ($senders as $val)
                                            @php
                                                $_options[$val->name] = $val->name;
                                            @endphp
                                        @endforeach

                                        @php
                                            $_options = ['' => 'Sender'] + $_options;
                                        @endphp

                                        {{ Form::select('sender', $_options, old('sender'), ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <!--./col -->

                                <div class="col-lg-3">
                                    <div class="mb-3">
                                        @php
                                            $options = [
                                                'PENDING' => 'PENDING',
                                                'SENT' => 'SENT',
                                                'DELIVERED' => 'DELIVERED',
                                                'REJECTED' => 'REJECTED',
                                            ];
                                            $options = ['' => 'SMS Status'] + $options;
                                        @endphp

                                        {{ Form::select('status', $options, old('status'), ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <!--./col -->

                                <div class="col-lg-2">
                                    <div class="mb-3">
                                        <div class="text-sm-end d-none d-sm-block">
                                            <button type="submit" name="filter" class="btn btn-secondary">
                                                <i class="bx bx-search"></i> Filter
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <!--./col -->
                            </div>
                            <!--./row -->
                            {{ Form::close() }}
                        </div>
                        <!--./col-lg-12 -->
                    </div>
                    <!--./row -->
                </div>
            </div>

            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">SMS Logs</h4>
                    <div class="flex-shrink-0">
                        <span class="font-weight-500">No. of SMS:
                            {{ number_format(count($sms_logs)) }}</span>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif


                    <div class="live-preview">
                        @if (isset($sms_logs) && $sms_logs->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 8px;">#</th>
                                            <th scope="col" width="60%">SMS</th>
                                            <th scope="col" width="12%">Phone</th>
                                            <th scope="col" width="12%">Created On</th>
                                            <th scope="col" width="10%">No. of SMS</th>
                                            <th scope="col" width="10%">Status</th>
                                            <th scope="col" style="width: 40px;">Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @php
                                            $serial = 1;
                                        @endphp
                                        @foreach ($sms_logs as $value)
                                            <tr>
                                                <td>
                                                    {{ $serial }}
                                                </td>

                                                <td>{{ $value->message }}</td>
                                                <td>{{ $value->phone }}</td>
                                                <td>{{ $value->created_at }}</td>
                                                <td>{{ $value->sms_count }}</td>
                                                <td>{{ $value->status }}</td>
                                                <td>
                                                    @if (Auth::user()->hasRole('admin'))
                                                        <a href="{{ route('sms-logs.delete', $value->id) }}"
                                                            title="Delete Contact" class="btn btn-xss delete">
                                                            <i class="bx bx-trash bx-xs text-danger"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @php
                                                $serial++;
                                            @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- end table -->
                            </div>
                            <!-- end table responsive -->
                            <div class="d-flex justify-content-end mt-2">
                                {!! $sms_logs->links() !!}
                            </div>
                        @else
                            <div class="alert alert-danger"> No any sms at the moment!</div>
                        @endif
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
