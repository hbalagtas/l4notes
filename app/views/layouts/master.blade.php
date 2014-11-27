<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title') - TBird Notes</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">

    <style>
	    body {
	      padding-top: 70px;
	      padding-bottom: 30px;
	    }

	    .theme-dropdown .dropdown-menu {
	      position: static;
	      display: block;
	      margin-bottom: 20px;
	    }

	    .theme-showcase > p > .btn {
	      margin: 5px 0;
	    }

	    .theme-showcase .navbar .container {
	      width: auto;
	    }

      .hgap {
        padding-top: 5px;
      }
    </style>
  </head>
  <body role="document">
    <!-- Fixed navbar -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
          <div class="container">

            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>

              <a class="navbar-brand" href="{{route('homepage')}}"><i class="fa fa-book"></i> TBird Notes</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                <li class="active">
                    <a href="{{route('homepage')}}"><i class="fa fa-home"></i> Home</a>
                </li>
                @if (Auth::check())
                <li>
                  <a href="{{route('note.create')}}"><i class="fa fa-plus-circle"></i> Create Note</a>
                </li>
                @endif
                <li>{{link_to_route('about', 'About')}}</li>
              </ul>

              @if (Auth::check())
              <div class="nav navbar-nav navbar-right">
                <img src="{{Auth::user()->picture}}" class="img-circle" height="52px">
              </div>
              @endif

              <ul class="nav navbar-nav navbar-right">
                @if ( Auth::check() )
                  <li>                    
                    <a href="{{route('logout')}}">Sign Out</a></li>
                @else
                  <li><a href="{{route('login')}}"><i class="fa fa-sign-in"></i> Sign In</a></li>
                @endif
              </ul>

              {{ Form::open(['route'=>['homepage'], 'method' => 'get','class'=>'navbar-form navbar-right'])}}
                <div class="controls">
                  <input name="searchterm" type="text" class="form-control input-xlarge search-query" placeholder="Search..." value="{{(Input::has('searchterm')?Input::get('searchterm'):'')}}">  
                </div>                
              {{ Form::close() }}

            </div><!--/.nav-collapse -->
          </div>
        </nav>

    <div class="container">
      @if (Session::has('message'))
        <div class="alert alert-info" role="alert" id="system-alert-info">
            <p><span class="glyphicon glyphicon-flash"></span>{{ Session::get('message') }}</p>
        </div>                  
      @endif
      
      @if ($errors->any())
        <div class="alert alert-warning" role="alert" id="system-alert-warning">
          <ul>
          {{ implode('', $errors->all('<li class="error">:message</li>')) }}
          </ul>
        </div>
      @endif
    </div>

    @yield('content')

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>

    <!-- Piwik -->
    <script type="text/javascript">
      var _paq = _paq || [];
      _paq.push(['trackPageView']);
      _paq.push(['enableLinkTracking']);
      (function() {
        var u="//hbalagtas.linuxd.org/piwik/";
        _paq.push(['setTrackerUrl', u+'piwik.php']);
        _paq.push(['setSiteId', 1]);
        var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
        g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
      })();
    </script>
    <noscript><p><img src="//hbalagtas.linuxd.org/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
    <!-- End Piwik Code -->
  </body>
</html>