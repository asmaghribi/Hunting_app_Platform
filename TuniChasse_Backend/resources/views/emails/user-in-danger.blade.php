@component('mail::message')
# Utilisateur en danger

Un utilisateur est en danger à la position suivante :
Latitude : {{ $position['latitude'] }}
Longitude : {{ $position['longitude'] }}

@component('mail::button', ['url' => ''])
Voir sur la carte
@endcomponent

Merci,<br>
{{ config('app.name') }}
@endcomponent
