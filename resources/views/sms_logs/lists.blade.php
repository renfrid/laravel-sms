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
                    <h4 class="card-title mb-0 flex-grow-1">SMS Logs</h4>
                    <div class="flex-shrink-0">

                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif

                    <div class="live-preview">
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
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
