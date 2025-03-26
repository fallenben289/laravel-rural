<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: Arial, sans-serif; }
        body { display: flex; justify-content: center; align-items: center; height: 100vh; background: #f8f9fa; }
        .container { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); text-align: center; }
        h1 { margin-bottom: 20px; color: #333; }
        form { display: flex; flex-direction: column; gap: 10px; }
        input { padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        button { padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background: #218838; }
        .link { margin-top: 10px; color: #007bff; text-decoration: none; }
        .link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Register</h1>
        <form action="{{ route('vendor.register') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="text" name="shop_name" placeholder="Shop Name" required>
            <input type="text" name="phone" placeholder="Phone Number" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Register</button>
        </form>
        
        @if(session('success'))
    <div style="color: green; margin-bottom: 10px;">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div style="color: red; margin-bottom: 10px;">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

        <a href="/login" class="link">Already have an account? Login</a>
    </div>
</body>
</html>
