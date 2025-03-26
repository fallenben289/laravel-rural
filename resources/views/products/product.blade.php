<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <!-- Left Side - Vendor Details & Add Product Form -->
        <div class="col-md-4 p-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Vendor Details</h5>
                </div>
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $vendor->name }}</p>
                    <p><strong>Phone:</strong> {{ $vendor->phone }}</p>
                </div>
            </div>

            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Add New Product</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                   
                      @csrf  <!-- Required for security -->
                      
                      @if(isset($product))
                          @method('PUT')  <!-- Add this for updates -->
                      @endif
                
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $product->name ?? '' }}" required>
                        </div>
                
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control">{{ $product->description ?? '' }}</textarea>
                        </div>
                
                        <div class="mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" name="price" id="price" class="form-control" value="{{ $product->price ?? '' }}" required>
                        </div>
                
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ $product->quantity ?? 0 }}">
                        </div>
                
                        <button type="submit" class="btn btn-primary">{{ isset($product) ? 'Update' : 'Add' }} Product</button>
                    </form>
                </div>
            </div>
        </div>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        
      <!-- Right Side - Product Table -->
<div class="col-md-8 p-4">
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Your Products</h5>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" width="50" class="img-thumbnail">
                                @else
                                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-box"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <!-- Edit Modal Form -->
                                    <div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <!-- Product Name -->
                                                        <div class="mb-3">
                                                            <label for="name" class="form-label">Product Name</label>
                                                            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
                                                        </div>

                                                        <!-- Product Price -->
                                                        <div class="mb-3">
                                                            <label for="price" class="form-label">Price</label>
                                                            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required min="0">
                                                        </div>

                                                        <!-- Product Quantity -->
                                                        <div class="mb-3">
                                                            <label for="quantity" class="form-label">Quantity</label>
                                                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity }}" min="0">
                                                        </div>

                                                        <!-- Product Image -->
                                                        <div class="mb-3">
                                                            <label for="image" class="form-label">Image</label>
                                                            <input type="file" class="form-control" id="image" name="image">
                                                        </div>

                                                        <button type="submit" class="btn btn-primary">Update Product</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Delete Button -->
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No products found. Add your first product!</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


            <!-- Store Link Box (Moved Below Product Table) -->
            <div class="card mt-4 shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">Your Public Store Link</h5>
                </div>
                <div class="card-body">
                    <div class="input-group">
                        <input type="text" class="form-control" id="storeLink" value="{{ route('customer.show', $vendor->id) }}" readonly>
                        <button class="btn btn-primary" type="button" onclick="copyStoreLink()">
                            <i class="fas fa-copy"></i> Copy
                        </button>
                    </div>
                    <p class="mt-2">Share this link with your customers:</p>
                    <div class="btn-group">
                        <a href="https://wa.me/?text=Check%20out%20my%20store:%20{{ urlencode(route('customer.show', $vendor->id)) }}" class="btn btn-success" target="_blank">
                            <i class="fab fa-whatsapp"></i> Share via WhatsApp
                        </a>
                        <a href="mailto:?body=Check%20out%20my%20store:%20{{ urlencode(route('customer.show', $vendor->id)) }}" class="btn btn-secondary">
                            <i class="fas fa-envelope"></i> Share via Email
                        </a>
                    </div>
                </div>
            </div>
            

<!-- Copy Function -->
<script>
function copyStoreLink() {
    const copyText = document.getElementById("storeLink");
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    document.execCommand("copy");

    alert("Store link copied to clipboard!");
}
</script>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

</body>
</html>
