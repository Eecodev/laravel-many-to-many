@extends('layouts.app')
@section('content')
    <section class="container">
        <h1>{{$project->title}}</h1>
        <p>{{$project->description}}</p>
        <span>{{$project->category ? $project->category->name : 'Uncategorised'}}</span>
    </section>
@endsection

