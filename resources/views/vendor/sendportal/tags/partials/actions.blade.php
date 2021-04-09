<form action="{{ route('sendportal.tags.destroy', $tag->id) }}" method="POST">
    @csrf
    @method('DELETE')
    @if (auth()->user()->ownsCurrentWorkspace())
    <a href="{{ route('sendportal.tags.edit', $tag->id) }}"
       class="btn btn-sm btn-light">{{ __('Edit') }}</a>
    @endif
    <button type="submit" class="btn btn-sm btn-light" @if (!auth()->user()->ownsCurrentWorkspace()) disabled @endif>{{ __('Delete') }}</button>
</form>
