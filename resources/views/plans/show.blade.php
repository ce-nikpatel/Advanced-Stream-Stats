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

@section('styles')
    <style type="text/css">
        .button {
            cursor: pointer;
            font-weight: 500;
            left: 3px;
            line-height: inherit;
            position: relative;
            text-decoration: none;
            text-align: center;
            border-style: solid;
            border-width: 1px;
            border-radius: 3px;
            -webkit-appearance: none;
            -moz-appearance: none;
            display: inline-block;
        }

        .button--small {
            padding: 10px 20px;
            font-size: 0.875rem;
        }

        .button--green {
            outline: none;
            background-color: #64d18a;
            border-color: #64d18a;
            color: white;
            transition: all 200ms ease;
        }

        .button--green:hover {
            background-color: #8bdda8;
            color: white;
        }
    </style>
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
