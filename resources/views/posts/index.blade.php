@extends('layouts.app')

@section('title', 'Blog posts') 

@section('content')
    {{--  @foreach ($posts as $key => $post)
        <div class="">{{ $key }}. {{ $post['title'] }}</div>
    @endforeach  --}}

    {{-- using foreach with if --}}
    @forelse ($posts as $key => $post)
        @include('posts.partials.post')
    @empty
        No Posts Found.
    @endforelse
@endsection