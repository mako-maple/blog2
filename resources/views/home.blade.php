<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ env('MIX_APP_NAME') }}</title>

  <script src="https://cdn.polyfill.io/v2/polyfill.min.js"></script>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body>
  <div id="app">
    @can('admin-higher')
      <admin_component logout="{{ route('logout') }}">
      </admin_component>
    @elsecan('user-higher')
      <user_component logout="{{ route('logout') }}">
      </user_component>
    @elsecan('non-agree')
      @component('agree')
      @endcomponent
    @else
      @yield('content')
    @endcan
  </div>
  <script src=" {{ mix('js/manifest.js') }} "></script>
  <script src=" {{ mix('js/vendor.js') }} "></script>
  <script src=" {{ mix('js/app.js') }} "></script>
</body>
</html>
