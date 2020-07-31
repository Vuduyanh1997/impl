<div class="show_amount" style="margin-bottom: 10px;">
    <span style="text-align: right; float: right;">{{$count}} results for {{$count_all}} public repositories</span>
</div>
<br><br>
<div id="content-repos">
     @foreach ($datas as $key => $data)
     	<div class="list-group">
        	<div class="list-group-item">
        	    <div class="left">
        	        <span class="full_name"><a href="{{$data['html_url']}}" target="_blank">{{$data['full_name']}}</a></span><span> -- <i class="far fa-star"></i>{{$data['stargazers_count']}}</span>
                                                    
        	        <p class="list-group-item-text">Cập nhật: {{$data['updated_at']}}</p>
        	    </div>
        	    <div class="right">
        	        <a data-toggle="tooltip" title="Clone" class="clone_repo" data-id="{{$data['id']}}" data-name-search="{{$name}}"><i class="fas fa-clone"></i></a>
        	    </div>
        	</div>
        </div>
     @endforeach
</div>
@if ($show == 0)
	<div id="load" style="margin-bottom: 30px;">
	    <center><a href="" id="loadMore" data-name='{{$name}}' data-next="{{$next}}">Load more</a></center>
	</div>
@endif