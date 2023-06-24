@extends('layouts.main')

@section('title')
<title>{{ config('app.name', 'Laravel') }} | 商品管理</title>
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
            <h1>新增商品</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
              <li class="breadcrumb-item active">新增商品</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <form action="{{ route('goods_create') }}" method="post" enctype="multipart/form-data">
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
                <label for="category_id">類別</label>
                @if ($dataCategory)
                <select class="form-control" id="category_id" name="category_id">
                  <option value="-1">請選擇</option>
                  @foreach ($dataCategory as $row)
                  <option value="{{ $row->id }}" @if (old('category_id') == $row->id) selected @endif>{{ $row->category_name }}</option>
                  @endforeach
                </select>
                @endif
              </div>
              <div class="form-group">
                <label for="brand_id">品牌</label>
                @if ($dataBrand)
                <select class="form-control" id="brand_id" name="brand_id">
                  <option value="-1">請選擇</option>
                  @foreach ($dataBrand as $row)
                  <option value="{{ $row->id }}" @if (old('brand_id') == $row->id) selected @endif>{{ $row->brand_name }}</option>
                  @endforeach
                </select>
                @endif
              </div>
              <div class="form-group">
                <label for="goods_name">商品名稱</label>
                <input type="text" id="goods_name" name="goods_name" class="form-control" value="{{ old('goods_name') }}">
              </div>
              <div class="form-group">
                <label for="is_show">是否顯示</label>
                <select class="form-control" id="is_show" name="is_show">
                  <option value="-1">請選擇</option>
                  <option value="0" @if (strlen(old('is_show')) && old('is_show') == 0) selected @endif>否</option>
                  <option value="1" @if (strlen(old('is_show')) && old('is_show') == 1) selected @endif>是</option>
                </select>
              </div>
              <div class="form-group">
                <label for="goods_img">商品圖片</label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="goods_img" name="goods_img">
                  <label class="custom-file-label" for="goods_img">選擇檔案</label>
                </div>
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
          <a href="{{ route('goods') }}" class="btn btn-secondary">取消</a>
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
<!-- bs-custom-file-input -->
<script src="../../plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="{{ asset('dist/js/demo.js') }}"></script> -->
<script>
$(function () {
  bsCustomFileInput.init();
});
</script>
@endsection
