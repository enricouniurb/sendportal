<form action="{{ route('from-emails.destroy', $email->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <a href="{{ route('from-emails.edit', $email->id) }}"
       class="btn btn-sm btn-light">{{ __('Edit') }}</a>
    <button type="submit" class="btn btn-sm btn-light">{{ __('Delete') }}</button>
</form>
