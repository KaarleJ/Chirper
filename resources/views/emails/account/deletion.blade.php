@component('mail::message')
# Account Deletion Confirmation

Click the button below to confirm the deletion of your account. This link will expire in 30 minutes.

@component('mail::button', ['url' => $deletionUrl])
Confirm Account Deletion
@endcomponent

If you did not request this, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent