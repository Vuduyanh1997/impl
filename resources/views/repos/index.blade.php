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
  <h1 class="h3 mb-0 text-gray-800">Repos</h1>
</div>

<form method="POST" accept-charset="utf-8" id="form_search" style="width: 100%" role="form">
    @csrf
    <div class="col-md-4">
        <div class="form-group">
            <input type="text" class="form-control" name="name_search" id="name_search" placeholder="Nhập tên người dùng">
        </div>
    </div>
    <div id="btn-container" class="col-md-2">
        <button type="submit" class="btn btn-sm btn-success"><i class="fa fa-search" aria-hidden="true"></i> Tìm kiếm</button>
    </div>
</form>
<div class="col-md-12" id="data">
    
</div>

@endsection

@section('footer')
    <script>
        $('#loadMore').hide();
        var count_all = 0;
        var x = 30;
        $(document).on('submit', '#form_search', function () {
            var name_search = $('#name_search').val();
            var path = '/get-repos/'+name_search+'/30';
            $('#data').load(path, function () {
                $('#myModal').modal({show: true});
            });
            return false;
        });

        $(document).on('click', '#loadMore',function (e) {
            e.preventDefault();
            var name_search = $(this).attr('data-name');
            var path = '/get-repos/'+name_search+'/'+$(this).attr('data-next');
            $('#data').load(path, function () {
                $('#myModal').modal({show: true});
            });
            return false;
            // x = (x + 30 <= count_all) ? x + 30 : count_all;
            // $('#content-repos .list-group:lt(' + x + ')').show();
            // if(x == count_all){
            //     $('#loadMore').hide();
            // }
            // $('.show_amount span').html(x + ' results for ' + count_all + 'public repositories');
        });

        // $(document).on('submit', '#form_search', function(e){
        //     e.preventDefault();
        //     var name_search = $('#name_search').val();
        //     $.ajax({
        //         type: 'POST',
        //         url: '/get-repos',
        //         data: {
        //             name_search: name_search,
        //         },
        //         success: function (res) {
        //             if (!res.error) {
        //                 var data = res.data;
        //                 if(data.length > 0){
        //                     var txt = '';
        //                     for (var i = 0; i < data.length; i++) {
        //                         txt += `<div class="list-group">
        //                                     <div class="list-group-item">
        //                                         <div class="left">
        //                                             <span class="full_name"><a href="`+data[i]['html_url']+`" target="_blank">`+data[i]['full_name']+`</a></span><span> -- <i class="far fa-star"></i>`+data[i]['stargazers_count']+`</span>
                                                    
        //                                             <p class="list-group-item-text">Cập nhật: `+data[i]['updated_at']+`</p>
        //                                         </div>
        //                                         <div class="right">
        //                                             <a data-toggle="tooltip" title="Clone" class="clone_repo" data-id="`+data[i]['id']+`" data-name-search="`+$('#name_search').val()+`"><i class="fas fa-clone"></i></a>
        //                                         </div>
        //                                     </div>
        //                                 </div>`;
        //                     }
        //                     $('#content-repos').html(txt);
        //                     count_all = $('.list-group').length;
        //                     $('#content-repos .list-group').hide();
        //                     $('#content-repos .list-group:lt(' + x + ')').show();
        //                     if (count_all > x) {
        //                         $('#loadMore').show();
        //                     } else {
        //                         x = count_all;
        //                     }
        //                     $('.show_amount span').html(x + ' results for ' + count_all + ' public repositories');
        //                 }
        //             }
        //         }
        //     });
        // })

        // $(document).on('click', '#loadMore',function (e) {
        //     e.preventDefault();
        //     x = (x + 30 <= count_all) ? x + 30 : count_all;
        //     $('#content-repos .list-group:lt(' + x + ')').show();
        //     if(x == count_all){
        //         $('#loadMore').hide();
        //     }
        //     $('.show_amount span').html(x + ' results for ' + count_all + 'public repositories');
        // });

        $(document).on('click', '.clone_repo', function(e){
            e.preventDefault();
            var id = $(this).attr('data-id');
            var name_search = $(this).attr('data-name-search');
            $.ajax({
                type: 'POST',
                url: '/get-repos/clone',
                data: {
                    id: id,
                    name_search: name_search, 
                },
                success: function (res) {
                    if (!res.error) {
                        toastr.success(res.message);
                    } else {
                        toastr.error(res.message);
                    }
                }
            });
        })
    </script>
@endsection