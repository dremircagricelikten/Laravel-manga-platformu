@extends('install.layout')

@section('title', 'Database Configuration')
@section('subtitle', 'Configure your MySQL database connection')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Database Configuration</h2>

    <div id="alert-container"></div>

    <form id="database-form" class="space-y-4 mb-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Database Host</label>
            <input type="text" name="db_host" value="localhost" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Database Port</label>
            <input type="number" name="db_port" value="3306" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Database Name</label>
            <input type="text" name="db_name" value="manga_cms" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
            <p class="text-xs text-gray-500 mt-1">Make sure this database exists in MySQL</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Database Username</label>
            <input type="text" name="db_username" value="root" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" required>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Database Password</label>
            <input type="password" name="db_password" value="" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            <p class="text-xs text-gray-500 mt-1">Leave empty if no password</p>
        </div>

        <div class="flex space-x-4">
            <button type="button" id="test-connection-btn" class="flex-1 px-6 py-3 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition">
                Test Connection
            </button>
        </div>
    </form>

    <div id="migration-section" class="hidden mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
            <p class="text-green-800 font-medium">✓ Database connection successful!</p>
        </div>

        <button id="run-migrations-btn" class="w-full px-6 py-3 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition">
            Create Database Tables
        </button>

        <div id="migration-progress" class="hidden mt-4">
            <div class="bg-gray-200 rounded-full h-2 mb-2">
                <div class="bg-purple-600 h-2 rounded-full transition-all duration-500" style="width: 0%" id="progress-bar"></div>
            </div>
            <p class="text-sm text-gray-600" id="migration-status">Running migrations...</p>
        </div>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('install.requirements') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
            &larr; Back
        </a>

        <a href="{{ route('install.admin') }}" id="next-btn" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition hidden">
            Next: Admin Account &rarr;
        </a>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('test-connection-btn').addEventListener('click', async function() {
    const form = document.getElementById('database-form');
    const formData = new FormData(form);
    const btn = this;
    
    btn.disabled = true;
    btn.textContent = 'Testing...';
    
    try {
        const response = await fetch('{{ route("install.test-database") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('success', data.message);
            document.getElementById('migration-section').classList.remove('hidden');
            
            // Save database config
            await fetch('{{ route("install.save-database") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            });
        } else {
            showAlert('error', data.message);
        }
    } catch (error) {
        showAlert('error', 'Connection failed: ' + error.message);
    }
    
    btn.disabled = false;
    btn.textContent = 'Test Connection';
});

document.getElementById('run-migrations-btn').addEventListener('click', async function() {
    const btn = this;
    btn.disabled = true;
    
    document.getElementById('migration-progress').classList.remove('hidden');
    const progressBar = document.getElementById('progress-bar');
    const status = document.getElementById('migration-status');
    
    // Simulate progress
    progressBar.style.width = '30%';
    status.textContent = 'Creating database tables...';
    
    try {
        const response = await fetch('{{ route("install.migrate") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            progressBar.style.width = '100%';
            status.textContent = 'Database tables created successfully!';
            showAlert('success', data.message);
            
            setTimeout(() => {
                document.getElementById('next-btn').classList.remove('hidden');
            }, 1000);
        } else {
            showAlert('error', data.message);
            progressBar.style.width = '0%';
        }
    } catch (error) {
        showAlert('error', 'Migration failed: ' + error.message);
        progressBar.style.width = '0%';
    }
    
    btn.disabled = false;
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
