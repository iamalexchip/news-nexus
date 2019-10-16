@extends('layouts.group')

@section('content')

@include('block.articles.potrait', ['articles' => $articles])
@include('block.articles.landscape', ['articles' => $trendingArticles])
<div id="loadMore" class="col-sm-10 text-center" style="margin:60px">
	<button class="btn btn-primary btn-block" onclick="loadMore()">Load More</button>
</div>

@endsection


@section('scripts')
<script>

	let page = 2;

	function loadMore()
	{
		$.ajax({
		  url: "/more/home?page="+page
		}).done(function(response) {
			if(response.current_page > response.last_page){
				$( "#loadMore" ).html('<h3>Thats all folks</h3>');
				return;
		  	} 		
	  		
	  		$( ".articles-container" ).append(response.body);
			$( "#loadMore" ).appendTo( ".articles-container" );
		  	
		  	if (response.current_page == response.last_page) {
				$( "#loadMore" ).html('<h3>Thats all folks</h3>');
		  	}
		});
		page++
	}

</script>
@endsection
