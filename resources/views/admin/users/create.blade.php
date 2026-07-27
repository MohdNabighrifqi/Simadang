{{-- resources/views/admin/users/create.blade.php --}}
@extends('layouts.admin')
@section('title','Tambah Pengguna')
@section('content')

@include('partials.breadcrumb', ['items' => [
    ['label' => 'Dashboard', 'route' => route('admin.dashboard')],
    ['label' => 'Kelola Pengguna', 'route' => route('admin.users.index')],
    ['label' => 'Tambah Pengguna'],
]])

    <div class="page-header">
        <h2>Tambah Pengguna Baru</h2>
    </div>
    <div class="card">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form')
            <div class="form-actions">
                <button type="submit" class="btn btn-teal">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-gray">Batal</a>
            </div>
        </form>
    </div>
@endsection
