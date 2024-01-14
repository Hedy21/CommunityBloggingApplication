@extends('admin.auth.layout.master')
@section('content')

<div class="">
    <a href="{{route('admin.article.create')}}" class="btn btn-primary">Create</a>
    <hr>
</div>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Slug</th>
            <th>Name</th>
            <th>Like Count</th>
            <th>View Count</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $d)
            <tr>
                <td>{{$d->slug}}</td>
                <td>{{$d->name}}</td>
                <td>{{$d->like_count}}</td>
                <td>{{$d->view_count}}</td>
                <td>
                    <a href="{{route('admin.article.edit',$d->slug)}}" class="btn btn-primary">Edit</a>
                    <form action="{{route('admin.article.destroy',$d->slug)}}" class="d-inline" method="POST">
                        @method('DELETE')
                        @csrf
                        <input type="submit" value="Delete" class="btn btn-danger">
                    </form>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#id{{$d->id}}">
                        View
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
{{$data->links()}}
@endsection

@section('script')
@foreach ($data as $d)
    <!-- Modal -->
<div class="modal fade" id="id{{$d->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
            <img src="{{asset('/images/'.$d->image)}}" class="img-thumnail w-100 p-3">
            @foreach ($d->tag as $tag)
            <span class="badge bg-dark text-white">
                {{$tag->name}}
            </span>
            @endforeach
            |
            @foreach ($d->programming as $p)
            <span class="badge bg-blue text-white">
                {{$p->name}}
            </span>
            @endforeach


            <div class="mt-3">
                <h3>{{$d->name}}</h3>
                {!! $d->description !!}
            </div>
        </div>
      </div>
    </div>
  </div>
@endforeach
@endsection
