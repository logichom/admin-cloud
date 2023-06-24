@extends('layouts.main')

@section('title')
<title>{{ config('app.name', 'Laravel') }} | 品牌管理</title>
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
            <h1>修改品牌</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
              <li class="breadcrumb-item active">修改品牌</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form action="{{ route('brand_update') }}" method="post">
      @csrf
      @method('PUT')
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
                <label for="category_id">類別</label>
                @if ($dataCategory)
                <select class="form-control" id="category_id" name="category_id">
                  <option value="-1">請選擇</option>
                  @foreach ($dataCategory as $row)
                  <option value="{{ $row->id }}" @if ($data->category_id == $row->id) selected @endif>{{ $row->category_name }}</option>
                  @endforeach
                </select>
                @endif
              </div>
              <div class="form-group">
                <label for="brand_name">品牌名稱</label>
                <input type="text" id="brand_name" name="brand_name" class="form-control" value="{{ $data->brand_name }}">
              </div>
              <div class="form-group">
              <label for="is_show">是否顯示</label>
                <select class="form-control" id="is_show" name="is_show">
                  <option value="-1">請選擇</option>
                  <option value="0" @if ($data['is_show'] == 0) selected @endif>否</option>
                  <option value="1" @if ($data['is_show'] == 1) selected @endif>是</option>
                </select>
              </div>
              @if ($errors->any())
                <h4><font style="color: red">{{ $errors->first() }}</font></h4>
              @endif
              <input type="hidden" id="id" name="id" value="{{ $data->id }}">
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
      </div>
      <div class="row">
        <div class="col-12">
          <a href="{{ route('brand') }}" class="btn btn-secondary">取消</a>
          <input type="submit" value="修改" class="btn btn-success float-right">
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
