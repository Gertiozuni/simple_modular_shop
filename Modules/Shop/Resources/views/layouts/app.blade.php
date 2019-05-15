<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Shop</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ mix('css/shop.css') }}">
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
</head>
<body>
<div class="container">
    <header class="header py-3">
        <div class="row flex-nowrap justify-content-between align-items-center">
            <div class="col-4">
                <a class="header-logo text-dark" href="{{ route('shop.index') }}">{{ config('shop.name') }}</a>
            </div>
            <div class="col-4 d-flex justify-content-end align-items-center">
                <a class="text-muted" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="mx-3" role="img" viewBox="0 0 24 24" focusable="false"><title>Search</title><circle cx="10.5" cy="10.5" r="7.5"/><path d="M21 21l-5.2-5.2"/></svg>
                </a>
                <a href="#" class="cart px-3">
                    <i class="fa fa-shopping-cart"></i>
                    Cart <span class="badge badge-info">{{ $itemCount }}</span>
                </a>

                @guest
                    <a class="px-3 text-muted" href="{{ route('login') }}">{{ __('Login') }}</a>
                    @if (Route::has('register'))
                        <a class="px-3 text-muted" href="{{ route('register') }}">{{ __('Register') }}</a>
                    @endif
                @else
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }} <span class="caret"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item px-3 text-muted" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endguest

                <div class="shopping-cart d-none">
                    <form action="{{ route('shop.cart.update') }}" method="POST">
                        @csrf
                        <div class="shopping-cart-header">
                            <i class="fa fa-shopping-cart cart-icon"></i><span class="badge">{{ $itemCount }}</span>
                            <div class="shopping-cart-total">
                                <span class="lighter-text">Total:</span>
                                <span class="main-color-text">${{ $cartTotal }}</span>
                            </div>
                        </div>

                        <ul class="shopping-cart-items pl-0">
                            @foreach (app('cart')->items() as $item)
                                <li class="clearfix d-flex pb-3 position-relative" style="border-bottom: 1px solid lightgray;">
                                    <div class="col-md-6">
                                        <img src="https://via.placeholder.com/150x100" class="rounded img-fluid" alt=""/>
                                    </div>
                                    <div class="col-md-6 pl-2">
                                        <button type="submit" class="close" aria-label="Close" action="{{ route('shop.cart.remove', $item) }}">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                        <div class="item-name py-2">{{ $item->name }}</div>
                                        <div class="item-price">${{ $item->total }}</div>
                                        <div class="d-flex">
                                            <label for="{{ $item->id }}" class="col-form-label col-form-label-sm pr-3">Qty:</label>
                                            <input type="number" min="0" name="qty[{{ $item->id }}]" class="form-control form-control-sm" id="{{ $item->id }}" value="{{ $item->quantity }}">
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if(app('cart')->getCart())
                            <a href="{{ route('shop.cart.destroy') }}" class="btn btn-sm btn-outline-success float-left">
                                Order Now
                            </a>

                            <button type="submit" class="btn btn-sm btn-outline-primary float-right">
                                Update Cart
                            </button>
                        @endif
                    </form>
                </div>

                <form action="" name="delete-cart-item" id="delete-cart-item" method="POST">
                    @csrf
                </form>

            </div>
        </div>
    </header>

    <div class="nav-scroller py-1 mb-2">
        <nav class="nav d-flex justify-content-between">
            @foreach($tags as $tag)
                <a class="p-2 text-muted" href="{{ route('shop.index', compact('tag')) }}">{{ ucfirst($tag) }}</a>
            @endforeach
        </nav>
    </div>

    <div class="jumbotron p-4 p-md-5 text-white rounded bg-dark">
        <div class="col-md-6 px-0">
            <h1 class="display-4 font-italic">Welcome to the {{ config('shop.name') }} international website.</h1>
            <p class="lead my-3">
                Quis hendrerit dolor magna eget est lorem ipsum dolor sit.
                Turpis massa tincidunt dui ut. In nibh mauris cursus mattis molestie a iaculis.
                Quis varius quam quisque id diam vel quam elementum pulvinar.
                Sed augue lacus viverra vitae congue eu consequat.
            </p>
        </div>
    </div>
</div>

<main role="main" class="container">
    <div class="row">
        @yield('content')
    </div>
</main>

<footer class="footer">
    <p>{{ config('shop.name') }}</p>
    <p><a href="#">Back to top</a></p>
</footer>

<script src="{{ mix('js/shop.js') }}"></script>
<script type="application/javascript">
    $(document).ready(function() {
        $(document).on('click', '.close', function(e) {
            e.preventDefault();
            $('#delete-cart-item').attr('action', $(this).attr('action'));
            $('#delete-cart-item').submit();
            console.log($(this).attr('action'))
        })
    })
</script>

</body>
</html>
