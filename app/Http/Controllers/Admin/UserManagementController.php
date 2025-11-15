<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Golongan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Search by name or email
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('nim_nip', 'like', '%' . $request->search . '%');
            });
        }
        
        $users = $query->paginate(15);
        
        return view('admin.users.index', compact('users'));
    }
    
    public function create()
    {
        $golongans = Golongan::all();
        return view('admin.users.create', compact('golongans'));
    }
    
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,dosen,mahasiswa',
        ];

        if (in_array($request->role, ['dosen', 'mahasiswa'])) {
            $rules['nim_nip'] = 'required|string|max:20|unique:users,nim_nip';
        }

        if ($request->role === 'mahasiswa') {
            $rules['id_gol'] = 'required|exists:golongan,id_gol';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            // Buat user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'nim_nip' => $request->nim_nip,
            ]);
    
            // Buat entry di tabel mahasiswa jika role adalah mahasiswa
            if ($request->role === 'mahasiswa' && $request->nim_nip) {
                Mahasiswa::create([
                    'nim' => $request->nim_nip,
                    'nama' => $request->name,
                    'id_gol' => $request->id_gol ?? 'GOL001',
                ]);
            }
    
            // Buat entry di tabel dosen jika role adalah dosen
            if ($request->role === 'dosen' && $request->nim_nip) {
                Dosen::create([
                    'nip' => $request->nim_nip,
                    'nama' => $request->name,
                ]);
            }
    
            DB::commit();
            return redirect()->route('admin.users.index')
                ->with('success', 'User berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }
    
    public function edit(User $user)
    {
        $golongans = Golongan::orderBy('id_gol')->get();
        return view('admin.users.edit', compact('user', 'golongans'));
    }
    
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,dosen,mahasiswa',
            'nim_nip' => 'nullable|string|max:20',
            'fakultas' => 'nullable|string|max:100',
            'jurusan' => 'nullable|string|max:100',
            'semester' => 'nullable|string|max:10',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'about' => 'nullable|string|max:1000',
        ]);
        
        DB::beginTransaction();
        
        try {
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            
            $oldRole = $user->role;
            $oldNimNip = $user->nim_nip;
            
            $user->update($validated);
            
            // Jika role berubah, hapus data lama dan buat data baru
            if ($oldRole !== $user->role) {
                // Hapus data lama
                if ($oldRole === 'mahasiswa' && $oldNimNip) {
                    Mahasiswa::where('nim', $oldNimNip)->delete();
                } elseif ($oldRole === 'dosen' && $oldNimNip) {
                    Dosen::where('nip', $oldNimNip)->delete();
                }
                
                // Buat data baru
                if ($user->role === 'mahasiswa' && $user->nim_nip) {
                    Mahasiswa::create([
                        'nim' => $user->nim_nip,
                        'nama' => $user->name,
                        'alamat' => $user->location ?? null,
                        'no_hp' => $user->phone ?? null,
                        'semester' => $user->semester ? (int)$user->semester : 1,
                        'id_gol' => 'GOL001'
                    ]);
                } elseif ($user->role === 'dosen' && $user->nim_nip) {
                    Dosen::create([
                        'nip' => $user->nim_nip,
                        'nama' => $user->name,
                        'alamat' => $user->location ?? null,
                        'no_hp' => $user->phone ?? null
                    ]);
                }
            } else {
                // Update data yang sudah ada
                if ($user->role === 'mahasiswa' && $user->nim_nip) {
                    Mahasiswa::where('nim', $user->nim_nip)->update([
                        'nama' => $user->name,
                        'alamat' => $user->location ?? null,
                        'no_hp' => $user->phone ?? null,
                        'semester' => $user->semester ? (int)$user->semester : 1
                    ]);
                } elseif ($user->role === 'dosen' && $user->nim_nip) {
                    Dosen::where('nip', $user->nim_nip)->update([
                        'nama' => $user->name,
                        'alamat' => $user->location ?? null,
                        'no_hp' => $user->phone ?? null
                    ]);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                            ->with('success', 'User berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Anda tidak dapat menghapus akun sendiri!');
        }
        
        DB::beginTransaction();
        
        try {
            // Hapus data terkait di tabel mahasiswa atau dosen
            if ($user->role === 'mahasiswa' && $user->nim_nip) {
                Mahasiswa::where('nim', $user->nim_nip)->delete();
            } elseif ($user->role === 'dosen' && $user->nim_nip) {
                Dosen::where('nip', $user->nim_nip)->delete();
            }
            
            $user->delete();
            
            DB::commit();
            
            return redirect()->route('admin.users.index')
                            ->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('admin.users.index')
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}