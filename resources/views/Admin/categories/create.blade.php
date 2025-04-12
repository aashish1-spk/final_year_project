@extends('front.layouts.app')

@section('main')
    <section class="section-5 bg-2">
        <div class="container py-5">
            <div class="row">
                <div class="col">
                    <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a></li>
                            <li class="breadcrumb-item active">Add</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    @include('admin.sidebar')
                </div>
                <div class="col-lg-9">
                    @include('front.message')



                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form">
                            <form action="{{ route('categories.store') }}" method="POST">
                                @csrf
                                <div class="card-body p-4">
                                    <h3 class="fs-4 mb-1">Add Category</h3>

                                    <div class="mb-4">
                                        <label for="name" class="mb-2">Category Name*</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="status" class="mb-2">Status*</label>
                                        <select name="status" id="status" class="form-control" required>
                                            <option value="">Select status</option>
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card-footer p-4">
                                    <button type="submit" class="btn btn-success">Save</button>
                                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
