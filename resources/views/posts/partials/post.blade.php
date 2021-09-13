{{-- when condition true, loop break --}}
    {{-- @break($key == 2) --}}
    {{-- continue just skip record when cond is meet --}}
    {{-- @continue($key == 1) --}}
    <h3><a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a></h3>

    @if ($post->comments_count)
        <p>{{ $post->comments_count }} {{ Str::plural('comment', $post->comments_count) }} </p>
    @else
        <p>No comments yet</p>
    @endif

    <div class="mb-3">
        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
        <form class="d-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}" method="POST">
            @csrf
            @method('DELETE')
            <input type="submit" value="Delete" class="btn btn-primary">
        </form>
    </div>