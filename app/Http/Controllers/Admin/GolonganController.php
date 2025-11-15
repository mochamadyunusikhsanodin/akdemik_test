<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Golongan;
use Illuminate\Http\Request;

class GolonganController extends Controller
{
    public function index()
    {
        $golongans = Golongan::all();
        return view('admin.golongan.index', compact('golongans'));
    }

    public function create()
    {
        return view('admin.golongan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gol' => 'required|string|max:10|unique:golongan,id_gol',
            'nama_gol' => 'required|string|max:255',
        ]);

        Golongan::create($request->all());

        return redirect()->route('admin.golongan.index')
                        ->with('success', 'Golongan berhasil ditambahkan.');
    }

    public function show(Golongan $golongan)
    {
        return view('admin.golongan.show', compact('golongan'));
    }

    public function edit($id_gol)
    {
        $golongan = Golongan::findOrFail($id_gol);
        return view('admin.golongan.edit', compact('golongan'));
    }

    public function update(Request $request, $id_gol)
    {
        $golongan = Golongan::findOrFail($id_gol);
        
        $request->validate([
            'id_gol' => 'required|string|max:10|unique:golongan,id_gol,' . $id_gol . ',id_gol',
            'nama_gol' => 'required|string|max:255',
        ]);

        $golongan->update($request->all());

        return redirect()->route('admin.golongan.index')
                        ->with('success', 'Golongan berhasil diperbarui.');
    }

    public function destroy($id_gol)
    {
        $golongan = Golongan::findOrFail($id_gol);
        $golongan->delete();

        return redirect()->route('admin.golongan.index')
                        ->with('success', 'Golongan berhasil dihapus.');
    }
}