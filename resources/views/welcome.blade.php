@extends('layouts.app')
@section('content')
    <section class="container">
        <h1>@lang('Welcome to the Classifier App')</h1>

        <a href="{{ route('app') }}" class="btn btn-success btn-lg">@lang('Go!')</a>
    </section>
@endsection
