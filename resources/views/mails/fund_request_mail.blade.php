@component('mail::message')
# {{$mailData['subject']}}

@if(isset($mailData['ioweyou_expiry_check']) && $mailData['ioweyou_expiry_check'] == true)
    @component('mail::panel')
        {!! $mailData['message'] !!}
    @endcomponent
@else
    {{ $mailData['message'] }}
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent
