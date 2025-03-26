<form method="POST" action="{{ route('vendor.login') }}" style="display: flex; flex-direction: column; gap: 10px; width: 300px; padding: 20px; border: 1px solid #ddd; border-radius: 5px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);">
    @csrf
    <input type="text" name="phone" placeholder="Phone Number" required style="padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
    <button type="submit" style="padding: 10px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">Login</button>
</form>
