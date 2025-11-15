<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruang::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('id_ruang', 'like', '%' . $request->search . '%')
                  ->orWhere('nama_ruang', 'like', '%' . $request->search . '%');
            });
        }
        
        $ruangs = $query->orderBy('id_ruang')->paginate(15);
        
        return view('admin.ruang.index', compact('ruangs'));
    }

    public function create()
    {
        return view('admin.ruang.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ruang' => 'required|string|max:10|unique:ruang,id_ruang',
            'nama_ruang' => 'required|string|max:50'
        ]);

        Ruang::create($request->all());

        return redirect()->route('admin.ruang.index')
                        ->with('success', 'Ruang berhasil ditambahkan!');
    }

    public function show(Ruang $ruang)
    {
        return view('admin.ruang.show', compact('ruang'));
    }

    public function edit(Ruang $ruang)
    {
        return view('admin.ruang.edit', compact('ruang'));
    }

    public function update(Request $request, Ruang $ruang)
    {
        $request->validate([
            'id_ruang' => ['required', 'string', 'max:10', Rule::unique('ruang')->ignore($ruang->id_ruang, 'id_ruang')],
            'nama_ruang' => 'required|string|max:50'
        ]);

        $ruang->update($request->all());

        return redirect()->route('admin.ruang.index')
                        ->with('success', 'Ruang berhasil diupdate!');
    }

    public function destroy(Ruang $ruang)
    {
        try {
            $ruang->delete();
            return redirect()->route('admin.ruang.index')
                            ->with('success', 'Ruang berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.ruang.index')
                            ->with('error', 'Ruang tidak dapat dihapus karena masih digunakan!');
        }
    }
}