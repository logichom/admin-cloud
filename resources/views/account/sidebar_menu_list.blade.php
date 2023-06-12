@extends('layouts.main')

@section('title')
<title>{{ config('app.name', 'Laravel') }} | 目錄管理</title>
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
            <h1>目錄管理</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/home') }}">Home</a></li>
              <li class="breadcrumb-item active">目錄管理</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <form action="{{ route('sidebar_menu_search') }}" method="post">
      @csrf
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">搜尋條件</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <!-- <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button> -->
          </div>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="name">新增者名稱</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="請輸入名稱" value="{{ request()->name }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">新增者email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="請輸入帳號" value="{{ request()->email }}">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="title">title</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="請輸入title" value="{{ request()->title }}">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="category_name">category_name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" placeholder="請輸入類別" value="{{ request()->category_name }}">
              </div>
            </div>
          </div>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">查詢</button>
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->
      </form>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">操作</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-primary" onclick="window.location='{{ route("sidebar_menu_create") }}'">新增</button>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">共{{ $total }}筆</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
              <table class="table table-bordered table-hover text-nowrap">
                <thead>
                  <tr>
                    <th style="width: 10px">id</th>
                    <th>title</th>
                    <th>category_name</th>
                    <th>seq</th>
                    <th>created_by_user_id</th>
                    <th>created_at</th>
                    <th>updated_at</th>
                    <th>功能</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($data)
                    @foreach ($data as $row)
                      <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->title }}</td>
                        <td>{{ $row->category_name }}</td>
                        <td>{{ $row->seq }}</td>
                        <td>{{ $row->created_by_user_id }}</td>
                        <td>{{ $row->created_at }}</td>
                        <td>{{ $row->updated_at }}</td>
                        <td>
                            <button type="button" class="btn btn-block btn-success" onclick="menu_edit({{ $row->id }})">編輯</button>
                            <button type="button" class="btn btn-block btn-danger" onclick="menu_delete({{ $row->id }})">刪除</button>
                        </td>
                      </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="8">查無資料</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
              <!-- 超過每頁數量才會有分頁 -->
              @if ($data)
                {{ $data->links() }}
              @endif
            </div>
          </div>
        </div>
      </div>
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
<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  function menu_edit(id) {
    if (!id) {
      alert('參數錯誤');
      return false;
    }
    
    location.href = '/sidebar_menu_update/' + id;
  }

  function menu_delete(id) {
    if (window.confirm('確定要刪除此目錄?')) {
      $.ajax({
        type: "POST",
        url: "/sidebar_menu_delete",
        dataType: "json",
        data: {
          'id': id,
        },
        success: function (data) {
          if (data.code == 200) {
            alert("刪除成功");
            location.reload();
          } else {
            alert(data.msg);
          }      
        }
      });
    }
  }
</script>
@endsection
