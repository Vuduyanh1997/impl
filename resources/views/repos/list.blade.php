@extends('layouts.master')
@section('header')

{{-- <link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> --}}
<style type="text/css" media="screen">
    .full_name{
        font-size: 16px;
        font-weight: 700;
    }
    .left{
        float: left;
        width: 80%;
    }
    .right{
        float: right;
        width: 20%;
    }
    .right i{
        margin-top: 20px;
        float: right;
        font-size: 20px;
    }
</style>
@endsection

@section('content')
<div class="d-sm-flex align-items-center justify-content-between mb-4">
  <h1 class="h3 mb-0 text-gray-800">List Repos</h1>
</div>
<div class="table">
    <table class="table table-bordered table-hover" id="tbl_repo">
        <thead>
            <tr>
                <th style="text-align: center;">#</th>
                <th style="text-align: center;">Action</th>
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">User Clone</th>
                <th style="text-align: center;">Time Clone</th>
            </tr>
        </thead>
    </table>
</div>

@endsection

@section('footer')
    <script>
        var table = $('#tbl_repo').DataTable({
            processing: true,
            serverSide: true,
            ordering: false,
            order: [], 
            pageLength: 30,
            lengthMenu: [[30, 50, 100, 200, 500], [30, 50, 100, 200, 500]],
            ajax: {
                type: 'POST',
                url: '/get-list-repo-clone',
            },
            columns: [
                {data: 'DT_RowIndex', orderable: false, searchable: false, 'class':'text-center', 'width': '5%'},
                {data: 'action', name: 'action', orderable: false, searchable: false, 'class': 'text-center', 'width': '5%'},
                {data: 'name', name: 'name', 'width': '20%'},
                {data: 'user', name: 'user'},
                {data: 'created_at', name: 'created_at', 'class':'text-center'},
            ]
        });

        $(document).on('click', '.btn-forks', function(e){
            e.preventDefault();
            var github_id = $(this).attr('data-user-github-id');
            var forks_url = $(this).attr('data-forks');
            var id = $(this).attr('data-id');

            $.ajax({
                type: 'POST',
                url: '/forks',
                data: {
                    id: id,
                    forks_url: forks_url, 
                    github_id: github_id, 
                },
                success: function (res) {
                    // if (!res.error) {
                    //     toastr.success(res.message);
                    // } else {
                    //     toastr.error(res.message);
                    // }
                }
            });

        })
        
    </script>
@endsection