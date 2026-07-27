{{-- resources/views/admin/users/edit.blade.php --}}
@extends('layouts.admin')
@section('title','Edit Pengguna')
@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Kelola Pengguna', 'route' => route('admin.users.index')],
    ['label' => 'Edit ' . $user->name],
]])

    <div class="page-header">
        <h2>Edit Pengguna</h2>
    </div>
    <div class="card">
        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
            @csrf @method('PUT')
            @include('admin.users._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-teal">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-gray">Batal</a>
            </div>
        </form>
    </div>
@endsection
