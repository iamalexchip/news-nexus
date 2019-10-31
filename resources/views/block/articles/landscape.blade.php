<!-- block.articles.landscape -->

@foreach($articles as $article)
<div class="col-sm-12 article article-landscape">
	<div class="card">
		<div class="row">
			<div class="col-sm-5">  
			  <div class="pic">
			  	<!-- {{ $article->image }} -->
			  	<img class="card-img-top" src="{{ asset('images/vegeta_landscape.jpg') }}" alt="{{ $article->title }}">
			  </div>
			</div>
			<div class="col-sm-7 text">
			  	<div class="card-body">
				  	<h5 class="card-title"><a href="{{ $article->link }}">{{ $article->title }}</a></h5>
				    <p class="card-text">
				    	<div class="row">
							<div class="col-sm-6">{{ $article->published->diffForHumans() }}</div>
				            <div class="col-sm-6"><i class="fa fa-eye"></i>{{ $article->site_clicks }}</div>
				            <div class="col-sm-12">{{ str_limit($article->summary, $limit = 220, $end = '....') }}</div>
				            <div class="col-sm-12">
				                @foreach($article->source->categories as $category)
			                	<a href="{{ $category->link }}" class="btn btn-outline-dark">
			                		{{ $category->title }}
			                	</a>
				                @endforeach
				            </div>
				        </div>
			        </p>
		  		</div>
		 	</div>
		</div>
	</div>
</div>
@endforeach

<!-- end block.articles.landscape -->