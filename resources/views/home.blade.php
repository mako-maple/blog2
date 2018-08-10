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
    @can('admin-higher')
      <admin_component 
        id="{{ Auth::user()->id }}"
        name="{{ Auth::user()->name }}"
        role="{{ Auth::user()->role }}"
        logout="{{ route('logout') }}"
      >
      </admin_component>
    @elsecan('user-higher')
      <example_component 
        id="{{ Auth::user()->id }}"
        name="{{ Auth::user()->name }}"
        role="{{ Auth::user()->role }}"
        logout="{{ route('logout') }}"
      >
      </example_component>
    @else
      <a href="/login">LOGIN</a>
    @endcan
  </div>
  <script src=" {{ mix('js/app.js') }} "></script>
</body>
</html>
