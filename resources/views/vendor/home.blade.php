<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Your Store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<form id="vendor-logout-form" action="{{ route('vendor.logout') }}" method="POST" class="d-none">
    @csrf
</form>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4"
                     style="background: linear-gradient(135deg, #007bff, #0056b3);">
                    <h4 class="mb-0 fw-bold">Your Store</h4>
                </div>
                <div class="card-body text-center p-5">

                

                    <h3 class="mb-4 fw-bold text-dark">Welcome to Your Store</h3>

                    @if($vendor)
                        <div class="vendor-info p-4 rounded shadow-sm"
                             style="background: #f8f9fa; border-left: 5px solid #007bff;">
                            @if($vendor->image)
                                <img src="{{ asset('storage/' . $vendor->image) }}"
                                     alt="Store Image"
                                     class="rounded-circle shadow border border-3 border-primary"
                                     style="max-width: 120px; height: 120px; object-fit: cover;">
                            @endif
                            <h4 class="mt-3 text-dark fw-bold">{{ $vendor->name }}</h4>
                            <p class="text-muted px-3">{{ $vendor->description }}</p>
                        </div>
                    @else
                        <p class="text-muted mt-4">You are not registered as a vendor.</p>
                        <a href="{{ route('vendor.register') }}" class="btn btn-outline-primary mt-3 px-4 py-2 shadow-sm">
                            Register as Vendor
                        </a>
                    @endif

                  
                </div>
                <div class="card-footer text-center bg-light py-4">
                    <button type="button" class="btn btn-danger px-5 py-2 rounded-pill shadow"
                       onclick="event.preventDefault(); document.getElementById('vendor-logout-form').submit();">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
    @if($vendor)
    @include('products.product', ['vendor' => $vendor, 'products' => $products])
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@else
    <p>No vendor found. Please ensure your account is set up correctly.</p>
@endif

</div>

<script>
    // Example: Show success message dynamically (Laravel Session)
    document.addEventListener("DOMContentLoaded", function () {
        var statusMessage = document.getElementById("status-message");
        if (statusMessage && statusMessage.innerText.trim() !== "") {
            statusMessage.style.display = "block";
        }
    });
</script>

</body>
</html>