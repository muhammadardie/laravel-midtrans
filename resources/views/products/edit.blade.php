@extends('layouts.app') 
@section('content')

<div class="container">
    <div class="row">
        <div class="col-12">
             <form class="form" action="{{ route('products.update', $product->id) }}" method="post">
            @csrf
            {{ method_field('PATCH') }}
              <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
              </div>
              <div class="form-group">
                <label>Price</label>
                <input type="number" name="price" class="form-control" value="{{ (int) $product->price }}" required>
              </div>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection