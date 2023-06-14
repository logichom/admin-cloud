@extends('layouts.main')

@section('title')
<title>{{ config('app.name', 'Laravel') }} | 權限管理</title>
@endsection

@section('css')
<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
<!-- Theme style -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
@endsection

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>新增權限</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
              <li class="breadcrumb-item active">新增權限</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form action="{{ route('permission_create') }}" method="post">
      @csrf
      <div class="row">
        <div class="col-12">
          <div class="card card-primary">
            <div class="card-header">
              <!-- <h3 class="card-title">General</h3> -->
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="form-group">
                <label for="user_id">帳號</label>
                @if ($dataUser)
                  <select class="form-control" id="user_id" name="user_id">
                    @foreach ($dataUser as $row)
                    <option value="{{ $row->id }}" @if (old('user_id') == $row->id) selected @endif>{{ $row->name }}</option>
                    @endforeach
                  </select>
                @endif
              </div>
              <div class="form-group">
                <label for="sidebar_menu_id">目錄</label>
                @if ($dataSidebarMenu)
                  <select class="form-control" id="sidebar_menu_id" name="sidebar_menu_id">
                    @foreach ($dataSidebarMenu as $row)
                    <option value="{{ $row->id }}" @if (old('sidebar_menu_id') == $row->id) selected @endif>{{ $row->title }}</option>
                    @endforeach
                  </select>
                @endif
              </div>
              @if ($errors->any())
                <h4><font style="color: red">{{ $errors->first() }}</font></h4>
              @endif
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <a href="{{ route('permission') }}" class="btn btn-secondary">取消</a>
          <input type="submit" value="新增" class="btn btn-success float-right">
        </div>
      </div>
      </form>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endsection

@section('footer')
<footer class="main-footer">
  <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> 3.2.0
  </div>
</footer>
@endsection

@section('js')
<!-- jQuery -->
<script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{ asset('dist/js/demo.js') }}"></script> -->
@endsection
