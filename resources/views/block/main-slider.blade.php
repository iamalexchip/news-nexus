<!-- block.main-slider -->
<div id="news-slider" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
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
            <img class="d-block w-100" src="{{ $article->image }}" alt="{{ $article->title }}">
            <div class="carousel-caption">
                <span>Category</span>
                <h3>{{ $article->title }}</h3>
                <p>xv</p>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!-- end block.main-slider -->