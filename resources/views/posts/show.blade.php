@extends('layouts.app')

@section('content')
    <h1>{{$post->title}}</h1>
    <h2>{{$post->content}}</h2>
    <h4>{{$post->user->name}}</h4>

    <h4>Comentarios</h4>
    {!! Form::open(['route'=>['comment.store',$post],'method'=>'post']) !!}
        {!! Field::textarea('comment') !!}
        <button type="submit">Publicar comentario</button>
    {!! Form::close()!!}


@endsection