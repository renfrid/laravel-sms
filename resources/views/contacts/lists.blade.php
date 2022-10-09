@extends('layouts.app-backend')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Contacts</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active">Contacts</li>
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
                    <h4 class="card-title mb-0 flex-grow-1">List of contacts</h4>
                    <div class="flex-shrink-0">
                        <a href="{{ route('contacts.create') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-plus"></i> Register New
                        </a>

                        <a href="{{ route('contacts.import') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fa fa-plus"></i> Import
                        </a>
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
                            <table class="table align-middle table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 42px;">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value=""
                                                    id="responsivetableCheck">
                                                <label class="form-check-label" for="responsivetableCheck"></label>
                                            </div>
                                        </th>
                                        <th scope="col" width="20%">Name</th>
                                        <th scope="col" width="12%">Phone</th>
                                        <th scope="col" width="30%">Groups</th>
                                        <th scope="col" width="14%">Created On</th>
                                        <th scope="col" width="10%">Status</th>
                                        <th scope="col" style="width: 80px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($contacts as $value)
                                        <tr>
                                            <th scope="row">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value=""
                                                        id="responsivetableCheck01">
                                                    <label class="form-check-label" for="responsivetableCheck01"></label>
                                                </div>
                                            </th>

                                            <td>
                                                {{ $value->name }}
                                            </td>
                                            <td>{{ $value->phone }}</td>
                                            <td>
                                                @if ($value->groups->isNotEmpty())
                                                    @foreach ($value->groups as $val)
                                                        {{ $val->name }}<br/>
                                                    @endforeach
                                                @endif
                                            </td>

                                            <td>{{ $value->created_at }}</td>
                                            <td class="text-success">
                                                <i class="ri-checkbox-circle-line fs-17 align-middle"></i>
                                                Active
                                            </td>

                                            <td>
                                                <a href="{{ route('contacts.edit', $value->id) }}" title="Edit Contact"
                                                    class="btn btn-xss">
                                                    <i class="bx bx-edit bx-xs"></i>
                                                </a>

                                                <a href="{{ route('contacts.delete', $value->id) }}"
                                                    title="Delete Contact" class="btn btn-xss delete">
                                                    <i class="bx bx-trash bx-xs text-danger"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- end table -->
                        </div>
                        <!-- end table responsive -->
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->
@endsection
