@extends('layouts.app')

@section('content')
    <h1>{{$post->title}}</h1>
    {!!$post->safe_html_content!!}
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