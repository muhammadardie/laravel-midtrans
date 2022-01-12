@extends('layouts.app') 
@section('content')

<div class="d-flex flex-column flex-md-row justify-content-end">
    <a href="{{ route('products.create') }}" class="btn btn-success" style="margin-right:200px;margin-top:20px;margin-bottom:20px">
        New Product
    </a>
</div>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="table-responsive">
                    <table class="table table-hover table-condensed">
                        <thead class="thead-light">
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Price</th>
                            <th scope="col"></th>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>#{{ $loop->iteration }}</td>
                                    <td>{{ $product->name }}</td>
                                    <td>Rp. {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info">
                                            Edit
                                        </a>
                                        <a href="{{ route('products.destroy', $product->id) }}" class="btn btn-danger btn-delete-table">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection