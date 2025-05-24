<h1>Video List</h1>

@foreach ($videos as $video)
    <div>
        <h3>{{ $video->title }}</h3>
        <p>{{ $video->description }}</p>
        <!-- <a href="{{ $video->vimeo_link }}" target="_blank">Watch on Vimeo</a> -->
    </div>
@endforeach
