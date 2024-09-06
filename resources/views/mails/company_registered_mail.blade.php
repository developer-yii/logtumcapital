@component('mail::message')
# {{$mailData['subject']}}

{{ $mailData['message'] }}

{{ __('translation.Thanks') }},<br>
{{ config('app.name') }}
@endcomponent
