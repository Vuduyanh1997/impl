@extends('layouts.master')

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>
<div class="row">
  <div class="col-md-12">

    <table class="table table-bordered table-hover">
      <tr>
        <th>Họ tên</th>
        <td>{{$data->login}}</td>
      </tr>
      <tr>
        <th>Tài khoản</th>
        <td>{{$data->login}}</td>
      </tr>
      <tr>
        <th>email</th>
        <td>{{$data->email}}</td>
      </tr>
      <tr>
        <th>Trang cá nhân</th>
        <td><a href="{{$data->html_url}}">{{$data->html_url}}</a></td>
      </tr>
      <tr>
        <th>Public repos</th>
        <td>{{$data->public_repos}}</td>
      </tr>
      <tr>
        <th>Ngày tạo</th>
        <td>{{$data->created_at}}</td>
      </tr>
    </table>
    
  </div>
</div>
@endsection