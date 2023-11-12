<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Wonde API</title>
        @vite('resources/js/app.js')
    </head>
    <body style="margin:0">
    <div id="app"></div>
    <noscript>
      <section>
        <div>
            <h1>Warning</h1>
            <p>
                This application requires javascript to work, you either have it disabled or it is not supported on this device.
            </p>
        </div>
      </section>
    </noscript>
    </body>
</html>
