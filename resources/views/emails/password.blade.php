<x-mail::message>
    # Reset Password

    Your six-digit PIN is <h4>{{ $pin }}</h4>

    <p>
        Please do not share your One Time Pin With Anyone. You made a request to reset your password.
        Please discard if this wasn't you.
    </p>

    <x-mail::button :url="''">
        Button Text
    </x-mail::button>

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
