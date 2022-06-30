@if (session('status') == 'verification-link-sent')
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
    </div>
@endif

<div class="mt-4 flex items-center justify-between">
    @if ($errors->any())
        <ul class="list-group">
            @foreach ($errors->all() as $error)
                <li class="list-group-item list-group-item-danger"> {{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Resend Verification Email</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Log Out</button>
    </form>
</div>
