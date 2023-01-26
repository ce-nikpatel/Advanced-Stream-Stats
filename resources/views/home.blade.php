@extends('layouts.app')
@section('content')
    <div class="container">
        <section id="loading">
            <div id="loading-content"></div>
        </section>
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session()->get('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                @if (session()->get('error'))
                    <div class="alert alert-danger">
                        {{ session()->get('error') }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        @if (!empty($subscription))
                            {{ __('Current Active Subscription') }}: <strong>${{ $subscription->price }}({!! ucwords($subscription->name) !!})</strong> </br></br>
                            <button id="my-button" class="btn btn-primary pull-right cancel-plan">
                                Cancel Subscription
                            </button>
                        @else
                            {{ __("Currently, you don't have active subscription. Please subscribe the plan from the list.") }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Available Plans
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($plans as $plan)
                                <li class="list-group-item clearfix">
                                    <div class="pull-left">
                                        <h5>{{ ucwords($plan->name) }}</h5>
                                        <h5>${{ number_format($plan->price, 2) }} <strong> [Every {{ $plan->billing_frequency }} Month(s)]</strong></h5>
                                        <h5>{{ $plan->description }}</h5>

                                        @if (empty($subscription))
                                            <a href="{{ route('plan.show', $plan->id) }}"
                                                class="btn btn-outline-dark pull-right">Choose</a>
                                        @endif

                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.cancel-plan').click(function() {
                $('#loading').addClass('loading');
                $('#loading-content').addClass('loading-content');
                $.ajax({
                    url: "{{ route('subscription.cancel') }}",
                    type: 'GET',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log(response);
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                        if (response.status == 200) {
                            location.reload();
                        }
                    }
                });
            });
        });
    </script>
@endsection
