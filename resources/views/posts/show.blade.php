@extends('layouts.app')

@section('content')
    <h1>{{$post->title}}</h1>
    {!!$post->safe_html_content!!}
    <h4>{{$post->user->name}}</h4>
    @if(auth()->check())
        @if(!auth()->user()->isSubscribedTo($post))
            {!! Form::open(['route'=>['posts.subscribe',$post],'method'=>'POST']) !!}
                <button type="submit">Subscribirse al post</button>
            {!! Form::close() !!}
        @else
            {!! Form::open(['route'=>['posts.unsubscribe',$post],'method'=>'POST']) !!}
                <button type="submit">Desuscribirse del post</button>
            {!! Form::close() !!}
        @endif
    @endif




    <h4>Comentarios</h4>
    {!! Form::open(['route'=>['comment.store',$post],'method'=>'post']) !!}
        {!! Field::textarea('comment') !!}
        <button type="submit">Publicar comentario</button>
    {!! Form::close()!!}

    {{-- ultimos comentarios al principio--}}
    @foreach($post->latestComment as $comment)
        {{-- si el comentario es la respuesta del post, agregamos la clase answer si no no agregoninguna --}}
        <article class="{{$comment->answer?'answer':''}}">
            {{--@todo: agregar soporte markdown en los comentarios --}}
            {{$comment->comment}}
            {{-- si el usuario puede aceptar el comentario y el comentario ya no esta marcado como respuesta --}}
           @if(Gate::allows('accept',$comment) && !$comment->answer)
             {!! Form::open(['route'=>['comments.accept',$comment],'method'=>'post'] ) !!}
                <button type="submit">Aceptar respuesta</button>

            {!! Form::close() !!}
            @endif
        </article>



    @endforeach


@endsection