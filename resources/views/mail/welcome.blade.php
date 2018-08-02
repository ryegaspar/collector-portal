@component('mail::message')
## Welcome to Unifin {{ $user->first_name }},<br>

These will be your login credentials:<br>
username: {{ $user->username }}<br>
password: {{ $unencrypted_password }}<br>

Login to the Unifin portal by clicking the button below and entering your credentials:

@component('mail::button', ['url' => url('/admin')])
Unifin Portal
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent