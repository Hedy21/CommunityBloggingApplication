@extends('admin.auth.layout.master')
@section('content')

<div class="">
    <a href="{{route('admin.tag.index')}}" class="btn btn-primary">All Tags</a>
</div>
<form action="{{route('admin.tag.update',$data->slug)}}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="">Name</label>
        <input type="text"  value="{{$data->name}}"name="name" class="form-control">
    </div>
    <input type="submit" class="btn btn-dark" value="Edit">
</form>
@endsection
