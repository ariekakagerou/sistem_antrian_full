# 📄 Fitur Pagination Card Loket

Dokumentasi untuk fitur pagination pada card loket di halaman petugas.

## 🎯 Fitur

- ✅ Menampilkan maksimal **6 card loket** per halaman
- ✅ Navigasi dengan tombol **Previous** dan **Next**
- ✅ Navigasi dengan **nomor halaman**
- ✅ Indikator halaman aktif
- ✅ Auto-calculate total halaman
- ✅ Responsive design

## 📊 Spesifikasi

| Property | Value | Keterangan |
|----------|-------|------------|
| **Per Page** | 6 card | Jumlah card per halaman |
| **Layout** | 3 kolom (desktop) | Grid responsive |
| **Layout** | 2 kolom (tablet) | Grid responsive |
| **Layout** | 1 kolom (mobile) | Grid responsive |

## 🔧 Implementasi

### Backend (PetugasLoket.php)

```php
// Property pagination
public $currentPage = 1;
public $perPage = 6;
public $totalPages = 1;

// Method pagination
public function calculateTotalPages()
{
    $totalLokets = count($this->lokets);
    $this->totalPages = ceil($totalLokets / $this->perPage);
}

public function getPaginatedLokets()
{
    $offset = ($this->currentPage - 1) * $this->perPage;
    return array_slice($this->lokets, $offset, $this->perPage);
}

public function nextPage()
{
    if ($this->currentPage < $this->totalPages) {
        $this->currentPage++;
    }
}

public function previousPage()
{
    if ($this->currentPage > 1) {
        $this->currentPage--;
    }
}

public function goToPage($page)
{
    if ($page >= 1 && $page <= $this->totalPages) {
        $this->currentPage = $page;
    }
}
```

### Frontend (petugas-loket.blade.php)

```blade
<!-- Header dengan indikator halaman -->
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">
        <i class="fas fa-door-open mr-2 text-indigo-600"></i>
        Pilih Loket
    </h2>
    @if($totalPages > 1)
        <div class="text-sm text-gray-600">
            Halaman {{ $currentPage }} dari {{ $totalPages }}
        </div>
    @endif
</div>

<!-- Grid card loket (hanya 6 per halaman) -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @foreach($this->getPaginatedLokets() as $loket)
        <button wire:click="selectLoket({{ $loket['id'] }})">
            <!-- Card content -->
        </button>
    @endforeach
</div>

<!-- Pagination controls -->
@if($totalPages > 1)
    <div class="flex items-center justify-center gap-2">
        <!-- Previous, Page Numbers, Next -->
    </div>
@endif
```

## 🎨 UI/UX Features

### 1. **Card Loket**
- Gradient background (indigo → purple)
- Hover effect: scale & shadow
- Icon hospital
- Nama loket & deskripsi

### 2. **Pagination Controls**
- **Previous Button**: Disabled di halaman pertama
- **Page Numbers**: Highlight halaman aktif
- **Next Button**: Disabled di halaman terakhir
- Smooth transitions

### 3. **Responsive**
```
Desktop (lg):  3 kolom
Tablet (md):   2 kolom
Mobile:        1 kolom
```

## 📱 Contoh Penggunaan

### Skenario 1: 6 Loket atau Kurang
- Semua card ditampilkan
- Pagination **tidak muncul**
- Tidak ada tombol navigasi

### Skenario 2: 7-12 Loket
- Halaman 1: 6 card pertama
- Halaman 2: Sisa card
- Pagination muncul dengan 2 tombol halaman

### Skenario 3: 13+ Loket
- Setiap halaman: 6 card
- Pagination muncul dengan multiple tombol
- Navigasi Previous/Next aktif

## 🔄 Alur Kerja

1. **Load Data**
   ```
   loadLokets() → calculateTotalPages()
   ```

2. **Render Cards**
   ```
   getPaginatedLokets() → Slice array berdasarkan currentPage
   ```

3. **User Click Next**
   ```
   nextPage() → currentPage++ → Re-render
   ```

4. **User Click Page Number**
   ```
   goToPage(3) → currentPage = 3 → Re-render
   ```

## 💡 Keuntungan

### Performance
- ✅ Hanya render 6 card per halaman
- ✅ Mengurangi DOM elements
- ✅ Faster page load

### UX
- ✅ Tidak overwhelm user dengan banyak card
- ✅ Easy navigation
- ✅ Clear visual hierarchy

### Scalability
- ✅ Support unlimited loket
- ✅ Easy to adjust perPage value
- ✅ Maintainable code

## ⚙️ Kustomisasi

### Ubah Jumlah Card Per Halaman

Edit `PetugasLoket.php`:
```php
public $perPage = 9; // Ubah dari 6 ke 9
```

Sesuaikan grid di view:
```blade
<!-- Untuk 9 card, gunakan 3x3 grid -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-4">
```

### Ubah Style Pagination

Edit `petugas-loket.blade.php`:
```blade
<!-- Ganti warna dari indigo ke blue -->
class="bg-blue-600 text-white hover:bg-blue-700"
```

## 🧪 Testing

### Test Case 1: Pagination Muncul
```
Given: Ada 10 loket
When: User buka halaman petugas
Then: Pagination muncul dengan 2 halaman
```

### Test Case 2: Navigasi Next
```
Given: User di halaman 1
When: User klik "Selanjutnya"
Then: Pindah ke halaman 2
And: Tombol "Sebelumnya" aktif
```

### Test Case 3: Navigasi Previous
```
Given: User di halaman 2
When: User klik "Sebelumnya"
Then: Pindah ke halaman 1
And: Tombol "Sebelumnya" disabled
```

### Test Case 4: Click Page Number
```
Given: User di halaman 1
When: User klik nomor "3"
Then: Langsung pindah ke halaman 3
```

## 🐛 Troubleshooting

### Pagination Tidak Muncul
**Cause:** Total loket ≤ 6
**Solution:** Normal behavior, pagination hanya muncul jika > 6 loket

### Card Tidak Update Saat Klik Next
**Cause:** Method `getPaginatedLokets()` tidak dipanggil
**Solution:** Pastikan menggunakan `$this->getPaginatedLokets()` di view

### Total Pages Salah
**Cause:** `calculateTotalPages()` tidak dipanggil
**Solution:** Panggil di `loadLokets()` setelah load data

## 📊 Statistik

### Before Pagination
- 20 loket = 20 card rendered
- Heavy DOM
- Scroll panjang

### After Pagination
- 20 loket = 6 card rendered per halaman
- Light DOM
- Clean UI

## 🎯 Best Practices

1. **Always Calculate Total Pages**
   ```php
   $this->calculateTotalPages();
   ```

2. **Use Method for Paginated Data**
   ```blade
   @foreach($this->getPaginatedLokets() as $loket)
   ```

3. **Show Pagination Only When Needed**
   ```blade
   @if($totalPages > 1)
   ```

4. **Disable Buttons at Boundaries**
   ```blade
   @if($currentPage == 1) disabled @endif
   ```

## 🔜 Future Enhancements

- [ ] Keyboard navigation (arrow keys)
- [ ] Search/filter loket
- [ ] Sort loket
- [ ] Remember last page (session)
- [ ] Infinite scroll option
- [ ] Animation transitions

---

**Status:** ✅ Implemented  
**Version:** 1.0.0  
**Last Updated:** 5 November 2024
