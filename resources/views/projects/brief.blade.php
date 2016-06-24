@extends('app')

@section('title')
    Project Brief
@endsection

@section('content')

    {!! Form::open(['url' => '#', 'method' => 'post', 'id' => 'brief_form', 'class' => 'form-horizontal']) !!}

    <div class="row">
        <div class="col-sm-10">
            <h2 style="margin:0"><img src="/images/projects.png"> Project Brief</h2>
        </div>
    </div>

    <hr>

    @include('partials.projects_menu')

    @for($i = 1; $i <= 10; $i++)
        <div class="col-md-12">
            <table class="table">
                <tr class="success" data-toggle="collapse" data-target="#tab{{ $i }}">
                    <th>Tab {{ $i }}:</th>
                </tr>
                <tr class="collapse" id="tab{{ $i }}">
                    <td>Collapsible /  Expandable Area</td>
                </tr>
            </table>
        </div>
    @endfor

    {!! Form::close() !!}

@endsection