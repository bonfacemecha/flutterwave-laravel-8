<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>

<body>
    {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light"> --}}
    <nav class="navbar navbar-light navbar-expand-lg" style="background-color: #e3f2fd;">
        <div class="container">
            <a class="navbar-brand" href="#">Navbar</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="text-capitalize">Pay for services using flutterwave</h2>
        <form id="makePaymentForm">
            @csrf
            <div class="row">
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" name="name" id="name" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control" name="email" id="email" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="text" class="form-control" name="amount" id="amount" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="mb-3">
                        <label for="phone_number" class="form-label">Number</label>
                        <input type="text" class="form-control" name="phone_number" id="phone_number" required>
                    </div>
                </div>
                <div class="col-sm-6">
                    <button type="submit" class="btn btn-primary">Pay Now</button>
                </div>
            </div>


        </form>
    </div>




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
    </script>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script language="JavaScript" type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js?ver=3.6.0"></script>




    <script>
        $(function() {
            $("#makePaymentForm").submit(function(e) {
                e.preventDefault();
                var name = $("#name").val();
                var email = $("#email").val();
                var phone_number = $("#phone_number").val();
                var amount = $("#amount").val();
                //make payment
                makePayment(amount, email, phone_number, name);
            });

        })

        function makePayment(amount, email, phone_number, name) {
            FlutterwaveCheckout({
                public_key: '{{ env('FLUTTER_P_KEY') }}',
                tx_ref: "RX1_{{ substr(rand(0, time()), 0, 7) }}",
                amount,
                currency: "UGX",
                country: "UG",
                payment_options: " ",
                // redirect_url: 
                //     "#",
                // meta: {
                //     consumer_id: 23,
                //     consumer_mac: "92a3-912ba-1192a",
                // },
                customer: {
                    email,
                    phone_number,
                    name,
                },
                callback: function(data) {
                    var transaction_id = data.transaction_id;
                    //make ajax request
                    var _token = $("input[name='_token']").val();
                    $.ajax({
                        type: "POST",
                        url: "{{ URL::to('verify-payment') }}",
                        data: {
                            transaction_id,
                            _token
                        },
                        dataType: "dataType",
                        success: function(response) {
                            console.log(response);

                        }

                    });
                },
                onclose: function() {
                    // close modal
                },
                customizations: {
                    title: "BM Studio",
                    description: "Payment for items in cart",
                    logo: "https://s3-us-west-2.amazonaws.com/hp-cdn-01/uploads/orgs/flutterwave_logo.jpg?69",
                },
            });
        }
    </script>


</body>

</html>
