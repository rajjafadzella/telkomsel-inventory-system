<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 1. Menampilkan Daftar Barang (index) + Pencarian + Pagination
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('code', 'like', "%{$search}%");
            })
            ->paginate(10); // Pagination 10 data per halaman

        return view('products.index', compact('products'));
    }

    // 2. Menampilkan Form Tambah Barang
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    // 3. Menyimpan Barang Baru (store) + Validasi + Upload Gambar
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:products,code',
            'name' => 'required',
            'category_id' => 'required|exists:categories,id',
            'stock' => 'required|integer|min:0',
            'location' => 'required',
            'condition' => 'required|in:Bagus,Rusak Ringan,Rusak Berat',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048' // Validasi gambar
        ]);

        $data = $request->all();

        // Logika Upload Gambar (Bonus Fitur)
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // 4. Menampilkan Form Edit Barang
public function edit(Product $product)
{
    $categories = Category::all();
    return view('products.edit', compact('product', 'categories'));
}

// 5. Memproses Update Data Barang
public function update(Request $request, Product $product)
{
    $request->validate([
        'code' => 'required|unique:products,code,' . $product->id,
        'name' => 'required',
        'category_id' => 'required|exists:categories,id',
        'stock' => 'required|integer|min:0',
        'location' => 'required',
        'condition' => 'required|in:Bagus,Rusak Ringan,Rusak Berat',
        'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    $data = $request->all();

    // Logika Ganti Gambar (Jika ada gambar baru yang di-upload)
    if ($request->hasFile('image')) {
        // Hapus gambar lama dari storage jika ada
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        // Simpan gambar baru
        $data['image'] = $request->file('image')->store('products', 'public');
    }

    $product->update($data);

    return redirect()->route('products.index')->with('success', 'Barang berhasil diperbarui!');
}

    // 6. Menghapus Barang
    public function destroy(Product $product)
    {
        // Hapus file gambar dari storage sebelum menghapus record database
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Barang berhasil dihapus!');
    }
}