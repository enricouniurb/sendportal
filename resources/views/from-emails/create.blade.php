@extends('sendportal::layouts.app')

@section('title', __('New From Email'))

@section('heading')
    {{ __('From Emails') }}
@stop

@section('content')

    @component('sendportal::layouts.partials.card')
        @slot('cardHeader', __('Create From Email'))

        @slot('cardBody')
            <form action="{{ route('from-emails.store') }}" method="POST" class="form-horizontal">
                @csrf

                @include('from-emails.partials.form')

                <x-sendportal.submit-button :label="__('Save')" />
            </form>
        @endSlot
    @endcomponent

@stop
