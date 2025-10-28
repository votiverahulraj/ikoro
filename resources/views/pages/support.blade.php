<x-guest-layout>
    @push('styles')
        <link rel="stylesheet" href="{{ asset('frontend/assets/css/content.css') }}">
    @endpush

    <div class="container py-4 support-page-add">
        <h2 class="text-center mb-4 how-can-we-text">How can we help you?</h2>
        <form action="{{ route('ikoro.support.store') }}" method="POST" class="p-4 rounded support-form-page">
            @csrf

            <div class="mb-3">
                <x-input-label class="text-title" for="title" :value="__('Title')" />
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}">

                @if ($errors->has('title'))
                    <p class="text-danger mt-2">{{ $errors->first('title') }}</p>
                @endif
            </div>
            <div class="mb-3">
                <x-input-label class="text-title" for="msg_text" :value="__('What’s your Problem?')" />
                <textarea id="msg_text" name="msg_text" rows="5" class="form-control">{{ old('msg_text') }}</textarea>
                @if ($errors->has('msg_text'))
                    <p class="text-danger mt-2">{{ $errors->first('msg_text') }}</p>
                @endif
            </div>

            <button type="submit" class="btn btn-primary submit-btn-add">Submit</button>
            <ul class="mt-3 list-add-text">
              @foreach ($tokens as $token )
              <li>
                <a href="{{ route('ikoro.support.show',$token->id) }}" class="text-title text-decoration-none">{{ $token->title }}</a>
            </li>

              @endforeach

            </ul>
        </form>
    </div>
</x-guest-layout>
