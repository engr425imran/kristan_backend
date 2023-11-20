@component('mail::message')
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p style="text-align: center">Your password reset code is <b>{{$code}}</b></p>
    <p>If you did not request a password reset, no further action is required.</p>
@endcomponent
