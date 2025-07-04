<x-mail::message>
{{ __('You have been invited to join the :team team!', ['team' => $invitation->team->name]) }}

{{ __('If you do not have an account, you may create one by clicking the button below. After creating an account, you may click the invitation acceptance button in this email to accept the team invitation:') }}

<x-mail::button url="{{ route('register') }}">
{{ __('Create Account') }}
</x-mail::button>

{{ __('If you already have an account, you may accept this invitation by clicking the button below:') }}

<x-mail::button url="{{ $acceptUrl }}">
{{ __('Accept Invitation') }}
</x-mail::button>

{{ __('If you did not expect to receive an invitation to this team, you may discard this email.') }}

{{ config('app.name') }}
</x-mail::message>
