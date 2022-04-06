@component('mail::message')
    # Hola, {{$name}}

    Gracias por registrarte.
    Para poder activar su cuenta, presione el boton de abajo.

    @component('mail::button', ['url' => $url])
        Activar Cuenta
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
