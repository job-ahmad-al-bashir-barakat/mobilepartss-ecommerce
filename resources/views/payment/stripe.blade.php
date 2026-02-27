@extends('payment.layouts.master')

@push('script')
    <script src="https://polyfill.io/v3/polyfill.min.js?version=3.52.1&features=fetch"></script>
    <script src="https://js.stripe.com/v3/"></script>
@endpush

@section('content')
    <div>
        <h1 class="text-center">{{ "Please do not refresh this page..." }}</h1>
    </div>

    <script type="text/javascript">
        var stripe = Stripe('{{$config->published_key}}');
        document.addEventListener("DOMContentLoaded", function () {
            fetch("{{ url("payment/stripe/token/?payment_id={$data->id}") }}", {
                method: "GET",
            }).then(function (response) {
                return response.json();
            }).then(function (session) {
                if (!session.id) {
                    throw new Error(session.error || "Unable to initialize Stripe checkout.");
                }
                return stripe.redirectToCheckout({sessionId: session.id});
            }).then(function (result) {
                if (result.error) {
                    alert(result.error.message);
                }
            }).catch(function (error) {
                alert(error.message || "Unable to initialize payment.");
                console.error("error:", error);
            });
        });
    </script>
@endsection
