<!-- block.main-slider -->
<div id="news-slider" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <!-- Carousel Thumbnails -->
        <ul class="carousel-thumbnails">
            @foreach($sliderArticles as $article)
            <li data-target="#news-slider"
                data-slide-to="{{ $loop->index }}" class="anim @if($loop->first) active @endif">
                <span>Category</span>
                <p>{{ $article->title }}</p>
            </li>
            @endforeach
        </ul>
        <!-- Carousel Items -->
        @foreach($sliderArticles as $article) 
        <div slide="{{ $loop->index }}" class="carousel-item @if($loop->first) active @endif">
            <img class="d-block w-100" src="{# $article->image #}" alt="{{ $article->summary }}">
            <div class="item-caption">
                <span>Category</span>
                <h3><a href="{{ $article->link }}">{{ $article->title }}</a></h3>
                <p>
                    <span>{{ $article->published->diffForHumans() }}</span>
                    <span><i class="fa fa-eye"></i>{{ $article->site_clicks }}</span>
                    <span>
                        <a href="{{ $article->source->link }}">{{ $article->source->title }}</a>
                    </span>
                    <span class="share-icons">
                        Share
                        <a href="#" class="fa fa-facebook"></a>
                        <a href="#" class="fa fa-twitter"></a>
                        <a href="#" class="fa fa-google"></a>
                    </span>
                </p>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- end block.main-slider -->