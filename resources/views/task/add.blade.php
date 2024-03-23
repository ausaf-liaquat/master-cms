@extends('layouts.main.main')

@section('title', $isEdit ? 'Edit Task' : 'Add Task')

@section('css-plugins')
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
                <li class="breadcrumb-item"><a href="{{ route('task-index') }}">Task</a></li>
                <li class="breadcrumb-item active">{{ $isEdit ? 'Edit' : 'Add' }} Task</li>
              </ol>
            </div>
            <h4 class="page-title">Task</h4>
          </div>
        </div>
      </div>
      <!-- end page title -->

      <form action="{{ $isEdit ? route('task-update', ['id' => $task->id]) : route('task-store') }}" method="post"
        autocomplete="off" id="form">
        @if ($isEdit)
          @method('put')
        @endif
        @csrf
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col-12 text-sm-end mb-2">
                    <button type="submit" class="btn btn-primary waves-effect waves-light"><i
                        class="fe-upload-cloud me-1"></i> {{ $isEdit ? 'Update' : 'Create' }}</button>
                  </div><!-- end col-->
                </div>
              </div> <!-- end card-body-->
            </div> <!-- end card-->
          </div> <!-- end col -->
        </div>
        <div class="col-12">
          <div class="row" data-masonry='{"percentPosition": true }'>
          </div>
        </div>
      </form>
    </div> <!-- container -->
  </div> <!-- content -->
@endsection

@section('js-plugins')
  <!-- Plugin js-->
  <script src="{{ url('assets/libs/masonry/masonry.pkgd.min.js') }}"></script>
  <script src="{{ url('assets/libs/parsleyjs/parsley.min.js') }}"></script>
@endsection

@section('js-page-specific')
  <script>
    $(document).ready(function() {
      "use strict";
      let parsley = $('#form').parsley();
      $("#form").submit(function(e) {
        if (parsley.isValid()) {
          $("#form").find("button[type='submit']").addClass("disabled");
        };
      });
    });
  </script>
@endsection
