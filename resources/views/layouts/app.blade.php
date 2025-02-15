<!doctype html>
<html lang="en" class="scorecard">
  <head>
    <meta charset="utf-8">

    <title>@yield('title')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="description" content="@yield('description')">
    <meta name="author" content="Police Scorecard">

    <meta name="apple-mobile-web-app-title" content="Police Scorecard">
    <meta name="application-name" content="Police Scorecard">

    <!-- Facebook META Data -->
    <meta name="facebook-domain-verification" content="s43qlqcd2jhfsaiguh0h5xfa0l7wa5" />

    <!-- Twitter META Info -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:site" content="@samswey">
    <meta property="twitter:title" content="@yield('title')">
    <meta property="twitter:description" content="@yield('description')">
    <meta property="twitter:creator" content="@samswey">
    <meta property="twitter:image:src" content="{{ asset('/images/card.jpg') }}">
    <meta property="twitter:domain" content="https://policescorecard.org">

    <!-- Open Graph protocol -->
    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:url" content="https://policescorecard.org">
    <meta property="og:image" content="{{ asset('/images/card.jpg') }}">
    <meta property="og:site_name" content="@yield('title')">
    <meta property="og:description" content="@yield('description')">

    <link href="{{ asset('/favicon.ico') }}" rel="shortcut icon">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow+Condensed:300,400,500,700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">

    <!-- Preload Resources -->
    <link rel="preload" href="/fonts/StateFace-Regular-webfont.woff" as="font" type="font/woff2" crossorigin>

    <!-- DNS Pre-Connects -->
    <link rel="preconnect" href="https://stats.g.doubleclick.net">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>

    <!-- Facebook Pixel Code -->
    <script>!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window, document,'script','https://connect.facebook.net/en_US/fbevents.js');fbq('init', '318406453226748');fbq('track', 'PageView');</script><noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=318406453226748&ev=PageView&noscript=1"/></noscript>
    <!-- End Facebook Pixel Code -->

    @yield('styles')
  </head>

  <body>
    <main class="wrapper" id="main">
        <a id="skip-nav" class="sr-only" href="#content" {!! trackData('Nav', 'Header', 'Skip to Content') !!}>Skip to Content</a>

        <!-- Page Content -->
        @yield('content')
    </main>

    @yield('modal')

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ Config::get('app.google_analytics') }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function analytics(){dataLayer.push(arguments);}
      analytics('js', new Date());
      analytics('config', '{{ Config::get("app.google_analytics") }}', { 'link_attribution': true });
    </script>

    <!-- Scripts -->
    <script src="{{ asset('/js/plugins.js') }}"></script>
    <script src="{{ mix('/js/app.js') }}"></script>

    @yield('scripts')
  </body>
</html>
