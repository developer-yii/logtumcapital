@component('mail::message')
# {{$mailData['subject']}}

{{ $mailData['message'] }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
