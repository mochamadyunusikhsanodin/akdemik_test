<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Matakuliah;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MatakuliahController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $matakuliah = Matakuliah::when($search, function ($query, $search) {
            return $query->where('kode_mk', 'like', "%{$search}%")
                        ->orWhere('nama_mk', 'like', "%{$search}%")
                        ->orWhere('semester', 'like', "%{$search}%");
        })
        ->orderBy('semester')
        ->orderBy('kode_mk')
        ->paginate(10);

        return view('admin.matakuliah.index', compact('matakuliah', 'search'));
    }

    public function create()
    {
        return view('admin.matakuliah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required|string|max:10|unique:matakuliah,kode_mk',
            'nama_mk' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8'
        ]);

        Matakuliah::create($request->all());

        return redirect()->route('admin.matakuliah.index')
                        ->with('success', 'Mata kuliah berhasil ditambahkan.');
    }

    public function show(Matakuliah $matakuliah)
    {
        $matakuliah->load(['krs.mahasiswa', 'pengampu']);
        return view('admin.matakuliah.show', compact('matakuliah'));
    }

    public function edit(Matakuliah $matakuliah)
    {
        return view('admin.matakuliah.edit', compact('matakuliah'));
    }

    public function update(Request $request, Matakuliah $matakuliah)
    {
        $request->validate([
            'kode_mk' => [
                'required',
                'string',
                'max:10',
                Rule::unique('matakuliah', 'kode_mk')->ignore($matakuliah->kode_mk, 'kode_mk')
            ],
            'nama_mk' => 'required|string|max:100',
            'sks' => 'required|integer|min:1|max:6',
            'semester' => 'required|integer|min:1|max:8'
        ]);

        $matakuliah->update($request->all());

        return redirect()->route('admin.matakuliah.index')
                        ->with('success', 'Mata kuliah berhasil diperbarui.');
    }

    public function destroy(Matakuliah $matakuliah)
    {
        try {
            $matakuliah->delete();
            return redirect()->route('admin.matakuliah.index')
                            ->with('success', 'Mata kuliah berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.matakuliah.index')
                            ->with('error', 'Mata kuliah tidak dapat dihapus karena masih digunakan.');
        }
    }
}