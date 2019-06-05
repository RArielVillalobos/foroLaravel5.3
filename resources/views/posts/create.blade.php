@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        <form method="post" action="{{route('posts.store')}}">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <input class="form-control" type="text" name="title">


                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="content"></textarea>


                            </div>

                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Publicar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
