@extends('shop::layouts.app')

@section('content')
    @forelse($cars as $car)
        <div class="col-md-6  col-lg-4 d-flex align-items-stretch p-4">
            <div class="car__list mb-5">
                <div class="car__list-header mb-3">{{ $car->title }}</div>
                <div class="car__list-body text-center">
                    <img class="car__list-body--img rounded img-fluid mb-4" src="https://via.placeholder.com/350x150" />
                    <p class="car__list-body--description">{{ $car->description }}</p>
                    <p class="car__list-body--tags">
                        @foreach($car->getTags() as $tag)
                            <span class="badge badge-light">
                                    <i class="fas fa-tag"></i>
                                    {{ $tag }}
                                </span>
                        @endforeach
                    </p>
                </div>
                <div class="car__list-footer text-center">
                    <form action="{{ route('shop.cart.add', $car) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-success">
                            <i class="fas fa-cart-plus"></i>
                            Add to cart
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-md-6 col-lg-4">
            <p>No Cars found!</p>
        </div>
    @endforelse
@stop
