@extends('layouts.group')

@section('content')

@include('block.articles.potrait', ['articles' => $articles])
@include('block.articles.landscape', ['articles' => $trendingArticles])

@endsection
