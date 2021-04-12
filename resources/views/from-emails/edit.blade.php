@extends('sendportal::layouts.app')

@section('title', __("Edit From Email"))

@section('heading')
    {{ __('From Emails') }}
@stop

@section('content')

    @component('sendportal::layouts.partials.card')
        @slot('cardHeader', __('Edit From Email'))

        @slot('cardBody')
            <form action="{{ route('from-emails.update', $email->id) }}" method="POST" class="form-horizontal">
                @csrf
                @method('PUT')

                @include('from-emails.partials.form')

                <x-sendportal.submit-button :label="__('Save')" />
            </form>
        @endSlot
    @endcomponent

@stop
