@extends('install.layout')

@section('title', 'Site Settings')
@section('subtitle', 'Configure your site settings')

@section('content')
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Site Settings</h2>

    <div id="alert-container"></div>

    <form id="settings-form" class="space-y-4 mb-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Site Adı <span class="text-red-500">*</span>
            </label>
            <input type="text" name="site_name" value="Manga Diyarı" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="Manga Diyarı" required>
            <p class="text-xs text-gray-500 mt-1">Sitenizin ana başlığı</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                Site Açıklaması
            </label>
            <textarea name="site_description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="En iyi manga okuma deneyimi">En iyi manga okuma deneyimi</textarea>
            <p class="text-xs text-gray-500 mt-1">SEO için kullanılacak kısa açıklama</p>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Sosyal Medya Bağlantıları</h3>
            
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Facebook URL</label>
                    <input type="url" name="facebook_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="https://facebook.com/yourpage">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Twitter URL</label>
                    <input type="url" name="twitter_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="https://twitter.com/yourpage">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Discord URL</label>
                    <input type="url" name="discord_url" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent" placeholder="https://discord.gg/invite">
                </div>
            </div>
        </div>

        <div class="border-t pt-4">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">Demo İçerik</h3>
            
            <div class="flex items-start">
                <input type="checkbox" name="load_demo_data" id="load_demo_data" class="mt-1 h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" checked>
                <label for="load_demo_data" class="ml-2 block text-sm text-gray-700">
                    <span class="font-medium">Demo içerikleri yükle</span>
                    <p class="text-xs text-gray-500 mt-1">
                        Örnek manga serileri, kategoriler, bölümler ve coin paketleri yüklenecektir. 
                        Bu sayede sitenizi hemen test edebilirsiniz.
                    </p>
                </label>
            </div>
        </div>

        <button type="submit" id="save-settings-btn" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold rounded-lg hover:shadow-lg transition">
            Ayarları Kaydet ve Devam Et
        </button>
    </form>

    <div class="flex justify-between">
        <a href="{{ route('install.database') }}" class="px-6 py-3 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition">
            &larr; Geri
        </a>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('settings-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const btn = document.getElementById('save-settings-btn');
    
    // Add checkbox value explicitly (unchecked checkboxes don't get sent)
    if (!document.getElementById('load_demo_data').checked) {
        formData.set('load_demo_data', '0');
    } else {
        formData.set('load_demo_data', '1');
    }
    
    btn.disabled = true;
    btn.textContent = 'Kaydediliyor...';
    
    try {
        const response = await fetch('{{ route("install.save-settings") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            showAlert('success', 'Ayarlar başarıyla kaydedildi! Yönlendiriliyorsunuz...');
            
            setTimeout(() => {
                window.location.href = '{{ route("install.admin") }}';
            }, 1000);
        } else {
            showAlert('error', data.message);
            btn.disabled = false;
            btn.textContent = 'Ayarları Kaydet ve Devam Et';
        }
    } catch (error) {
        showAlert('error', 'Ayarlar kaydedilirken hata oluştu: ' + error.message);
        btn.disabled = false;
        btn.textContent = 'Ayarları Kaydet ve Devam Et';
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
