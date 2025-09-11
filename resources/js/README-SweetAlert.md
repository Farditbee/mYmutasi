# SweetAlert Helper Documentation

File helper JavaScript untuk mempermudah penggunaan SweetAlert2 di berbagai halaman aplikasi.

## File Location
- **Helper File**: `/resources/js/sweetalert-helper.js`
- **Import**: Sudah diimport otomatis di `app.js`

## Fitur Utama

### 1. Auto-initialization
SweetAlert helper akan otomatis menginisialisasi event listener untuk:
- Tombol delete dengan class `.delete-btn`
- Tombol toggle status dengan class `.toggle-btn`

### 2. Method yang Tersedia

#### Konfirmasi Delete
```javascript
SweetAlertHelper.confirmDelete({
    title: 'Konfirmasi Hapus',
    text: 'Apakah Anda yakin ingin menghapus item ini?',
    itemName: 'kategori',
    hasRelatedData: true,
    onConfirm: () => {
        // Aksi setelah konfirmasi
    }
});
```

#### Konfirmasi Toggle Status
```javascript
SweetAlertHelper.confirmToggleStatus({
    currentStatus: 'active', // atau 'inactive'
    itemName: 'kategori',
    onConfirm: () => {
        // Aksi setelah konfirmasi
    }
});
```

#### Setup Form Submission
```javascript
SweetAlertHelper.setupFormSubmission({
    form: document.getElementById('myForm'),
    submitBtn: document.getElementById('submitBtn'),
    fields: [
        { element: document.getElementById('name'), name: 'Nama', required: true },
        { element: document.getElementById('email'), name: 'Email', required: true }
    ],
    confirmation: {
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menyimpan data?',
        action: 'simpan'
    }
});
```

#### Pesan Success/Error
```javascript
// Success
SweetAlertHelper.showSuccess('Berhasil!', 'Data berhasil disimpan');

// Error
SweetAlertHelper.showError('Error!', 'Terjadi kesalahan');

// Validation Error
SweetAlertHelper.showValidationError('Nama harus diisi!');
```

#### Loading Dialog
```javascript
SweetAlertHelper.showLoading('Memproses...', 'Mohon tunggu sebentar');
```

### 3. Setup Manual untuk Tombol

#### Delete Buttons
```javascript
SweetAlertHelper.setupDeleteButtons('.my-delete-btn', {
    title: 'Konfirmasi Hapus Custom',
    text: 'Pesan custom untuk delete'
});
```

#### Toggle Buttons
```javascript
SweetAlertHelper.setupToggleButtons('.my-toggle-btn', {
    // Custom options
});
```

## Penggunaan di HTML

### Delete Button
```html
<form action="{{ route('items.destroy', $item) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" 
            class="delete-btn"
            data-item-name="{{ $item->name }}"
            data-has-related="{{ $item->hasRelatedData() ? 'true' : 'false' }}">
        Hapus
    </button>
</form>
```

### Toggle Status Button
```html
<form action="{{ route('items.toggle-status', $item) }}" method="POST">
    @csrf
    <button type="submit" 
            class="toggle-btn"
            data-status="{{ $item->is_active ? 'nonaktifkan' : 'aktifkan' }}"
            data-item-name="{{ $item->name }}">
        {{ $item->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
    </button>
</form>
```

### Form dengan Validation
```html
<form id="myForm" action="{{ route('items.store') }}" method="POST">
    @csrf
    <input type="text" id="name" name="name" required>
    <input type="email" id="email" name="email" required>
    
    <button type="button" id="submitBtn">Simpan</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    SweetAlertHelper.setupFormSubmission({
        form: document.getElementById('myForm'),
        submitBtn: document.getElementById('submitBtn'),
        fields: [
            { element: document.getElementById('name'), name: 'Nama', required: true },
            { element: document.getElementById('email'), name: 'Email', required: true }
        ],
        confirmation: {
            title: 'Konfirmasi',
            text: 'Apakah Anda yakin ingin menyimpan data?',
            action: 'simpan'
        }
    });
});
</script>
```

## Kustomisasi

### Disable Auto-initialization
```javascript
// Di app.js, ganti:
SweetAlertHelper.init();

// Menjadi:
SweetAlertHelper.init({
    enableDeleteButtons: false,
    enableToggleButtons: false
});
```

### Custom Selectors
```javascript
SweetAlertHelper.init({
    deleteSelector: '.custom-delete-btn',
    toggleSelector: '.custom-toggle-btn'
});
```

## Contoh Implementasi Lengkap

Lihat file-file berikut untuk contoh implementasi:
- `/resources/views/categories/index.blade.php`
- `/resources/views/categories/create.blade.php`
- `/resources/views/categories/edit.blade.php`

## Tips Penggunaan

1. **Konsistensi Class Names**: Gunakan class `.delete-btn` dan `.toggle-btn` untuk auto-initialization
2. **Data Attributes**: Manfaatkan data attributes untuk menyimpan informasi tambahan
3. **Form Validation**: Gunakan `setupFormSubmission()` untuk validasi otomatis
4. **Loading States**: Selalu tampilkan loading saat proses berlangsung
5. **Error Handling**: Gunakan `showError()` untuk menampilkan pesan error yang konsisten

## Browser Support

Helper ini kompatibel dengan semua browser modern yang mendukung:
- ES6 Classes
- Arrow Functions
- Template Literals
- Destructuring Assignment