<!DOCTYPE html>
<!--
    There was once a boy
    thought his willy was broke.
    He tugged it and lugged it
    and gave it a poke.
    But all of his effort, just
    went up in smoke.

    There was once a girl
    thought her fanny was stale.
    She rubbed it and scrubbed it
    ...

    There was once a boy
    thought his willy was broke.
    Turns out all he needed, was
    one horny bloke.

    - Alex Hall, {{ date("Y") }}
-->
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @if (isset($page_title))
    <title>{{ $page_title }} | Dispatcher</title>
    @else
    <title>Dispatcher</title>
    @endif
    
    <!-- fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.1.8/semantic.min.css">
    <link href="{{ url('/app.css') }}" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="{{ url('/jquery.datetimepicker.min.css') }}"/ >

    <!-- JavaScripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="{{ url('/dist/semantic.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.24/vue.js"></script>
    <script type="text/javascript" src="{{ url('/jquery.datetimepicker.full.js') }}"></script>
</head>
<body id="app-layout">
    <script>
        function init_semantic()
            {
                $('.dropdown').dropdown();
                $('.popup').popup();
                $('.hover-popup').popup({hoverable: true});
                $('.ui.checkbox').checkbox();
                $('.ui.tags').dropdown({allowAdditions: true});
            }
    </script>

    @yield('page')

    <script>
    $('.datetimepicker').datetimepicker();
    $.datetimepicker.setLocale('en');
    init_semantic();
    </script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</body>
</html>
