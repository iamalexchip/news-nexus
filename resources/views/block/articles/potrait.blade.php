<!-- block.articles.potrait -->

<div class="card-deck">
	@foreach($articles as $article)
	<div class="col-sm-4 article article-potrait">
		<div class="card">
		  <div class="pic">
		  	<!-- {{ $article->image }} -->
		  	<a href="{{ $article->link }}">
		  		<img class="card-img-top" src="{{ asset('images/vegeta_potrait.jpg') }}"
		  		alt="{{ $article->title }}">
		  	</a>
		  </div>
		  <div class="card-body">
		  	<h5 class="card-title"><a href="{{ $article->link }}">{{ $article->title }}</a></h5>
		    <p class="card-text">
		    	<div class="row">
					<div class="col-sm-6">{{ $article->published->diffForHumans() }}</div>
		            <div class="col-sm-6"><i class="fa fa-eye"></i>{{ $article->site_clicks }}</div>
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
	@endforeach
</div>

<!-- end block.articles.potrait -->