@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Dashboard</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Summary</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row project-wrapper">
        <div class="col-xxl-8">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-primary text-primary rounded-2 fs-2">
                                        <i class="bx bx-comment-detail" class="text-primary"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                        Total SMS</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ number_format($data['total_sms']) }}">
                                                {{ number_format($data['total_sms']) }}</span>
                                        </h4>

                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-success text-success rounded-2 fs-2">
                                        <i class="bx bx-comment-check" class="text-success"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="text-uppercase fw-medium text-muted mb-3">Delivered SMS</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ number_format($data['delivered_sms']) }}">{{ number_format($data['delivered_sms']) }}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-2 fs-2">
                                        <i class="bx bx-comment-error" class="text-warning"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                        Pending SMS</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ number_format($data['pending_sms']) }}">{{ number_format($data['pending_sms']) }}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->

                <div class="col-xl-3">
                    <div class="card card-animate">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm flex-shrink-0">
                                    <span class="avatar-title bg-soft-danger text-danger rounded-2 fs-2">
                                        <i class="bx bx-comment-x" class="text-danger"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 overflow-hidden ms-3">
                                    <p class="text-uppercase fw-medium text-muted text-truncate mb-3">
                                        Rejected SMS</p>
                                    <div class="d-flex align-items-center mb-3">
                                        <h4 class="fs-4 flex-grow-1 mb-0"><span class="counter-value"
                                                data-target="{{ number_format($data['rejected_sms']) }}">{{ number_format($data['rejected_sms']) }}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div><!-- end card body -->
                    </div>
                </div><!-- end col -->
            </div><!-- end row -->

            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header border-0 align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">SMS Overview</h4>
                            <div>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    ALL
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    1M
                                </button>
                                <button type="button" class="btn btn-soft-secondary btn-sm">
                                    6M
                                </button>
                                <button type="button" class="btn btn-soft-primary btn-sm">
                                    1Y
                                </button>
                            </div>
                        </div><!-- end card header -->

                        <div class="card-header p-0 border-0 bg-soft-light">
                            <div class="row g-0 text-center">
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1"><span class="counter-value"
                                                data-target="{{ number_format($data['total_sms']) }}">{{ number_format($data['total_sms']) }}</span>
                                        </h5>
                                        <p class="text-muted mb-0">Total SMS</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1 text-success"><span class="counter-value"
                                                data-target="{{ number_format($data['delivered_sms']) }}">{{ number_format($data['delivered_sms']) }}</span>
                                        </h5>
                                        <p class="text-muted mb-0">Delivered SMS</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0">
                                        <h5 class="mb-1 text-warning"><span class="counter-value"
                                                data-target="{{ number_format($data['pending_sms']) }}">{{ number_format($data['pending_sms']) }}</span>
                                        </h5>
                                        <p class="text-muted mb-0">Pending SMS</p>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-6 col-sm-3">
                                    <div class="p-3 border border-dashed border-start-0 border-end-0">
                                        <h5 class="mb-1 text-danger"><span class="counter-value"
                                                data-target="{{ number_format($data['rejected_sms']) }}">{{ number_format($data['rejected_sms']) }}</span>
                                        </h5>
                                        <p class="text-muted mb-0">Rejected SMS</p>
                                    </div>
                                </div>
                                <!--end col-->
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body p-0 pb-2">
                            <div>
                                <div id="delivery-status-chart" class="apex-charts"></div>
                            </div>
                        </div><!-- end card body -->
                    </div><!-- end card -->
                </div><!-- end col -->
            </div><!-- end row -->
        </div><!-- end col -->

        <div class="col-xxl-4">
            <div class="card">
                <div class="card-header border-0">
                    <h4 class="card-title mb-0">Scheduled SMS</h4>
                </div><!-- end cardheader -->
                <div class="card-body pt-0">
                    <div class="upcoming-scheduled">
                        <input type="text" class="form-control" data-provider="flatpickr" data-date-format="d M, Y"
                            data-deafult-date="today" data-inline-date="true">
                    </div>


                    {{-- <div class="mini-stats-wid d-flex align-items-center mt-3">
                        <div class="flex-shrink-0 avatar-sm">
                            <span class="mini-stat-icon avatar-title rounded-circle text-success bg-soft-success fs-4">
                                27
                            </span>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">James Bangs (Client) Meeting</h6>
                            <p class="text-muted mb-0">Nesta Technologies</p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="text-muted mb-0">03:45 <span class="text-uppercase">pm</span>
                            </p>
                        </div>
                    </div>

                    <div class="mt-3 text-center">
                        <a href="javascript:void(0);" class="text-muted text-decoration-underline">
                            View Schedules
                        </a>
                    </div> --}}

                </div><!-- end cardbody -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title flex-grow-1 mb-0">Recent Delivered SMS</h4>
                    <div class="flex-shrink-0">
                        {{-- <a href="javascript:void(0);" class="btn btn-soft-info btn-sm">Export
                            Report</a> --}}
                    </div>
                </div><!-- end cardheader -->
                <div class="card-body">
                    <div class="table-responsive table-card">
                        {{-- <table class="table table-nowrap table-centered align-middle">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th scope="col">Project Name</th>
                                    <th scope="col">Project Lead</th>
                                    <th scope="col">Progress</th>
                                    <th scope="col">Assignee</th>
                                    <th scope="col">Status</th>
                                    <th scope="col" style="width: 10%;">Due Date</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td class="fw-medium">Brand Logo Design</td>
                                    <td>
                                        <img src="assets/images/users/avatar-1.jpg" class="avatar-xxs rounded-circle me-1"
                                            alt="">
                                        <a href="javascript: void(0);" class="text-reset">Donald
                                            Risher</a>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-1 text-muted fs-13">53%</div>
                                            <div class="progress progress-sm  flex-grow-1" style="width: 68%;">
                                                <div class="progress-bar bg-primary rounded" role="progressbar"
                                                    style="width: 53%" aria-valuenow="53" aria-valuemin="0"
                                                    aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="avatar-group flex-nowrap">
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-1.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-2.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                            <div class="avatar-group-item">
                                                <a href="javascript: void(0);" class="d-inline-block">
                                                    <img src="assets/images/users/avatar-3.jpg" alt=""
                                                        class="rounded-circle avatar-xxs">
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-soft-warning">Inprogress</span></td>
                                    <td class="text-muted">06 Sep 2021</td>
                                </tr>
                                
                            </tbody>
                        </table> --}}
                    </div>
                </div><!-- end card body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
