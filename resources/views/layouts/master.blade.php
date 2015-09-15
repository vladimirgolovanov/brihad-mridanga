<!DOCTYPE html>
<html>
    <head>
        <title>Store books</title>
        <link rel="stylesheet" href="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.orange-deep_orange.min.css">
        <script src="https://storage.googleapis.com/code.getmdl.io/1.0.4/material.min.js"></script>
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <header class="mdl-layout__header">
        <div class="mdl-layout-icon"></div>
        </header>
        <div class="mdl-layout__drawer">
            <span class="mdl-layout-title">Store books</span>
            <nav class="mdl-navigation">
                <a class="mdl-navigation__link" href="{{ route('groups.index') }}">Groups</a>
                <a class="mdl-navigation__link" href="{{ route('books.index') }}">Books</a>
                <a class="mdl-navigation__link" href="{{ route('persons.index') }}">Persons</a>
            </nav>
        </div>
        <main class="mdl-layout__content">
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
        <footer class="mdl-mini-footer">
            <div class="mdl-mini-footer__right-section">
                <ul class="mdl-mini-footer--link-list">
                    <li><a href="#">Help</a></li>
                </ul>
            </div>
        </footer>
    </div>
    </body>
</html>