@extends('layouts.main.main')

@section('title', 'Task')

@section('css-plugins')
  <!-- Sweet Alert-->
  <link href="{{ url('assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
  <!-- third party css -->
  <link href="{{ url('assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
    type="text/css" />
  <link href="{{ url('assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}" rel="stylesheet"
    type="text/css" />
  <!-- third party css end -->
@endsection

@section('content')
  <div class="content">

    <!-- Start Content-->
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box">
            <div class="page-title-right">
              <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"></li>
                <li class="breadcrumb-item active">Task</li>
              </ol>
            </div>
            <h4 class="page-title">Task</h4>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">

                <div class="col-12 text-sm-end mb-2">
                  @if(in_array("add",$permissions))
                    <a href="{{ route('task-create') }}" class="btn btn-primary waves-effect waves-light"><i
                      class="mdi mdi-plus-circle me-1"></i> Add Task</a>
                  @endif
                </div><!-- end col-->

                <div class="col-12">
                  <div class="table-responsive">
                    <table class="table table-centered table-striped dt-responsive nowrap w-100" id="datatable">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th></th>
                            @if(in_array("status",$permissions))
                            <th class="no-sort" width="10%">Status</th>
                            @endif
                          @if ($count > 0)
                          <th class="text-sm-center no-sort" width="10%">Action</th>
                          @endif
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>

              </div>
            </div> <!-- end card-body-->
          </div> <!-- end card-->
        </div> <!-- end col -->
      </div>


    </div> <!-- container -->

  </div> <!-- content -->
@endsection

@section('js-plugins')
  <!-- Sweet Alerts js -->
  <script src="{{ url('assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
  <!-- third party js -->
  <script src="{{ url('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
  <script src="{{ url('assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
  <script src="{{ url('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ url('assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
@endsection

@section('js-page-specific')
  <script>
    $(document).ready(function() {
      "use strict";
      let table = $("#datatable").DataTable({
        language: {
          paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>"
          },
          info: "Showing task _START_ to _END_ of _TOTAL_",
          lengthMenu: 'Display <select class=\'form-select form-select-sm ms-1 me-1\'><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option><option value="-1">All</option></select> Task'
        },
        order: [] ,
        processing: true,
        serverSide: true,
        ajax: '{{ route('task-datatable') }}',
        columns: [{
            "data": "DT_RowIndex",
            "defaultContent": ""
          },
          {
            "data": "",
            "defaultContent": "",
          },
            @if(in_array("status",$permissions))
          {
            "data": "status",
            "defaultContent": ""
          },
          @endif
          @if ($count > 0)
            {
              "data": "id",
              "name": "id",
              "defaultContent": ""
            },
          @endif
        ],
        "columnDefs": [{
            "targets": 'no-sort',
            "orderable": false,
          },
        @if(in_array("status",$permissions))
          {
            "targets": {{$count ? -2: -1}},
            "render": function(data, type, row, meta) {
              return `
                <div class="form-check form-switch">
                  <input type="checkbox" class="form-check-input js-status-switch" data-id="${row.id}" ${data ? "checked" : null}>
                </div>
              `;
            },
          },
        @endif
          @if ($count > 0)
            {
              "targets": -1,
              "render": function(data, type, row, meta) {
                let edit = '{{ route('task-edit', [':id']) }}';
                edit = edit.replace(':id', data);
                return `
                  <div class="text-center">

                    @if(in_array("edit",$permissions))
                    <a href="${edit}" class="me-1">
                      <i class="fa fa-pencil-alt text-success"></i>
                    </a>
                    @endif

                    @if(in_array("delete",$permissions))
                    <i class="fa fa-trash text-danger js-delete-item cursor-pointer" data-id="${data}" ></i>
                    @endif

                  </div>
                `;
              },
            },
          @endif
        ],
        drawCallback: function() {
          $(".dataTables_paginate > .pagination").addClass("pagination-rounded"), $(".dataTables_length label")
            .addClass("form-label"), $(".dataTables_filter label").addClass("form-label");
        $(".js-status-switch").on("change", function() {
            let url = "{{ route('task-status') }}";

            axios.patch(url, {
              id: $(this).data("id"),
              status: $(this).prop("checked"),
            })
        });
          $(".js-delete-item").on("click", function() {
            let id = $(this).data("id");

            Swal.fire({
              icon: "question",
              title: "Are you sure?",
              showCancelButton: 1,
              confirmButtonText: "Yes, delete it!",
              showLoaderOnConfirm: 1,
              allowOutsideClick: !1,
              preConfirm: function(n) {
                let url = '{{ route('task-delete', [':id']) }}';
                url = url.replace(':id', id);
                return axios.delete(url)
                .then((e) => {
                  Swal.fire({
                    icon: "success",
                    title: e.data.message,
                  })
                  table.draw(false);
                }).catch((e) => {
                  Swal.fire({
                    icon: "error",
                    title: e.response.data.message,
                  })
                });
              },
            })
          });
        }
      })
    });
  </script>
@endsection
