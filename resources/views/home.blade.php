<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Laravel5.6 + Vue.js2.5</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
  <div id="app">
    <example-component test="GET DATA: {{ $_GET['AAA'] }}"></example-component>
  </div>
  <script src=" {{ mix('js/app.js') }} "></script>
</body>
</html>
