@extends('install.layout')

@section('title', 'Create Admin Account')
@section('subtitle', 'Set up your first admin user')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Admin Account</h2>

    <div id="alert-container"></div>

    <form id="admin-form" class="space-y-4 mb-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
            <input type="text" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="John Doe" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="admin@example.com" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="••••••••" required minlength="8">
            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="••••••••" required minlength="8">
        </div>

        <button type="submit" id="create-admin-btn" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition">
            Create Admin Account
        </button>
    </form>

    <div class="flex justify-between">
        <a href="{{ route('install.settings') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
            &larr; Geri
        </a>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('admin-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = document.getElementById('create-admin-btn');
    
    btn.disabled = true;
    btn.textContent = 'Creating...';
    
    try {
        const response = await fetch('{{ route("install.create-admin") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('success', 'Admin account created successfully! Finalizing installation...');
            
            setTimeout(() => {
                window.location.href = '{{ route("install.finalize") }}';
            }, 1500);
        } else {
            showAlert('error', data.message);
            btn.disabled = false;
            btn.textContent = 'Create Admin Account';
        }
    } catch (error) {
        showAlert('error', 'Failed to create admin account: ' + error.message);
        btn.disabled = false;
        btn.textContent = 'Create Admin Account';
    }
});

function showAlert(type, message) {
    const container = document.getElementById('alert-container');
    const bgColor = type === 'success' ? 'green' : 'red';
    
    container.innerHTML = `
        <div class="bg-${bgColor}-50 border border-${bgColor}-200 text-${bgColor}-800 rounded-lg p-4 mb-4">
            ${message}
        </div>
    `;
}
</script>
@endpush
