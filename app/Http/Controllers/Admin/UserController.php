<?php
// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = User::query();
        if ($request->filled('role'))  $q->where('role', $request->role);
        if ($request->filled('cari'))  $q->where(function($q2) use ($request) {
            $q2->where('name', 'like', '%'.$request->cari.'%')
               ->orWhere('email', 'like', '%'.$request->cari.'%');
        });
        $users = $q->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'role'     => ['required','in:admin,warga'],
            'daerah'   => ['nullable','string','max:100'],
        ], [
            'name.required'     => 'Nama wajib diisi.',
            'email.required'    => 'Email wajib diisi.',
            'email.unique'      => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        $data['password'] = Hash::make($data['password']);
        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$data['name']} berhasil ditambahkan.");
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable','string','min:8','confirmed'],
            'role'     => ['required','in:admin,warga'],
            'daerah'   => ['nullable','string','max:100'],
        ], [
            'email.unique'      => 'Email sudah digunakan pengguna lain.',
            'password.min'      => 'Password minimal 8 karakter.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun yang sedang digunakan.');
        }
        $nama = $user->name;
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$nama} berhasil dihapus.");
    }
}
