@extends('layouts.app')

@section('title', 'Create Evaluation Form')

@push('styles')
    @include('admin.partials.management-styles')
@endpush

@section('content')
    <form action="{{ route('admin.evaluation-questions.store') }}" method="POST">
        @csrf
        @include('admin.evaluation-questions._form', [
            'submitLabel' => 'Create Form',
            'panelHeading' => 'Create Evaluation Form',
        ])
    </form>
@endsection