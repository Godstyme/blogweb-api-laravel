@component('mail::message')
<h1>We have received your request to reset your account password</h1>
<p>You can use the following code to recover your account:</p>

@component('mail::panel')
<h1 style="color:red;">{{ $code }}</h1>
<h1 style="color:red;">{{ $expiringTime }}</h1>
@endcomponent

<p>The allowed duration of the code is five minutes from the time the message was sent</p>
@endcomponent
