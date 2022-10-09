@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Group Contacts : {{ $group->name }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Group Contacts</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">Assign/Remove Group Contacts</h4>
                    <div class="flex-shrink-0">
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    @if (session()->get('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div><br />
                    @endif

                    {{ Form::open(['url' => route('contact-groups.assign-contacts', $group->id), 'method' => 'POST', 'class' => 'form-horizontal']) }}
                    {{ Form::token() }}
                    <div class="row">
                        <div class="col-md-12">
                            <table>
                                <tr>
                                    @php $serial = 0; @endphp
                                    @foreach ($contacts as $val)
                                        @if ($serial % 4 == 0)
                                </tr>
                                <tr>
                                    @endif
                                    <td>
                                        {{ Form::checkbox('contact_ids[]', $val->id, in_array($val->id, $arr_contacts) ? true : false) }}
                                        <small
                                            class="font-weight-500">{{ $val->name . ' - ' . $val->phone }}</small>&nbsp;&nbsp;
                                    </td>
                                    @php $serial++; @endphp
                                    @endforeach
                                </tr>
                            </table>
                        </div>
                    </div>

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
