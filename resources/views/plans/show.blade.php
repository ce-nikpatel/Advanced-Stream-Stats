@extends('layouts.app')

@section('content')
    <div class="container">
        <section id="loading">
            <div id="loading-content"></div>
        </section>
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ ucwords($plan->name) }}</div>
                    <div class="card-body">
                        <form method="post" action="{{ route('subscription.create') }}">
                            @csrf
                            <div id="dropin-container"></div>
                            <hr />
                            <input type="hidden" name="plan" value="{{ $plan->braintree_plan }}" />
                            <button type="submit" class="btn btn-outline-dark d-none" id="payment-button">Pay</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://js.braintreegateway.com/js/braintree-2.32.1.min.js"></script>
    <script>
        $(document).ajaxStart(function() {
            $('#loading').addClass('loading');
            $('#loading-content').addClass('loading-content');
        });
        jQuery.ajax({
                url: "{{ route('token') }}",
            })
            .done(function(res) {
                braintree.setup(res.data.token, 'dropin', {
                    container: 'dropin-container',
                    onReady: function() {
                        jQuery('#payment-button').removeClass('d-none')
                        $('#loading').removeClass('loading');
                        $('#loading-content').removeClass('loading-content');
                    }
                });
            });
    </script>
@endsection
