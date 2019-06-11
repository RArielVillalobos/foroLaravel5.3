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

    {{-- ultimos comentarios al principio--}}
    @foreach($post->latestComment as $comment)
        {{-- si el comentario es la respuesta del post, agregamos la clase answer si no no agregoninguna --}}
        <article class="{{$comment->answer?'answer':''}}">
            {{$comment->comment}}
            {!! Form::open(['route'=>['comments.accept',$comment],'method'=>'post'] ) !!}
            <button type="submit">Aceptar respuesta</button>

            {!! Form::close() !!}
        </article>



    @endforeach


@endsection