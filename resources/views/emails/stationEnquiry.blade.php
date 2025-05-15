<x-mail::message>
# Hello {{ $data['stationName'] }},<br>
# <br>
# You have been successfully registered to Fleet XP.<br>
<h3>Name : {{$data['stationName']}}</h3>
<h3>Email : {{$data['email']}}</h3>
<h3>Phone : {{$data['phone']}}</h3>

<x-mail::button :url="$url">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
