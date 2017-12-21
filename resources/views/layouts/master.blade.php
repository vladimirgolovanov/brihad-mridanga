<!DOCTYPE html>
<html>
    <head>
        <title>Brihad Mridanga</title>
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="stylesheet" href="/static/material.min.css">
        <script src="/static/material.min.js"></script>
        <link rel="stylesheet" href="/static/material-icons.css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="/static/jquery-2.1.4.min.js"></script>
        <script src="/static/functions.js"></script>
        <link rel="stylesheet" href="/static/style.css">
        <style>
        .layout-transparent {
          background: url('../assets/demos/transparent.jpg') center / cover;
        }
        .layout-transparent .mdl-layout__header,
        .layout-transparent .mdl-layout__drawer-button {
          /* This background is dark, so we set text to white. Use 87% black instead if
             your background is light. */
          color: white;
        }
        </style>
    </head>
    <body>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Brihad Mridanga</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="/operation/51/make">Бх. Иван Маркин</a>
                <a class="mdl-navigation__link" href="/operation/12/make">Бх. Олег Тоболин</a>
                <a class="mdl-navigation__link" href="/operation/9/make">Нандалала дас</a>
                <a class="mdl-navigation__link" href="/operationoperation/21/make">Варадеша дас</a>
                <a class="mdl-navigation__link" href="/operation/231/make">Джулан Прия дд</a>
                <a class="mdl-navigation__link" href="/operation/201/make">Диана Дреева</a>
                <a class="mdl-navigation__link" href="/operation/214/make">Марианна Надина</a>
                <a class="mdl-navigation__link" href="/operation/230/make">Швета Двипа Радха дд</a>
                <a class="mdl-navigation__link" href="/operation/32/make">БВ ШКГд Валентина</a>
                <a class="mdl-navigation__link" href="/operation/63/make">БВ ШКГд Лила Киртана дд</a>
                <a class="mdl-navigation__link" href="/operation/33/make">БВ ШКГд Нила Радхика дд</a>
            @if(Auth::check())
                <a class="mdl-navigation__link" href="{{ route('books.index') }}">Books</a>
                <a class="mdl-navigation__link" href="{{ route('bookgroups.index') }}">Book Groups</a>
                <a class="mdl-navigation__link" href="{{ route('persons.index') }}">Persons</a>
                <a class="mdl-navigation__link" href="{{ route('report') }}">Reports</a>
                <a class="mdl-navigation__link" href="{{ route('auth.logout') }}">Logout</a>
            @else
                <a class="mdl-navigation__link" href="{{ route('auth.login') }}">Login</a>
            @endif
            </nav>
        </div>
        <main class="mdl-layout__content">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row"></div>
            </header>
            <div class="mdl-grid">
                <div class="mdl-cell mdl-cell--1-col">
                </div>
                <div class="mdl-cell mdl-cell--8-col">
                @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        {{ Session::get('flash_message') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                @yield('content')
                </div>
            </div>
        </main>
<!--         <footer class="mdl-mini-footer">
            <div class="mdl-mini-footer__right-section">
                <ul class="mdl-mini-footer--link-list">
                    <li><a href="#">Help</a></li>
                </ul>
            </div>
        </footer> -->
    </div>
    </body>
</html>
