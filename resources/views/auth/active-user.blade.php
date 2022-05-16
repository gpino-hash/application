@component('mail::message')

    # Hola, {{$name}}

    Gracias por registrarte.
    Para poder activar su cuenta, presione el boton de abajo.

    @component('mail::button', ['url' => $url])
        Verificar Cuenta
    @endcomponent

    Gracias,
    {{ config('app.name') }}
@endcomponent
