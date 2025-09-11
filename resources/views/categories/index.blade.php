<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Kategori') }}
            </h2>
            <a href="{{ route('categories.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Kategori
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div>
                        <!-- Per Page Selector -->
                        <div class="mb-4 flex items-center space-x-2">
                            <label for="per_page" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Tampilkan:
                            </label>
                            <select id="per_page" name="per_page" 
                                class="border border-gray-300 dark:border-gray-600 rounded-md py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                onchange="changePerPage(this.value)">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page', 10) == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                            </select>
                            <span class="text-sm text-gray-700 dark:text-gray-300">per halaman</span>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            #</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Nama</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Deskripsi</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Jenis</th>
                                        {{-- <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Warna</th> --}}
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $category->name }}
                                            </td>
                                            <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                {{ $category->description ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($category->type == 'income')
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Pemasukan
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                        Pengeluaran
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- <td
                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                <div class="flex items-center">
                                                    <div class="w-4 h-4 rounded-full mr-2"
                                                        style="background-color: {{ $category->color }}"></div>
                                                    {{ $category->color }}
                                                </div>
                                            </td> --}}
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <form action="{{ route('categories.toggle-status', $category) }}"
                                                    method="POST" class="inline toggle-form">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="button"
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full cursor-pointer transition-colors duration-200 hover:opacity-80 toggle-btn {{ $category->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-red-100 text-red-800 hover:bg-red-200' }}"
                                                        data-status="{{ $category->is_active ? 'nonaktifkan' : 'aktifkan' }}"
                                                        data-item-name="{{ $category->name }}"
                                                        title="Klik untuk {{ $category->is_active ? 'menonaktifkan' : 'mengaktifkan' }} kategori">
                                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                                    </button>
                                                </form>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('categories.edit', $category) }}"
                                                        class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200"
                                                        title="Edit Kategori">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <form action="{{ route('categories.destroy', $category) }}"
                                                        method="POST" class="inline delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors duration-200 delete-btn"
                                                            title="Hapus Kategori">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination Links -->
                        <div class="mt-4">
                            {{ $categories->links() }}
                        </div>
                        
                        {{-- @else
                            <p class="text-gray-500">Belum ada kategori.</p>
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                SweetAlertHelper.setupDeleteButtons('.delete-btn', {
                    title: 'Konfirmasi Hapus Kategori',
                    text: 'Apakah Anda yakin ingin menghapus kategori ini?'
                });

                SweetAlertHelper.setupToggleButtons('.toggle-btn', {});
            });
            
            function changePerPage(perPage) {
                const url = new URL(window.location.href);
                url.searchParams.set('per_page', perPage);
                url.searchParams.delete('page');
                window.location.href = url.toString();
            }
        </script>
    @endpush
</x-app-layout>
