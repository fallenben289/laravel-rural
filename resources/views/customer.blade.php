<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $vendor->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-card {
            transition: all 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .cart-badge {
            position: absolute;
            top: -5px;
            right: -5px;
        }
    </style>
</head>
<body>
    <!-- Simple Header -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">{{ $vendor->name }}</a>
            <div class="ms-auto position-relative">
                <button class="btn btn-light" id="cartButton">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="badge rounded-pill bg-danger cart-badge">0</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 product-card">
                    <div class="bg-light p-4 text-center" style="height: 200px;">
                        @if($product->image)
                            <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid h-100" alt="{{ $product->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center h-100">
                                <i class="fas fa-box-open fa-4x text-secondary"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <h5>{{ $product->name }}</h5>
                        <p class="text-muted small">{{ $product->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="text-primary mb-0">${{ number_format($product->price, 2) }}</h5>
                            <span class="badge bg-success">{{ $product->quantity }} in stock</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0">
                        <button class="btn btn-primary w-100 add-to-cart" 
                                data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-price="{{ $product->price }}">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- WhatsApp Order Modal -->
    <div class="modal fade" id="whatsappModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Complete Your Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="customerInfoForm">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="customerName" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="customerPhone" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Delivery Address</label>
                            <textarea class="form-control" id="customerAddress" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Your Order:</label>
                            <div id="orderSummary" class="border p-2 mb-3"></div>
                            <h5>Total: <span id="orderTotal">$0.00</span></h5>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="sendWhatsappBtn">
                        <i class="fab fa-whatsapp"></i> Send Order via WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = JSON.parse(sessionStorage.getItem('cart')) || [];
            const whatsappNumber = "{{ $vendor->phone }}"; // Vendor's WhatsApp number
            updateCartBadge();

            document.querySelectorAll('.add-to-cart').forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-id');
                    const productName = this.getAttribute('data-name');
                    const productPrice = parseFloat(this.getAttribute('data-price'));

                    addToCart(productId, productName, productPrice);
                });
            });

            document.getElementById('cartButton').addEventListener('click', function() {
                showWhatsappModal();
            });

            document.getElementById('sendWhatsappBtn').addEventListener('click', function() {
                sendOrderViaWhatsapp();
            });

            function addToCart(id, name, price) {
                const existingItem = cart.find(item => item.id === id);

                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({ id, name, price, quantity: 1 });
                }

                sessionStorage.setItem('cart', JSON.stringify(cart));
                updateCartBadge();
            }

            function updateCartBadge() {
                const count = cart.reduce((total, item) => total + item.quantity, 0);
                document.querySelector('.cart-badge').textContent = count;
            }

            function showWhatsappModal() {
                const orderSummary = document.getElementById('orderSummary');
                const orderTotal = document.getElementById('orderTotal');

                if (cart.length === 0) {
                    alert('Your cart is empty!');
                    return;
                }

                orderSummary.innerHTML = cart.map(item => `
                    <div class="d-flex justify-content-between mb-2">
                        <div>
                            <strong>${item.name}</strong>
                            <div>$${item.price.toFixed(2)} × ${item.quantity}</div>
                        </div>
                        <div>
                            $${(item.price * item.quantity).toFixed(2)}
                        </div>
                    </div>
                `).join('');

                const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                orderTotal.textContent = `$${total.toFixed(2)}`;

                new bootstrap.Modal(document.getElementById('whatsappModal')).show();
            }

            function sendOrderViaWhatsapp() {
                const name = document.getElementById('customerName').value;
                const phone = document.getElementById('customerPhone').value;
                const address = document.getElementById('customerAddress').value;

                if (!name || !phone || !address) {
                    alert('Please fill in all fields');
                    return;
                }

                let message = `*NEW ORDER*%0A%0A*Customer Name:* ${name}%0A*Phone:* ${phone}%0A*Address:* ${address}%0A%0A*Order Details:*%0A`;
                cart.forEach(item => message += `- ${item.name} (${item.quantity} × $${item.price.toFixed(2)})%0A`);
                message += `%0A*Total: $${orderTotal.textContent}*`;

                window.open(`https://api.whatsapp.com/send?phone=${whatsappNumber}&text=${message}`, '_blank');

                sessionStorage.removeItem('cart');
                updateCartBadge();
            }
        });
    </script>
</body>
</html>
