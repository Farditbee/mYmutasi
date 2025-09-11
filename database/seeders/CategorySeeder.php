<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Income categories
        $incomeCategories = [
            [
                'name' => 'Gaji',
                'type' => 'income',
                'description' => 'Pendapatan dari gaji bulanan',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Bonus',
                'type' => 'income',
                'description' => 'Bonus dari pekerjaan atau prestasi',
                'color' => '#059669',
                'is_active' => true,
            ],
            [
                'name' => 'Freelance',
                'type' => 'income',
                'description' => 'Pendapatan dari pekerjaan freelance',
                'color' => '#34D399',
                'is_active' => true,
            ],
            [
                'name' => 'Investasi',
                'type' => 'income',
                'description' => 'Keuntungan dari investasi',
                'color' => '#6EE7B7',
                'is_active' => true,
            ],
            [
                'name' => 'Bisnis',
                'type' => 'income',
                'description' => 'Pendapatan dari bisnis',
                'color' => '#047857',
                'is_active' => true,
            ],
            [
                'name' => 'Lainnya',
                'type' => 'income',
                'description' => 'Pendapatan lainnya',
                'color' => '#065F46',
                'is_active' => true,
            ],
        ];

        // Expense categories
        $expenseCategories = [
            [
                'name' => 'Makanan & Minuman',
                'type' => 'expense',
                'description' => 'Pengeluaran untuk makanan dan minuman',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Transportasi',
                'type' => 'expense',
                'description' => 'Biaya transportasi dan bahan bakar',
                'color' => '#DC2626',
                'is_active' => true,
            ],
            [
                'name' => 'Belanja',
                'type' => 'expense',
                'description' => 'Belanja kebutuhan sehari-hari',
                'color' => '#B91C1C',
                'is_active' => true,
            ],
            [
                'name' => 'Hiburan',
                'type' => 'expense',
                'description' => 'Pengeluaran untuk hiburan dan rekreasi',
                'color' => '#991B1B',
                'is_active' => true,
            ],
            [
                'name' => 'Kesehatan',
                'type' => 'expense',
                'description' => 'Biaya kesehatan dan obat-obatan',
                'color' => '#7F1D1D',
                'is_active' => true,
            ],
            [
                'name' => 'Pendidikan',
                'type' => 'expense',
                'description' => 'Biaya pendidikan dan kursus',
                'color' => '#F97316',
                'is_active' => true,
            ],
            [
                'name' => 'Tagihan',
                'type' => 'expense',
                'description' => 'Pembayaran tagihan bulanan',
                'color' => '#EA580C',
                'is_active' => true,
            ],
            [
                'name' => 'Investasi',
                'type' => 'expense',
                'description' => 'Pengeluaran untuk investasi',
                'color' => '#C2410C',
                'is_active' => true,
            ],
            [
                'name' => 'Lainnya',
                'type' => 'expense',
                'description' => 'Pengeluaran lainnya',
                'color' => '#9A3412',
                'is_active' => true,
            ],
        ];

        // Insert income categories
        foreach ($incomeCategories as $category) {
            Category::create($category);
        }

        // Insert expense categories
        foreach ($expenseCategories as $category) {
            Category::create($category);
        }
    }
}