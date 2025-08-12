@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Request Submitted Successfully</div>

                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h3>Thank you for your request!</h3>
                    <p class="lead">Your reference number is:</p>
                    <div class="alert alert-primary fs-3">
                        {{ $reference }}
                    </div>
                    <p>We've sent this reference number to your email. You can use it to track your request.</p>
                    <a href="{{ route('track.form') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Track Your Request
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection