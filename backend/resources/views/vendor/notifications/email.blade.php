<x-mail::message>

{{-- Saludo --}}
# ¡Hola! 👋

{{-- Introducción --}}
Te hemos registrado en **{{ config('app.name') }}**. Para activar tu cuenta y comenzar a usarla, por favor verifica tu correo electrónico haciendo clic en el botón de abajo.

{{-- Botón de acción --}}
<?php
    $color = 'primary';
?>
<x-mail::button :url="$actionUrl" :color="$color">
Activar cuenta
</x-mail::button>

{{-- Outro --}}
Si no esperabas este correo, puedes ignorarlo sin problema.

{{-- Firma --}}
Con entusiasmo,<br>
**El equipo de {{ config('app.name') }}** 🚀

{{-- Subcopy con URL alternativa --}}
<x-slot:subcopy>
Si tienes problemas para hacer clic en el botón "**Activar cuenta**", copia y pega este enlace en tu navegador:

<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>

</x-mail::message>
