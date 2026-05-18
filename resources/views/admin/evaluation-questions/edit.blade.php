@extends('layouts.app')

@section('title', 'Edit Evaluation Form')

@push('styles')
    @include('admin.partials.management-styles')
@endpush

@section('content')
    <form action="{{ route('admin.evaluation-questions.update', $question) }}" method="POST">
        @csrf
        @method('PUT')
        @include('admin.evaluation-questions._form', [
            'submitLabel' => 'Update Form',
            'panelHeading' => 'Edit Evaluation Form',
        ])
    </form>
@endsection