<x-mail::message>
    # Email Verification

    Thank you for signing up.
    Your six-digit code is <h4>{{ $pin }}</h4>

    <x-mail::button :url="''">
        {{-- Verify Now --}}
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
