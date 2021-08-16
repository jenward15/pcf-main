@extends('layouts.app')
@section('title','PCF - PCF Request')

@section('content')
<div id="wrapper">

    <!-- Sidebar -->
    @include('layouts.sidebar')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            @include('layouts.navbar')
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">PCF Request</h1>
                </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <div class="row">
                                        <div class="col-md-4 offset-md-8">
                                            <a href="{{ route('PCF.sub.addrequest') }}"
                                                class="btn btn-primary float-right"><i class="fas fa-plus-circle"></i> New
                                                Request</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped" id="dataTable" width="100%"
                                            cellspacing="0">
                                            <thead>
                                                <tr bgcolor="gray" class="text-white">
                                                    <th>PCF No.</th>
                                                    <th>Date</th>
                                                    <th>Institution</th>
                                                    <th>PSR</th>
                                                    <th>Profit</th>
                                                    <th>Profit Rate</th>
                                                    <th>Actions</th>
                                                </tr> 
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content Row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- End of Main Content -->
        <!-- Modal Component -->
        @include('modals.PCFRequest.add')
        @include('modals.PCFRequest.edit')
        <!-- End of Modal Component -->
        <!-- Footer -->
        @include('layouts.footer')
        <!-- End of Footer -->
    </div>
    <!-- End of Content Wrapper -->
</div>
<!-- End of Page Wrapper -->
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "stripeClasses": [],
                processing: false,
                serverSide: true,
                ordering: true,
                ajax: {
                    "url": '{!! route('PCF.list') !!}'
                },
                "columns": [
                    { data: 'pcf_no' },
                    { data: 'date' },
                    { data: 'institution' },
                    { data: 'psr' },
                    { data: 'profit' },
                    { data: 'profit_rate' },
                    { data: 'actions' }
                ],
            });

        });

        // function editSpecimenTypes(data) {
        //     console.log(data.data(id));
        //     var id = data.data('id');
        //     var name = data.data('name');
        //     var description = data.data('description');

        //     $("#specimen_type_id").val(id);
        //     $("#edit_specimen_type").val(name);
        //     $("#edit_description").val(description);
        // }

        function ApproveRequest(data) {
            Swal.fire({
                title: 'Approve Request',
                text: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {

                    var id = data.data('id');

                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/PCF/ajax/approve-request/' + id,
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            // console.log(response);
                            //reload table 
                            $("#dataTable").DataTable().ajax.reload(null, false);
                            //fire the alert message
                            Swal.fire(
                                'Success!',
                                'PCF Request has been approved successfully!',
                                'success'
                            )
                        },
                        error: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Something went wrong!',
                                'Please contact your system administrator!',
                                'error'
                            )
                        }
                    });
                }
            })
        }

        function DisApproveRequest(data) {
            Swal.fire({
                title: 'Disapprove Request',
                text: "Are you sure?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Confirm'
            }).then((result) => {
                if (result.isConfirmed) {

                    var id = data.data('id');
                    $.ajax({
                        type: 'GET',
                        dataType: 'json',
                        url: '/PCF/ajax/disapprove-request/' + id,
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            // console.log(response);
                            //reload table
                            $("#dataTable").DataTable().ajax.reload(null, false);
                            //fire the alert message
                            Swal.fire(
                                'Disapprove Success!',
                                'Request has been disapproved!',
                                'success'
                            )
                        },
                        error: function(response) {
                            // console.log(response);
                            Swal.fire(
                                'Something went wrong!',
                                'Please contact your system administrator!',
                                'error'
                            )
                        }
                    });

                }
            })
        }

    </script>
@endsection