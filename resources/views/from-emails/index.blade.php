@extends('sendportal::layouts.app')

@section('title', __('From Emails'))

@section('heading')
    {{ __('From Emails') }}
@endsection

@section('content')
    @component('sendportal::layouts.partials.actions')

        @slot('right')
            <a class="btn btn-primary btn-md btn-flat" href="{{ route('from-emails.create') }}">
                <i class="fa fa-plus"></i> {{ __('Add From Email') }}
            </a>
        @endslot
    @endcomponent

    <div class="card">
        <div class="card-table">
            <table class="table">
                <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Email') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($emails as $email)
                    <tr>
                        <td>
                            <a href="{{ route('from-emails.edit', $email->id) }}">
                                {{ $email->from_name }}
                            </a>
                        </td>
                        <td>{{ $email->from_email }}</td>
                        <td>
                            @include('from-emails.partials.actions')
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="100%">
                            <p class="empty-table-text">{{ __('You have not created from emails.') }}</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
