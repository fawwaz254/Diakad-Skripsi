<form method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <h1>Upload</h1>
    <label>
        Upload a file<br>
        <input type="file" name="file" />
    </label>

    <p><button>Submit</button></p>
</form>

<h1>Existing Files</h1>

<ul>
@forelse($files as $file)
    <li><a href="{{ Storage::disk('spaces')->url($file) }}"><img style="height:3rem; width:auto;" src="{{ Storage::disk('spaces')->url($file) }}"></a> | <a href="{{ url('delete?id='.$file) }}">Delete</a></li>
    <!-- <li><a href="{{ Storage::disk('spaces')->url($file) }}">{{ $file }}</a></li> -->
@empty
    <li><em>No files to display.</em></li>
@endforelse
</ul>