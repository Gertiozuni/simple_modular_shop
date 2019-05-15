@extends('admin::layouts.app')

@section('content')
    <div class="row ml-1 my-4">
        <h1>Cars List</h1>
        <div class="ml-auto mr-3">
            <a href="{{ route('admin.cars.create') }}" class="btn btn-outline-secondary">
                <font-awesome-icon icon="plus"></font-awesome-icon>
                Create
            </a>
        </div>
    </div>

    <div class="row">
        @forelse($cars as $car)
            <div class="col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="car__list mb-5">
                    <div class="car__list-header mb-3">{{ $car->title }}</div>
                    <div class="car__list-body text-center">
                        <img class="car__list-body--img rounded img-fluid mb-4" src="https://via.placeholder.com/350x150" />
                        <p class="car__list-body--description">{{ $car->description }}</p>
                        <p class="car__list-body--tags">
                            @foreach($car->getTags() as $tag)
                                <span class="badge badge-light">
                                    <font-awesome-icon icon="tag"></font-awesome-icon>
                                    {{ $tag }}
                                </span>
                            @endforeach
                        </p>
                    </div>
                    <div class="car__list-footer text-center">
                        <form action="{{ route('admin.cars.toggle', $car) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-{{ $car->active ? 'warning' : 'success' }}">
                                {{ $car->active ? 'In Activate': 'Activate'}}
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
    </div>

    <div class="row">
        <div class="col-md-12">
            {{ $cars->links() }}
        </div>
    </div>
@stop
