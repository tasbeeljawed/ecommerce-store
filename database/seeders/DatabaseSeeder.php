<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@ecommerce.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+1234567890',
            'is_active' => true,
        ]);

        // Create Customer User
        User::create([
            'name' => 'John Doe',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '+1234567891',
            'is_active' => true,
        ]);

        // Create Categories
        $categories = [
            ['name' => 'Electronics', 'slug' => 'electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Clothing', 'slug' => 'clothing', 'description' => 'Fashion and apparel'],
            ['name' => 'Books', 'slug' => 'books', 'description' => 'Books and literature'],
            ['name' => 'Home & Garden', 'slug' => 'home-garden', 'description' => 'Home and garden products'],
            ['name' => 'Sports', 'slug' => 'sports', 'description' => 'Sports equipment and gear'],
            ['name' => 'Toys', 'slug' => 'toys', 'description' => 'Toys and games'],
        ];

        foreach ($categories as $index => $category) {
            Category::create(array_merge($category, [
                'is_active' => true,
                'order' => $index + 1,
            ]));
        }

        // Create Sample Products
        $products = [
            // Electronics
            [
                'name' => 'Wireless Bluetooth Headphones',
                'slug' => 'wireless-bluetooth-headphones',
                'short_description' => 'Premium quality wireless headphones with noise cancellation',
                'description' => 'Experience superior sound quality with these wireless Bluetooth headphones featuring active noise cancellation, 30-hour battery life, and comfortable over-ear design.',
                'price' => 149.99,
                'sale_price' => 129.99,
                'sku' => 'ELEC001',
                'quantity' => 50,
                'category_id' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Smart Watch Pro',
                'slug' => 'smart-watch-pro',
                'short_description' => 'Advanced fitness tracking smartwatch',
                'description' => 'Track your fitness goals with this advanced smartwatch featuring heart rate monitoring, GPS, and multiple sport modes.',
                'price' => 299.99,
                'sale_price' => null,
                'sku' => 'ELEC002',
                'quantity' => 30,
                'category_id' => 1,
                'is_featured' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Portable Bluetooth Speaker',
                'slug' => 'portable-bluetooth-speaker',
                'short_description' => 'Waterproof portable speaker with 360° sound',
                'description' => 'Enjoy your music anywhere with this waterproof Bluetooth speaker offering 360-degree sound and 12-hour battery life.',
                'price' => 79.99,
                'sale_price' => 59.99,
                'sku' => 'ELEC003',
                'quantity' => 75,
                'category_id' => 1,
                'is_featured' => false,
                'is_active' => true,
            ],
            
            // Clothing
            [
                'name' => 'Classic Cotton T-Shirt',
                'slug' => 'classic-cotton-t-shirt',
                'short_description' => 'Comfortable 100% cotton t-shirt',
                'description' => 'A wardrobe essential made from premium 100% cotton fabric for all-day comfort.',
                'price' => 24.99,
                'sale_price' => 19.99,
                'sku' => 'CLTH001',
                'quantity' => 200,
                'category_id' => 2,
                'is_featured' => false,
                'is_active' => true,
            ],
            [
                'name' => 'Denim Jeans',
                'slug' => 'denim-jeans',
                'short_description' => 'Classic fit denim jeans',
                'description' => 'Timeless denim jeans with classic fit and durable construction.',
                'price' => 69.99,
                'sale_price' => null,
                'sku' => 'CLTH002',
                'quantity' => 100,
                'category_id' => 2,
                'is_featured' => true,
                'is_active' => true,
            ],
            
            // Books
            [
                'name' => 'The Art of Programming',
                'slug' => 'art-of-programming',
                'short_description' => 'Complete guide to modern programming',
                'description' => 'Comprehensive guide covering modern programming principles, patterns, and best practices.',
                'price' => 49.99,
                'sale_price' => 39.99,
                'sku' => 'BOOK001',
                'quantity' => 150,
                'category_id' => 3,
                'is_featured' => true,
                'is_active' => true,
            ],
            
            // Home & Garden
            [
                'name' => 'Ceramic Plant Pot Set',
                'slug' => 'ceramic-plant-pot-set',
                'short_description' => 'Set of 3 decorative ceramic pots',
                'description' => 'Beautiful set of three ceramic plant pots perfect for indoor plants.',
                'price' => 34.99,
                'sale_price' => 29.99,
                'sku' => 'HOME001',
                'quantity' => 80,
                'category_id' => 4,
                'is_featured' => false,
                'is_active' => true,
            ],
            
            // Sports
            [
                'name' => 'Yoga Mat Premium',
                'slug' => 'yoga-mat-premium',
                'short_description' => 'Non-slip premium yoga mat',
                'description' => 'High-quality yoga mat with excellent grip and cushioning for comfortable practice.',
                'price' => 39.99,
                'sale_price' => null,
                'sku' => 'SPRT001',
                'quantity' => 120,
                'category_id' => 5,
                'is_featured' => false,
                'is_active' => true,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
