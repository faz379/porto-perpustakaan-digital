<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background: lightgray;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            width: 200px;
            background-color: #343a40;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            padding: 10px;
            text-decoration: none;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575d63;
            text-decoration: none;
        }
        .content {
            margin-left: 220px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h4 class="text-center text-light">MENU</h4>
    <hr class="bg-light">

    <!-- Menampilkan nama user yang login -->
    <div class="text-center text-light mb-3">
        @auth
            <p>{{ Auth::user()->role === 'admin' ? 'Admin' : Auth::user()->name }}</p>
        @endauth
    </div>

    <a href="{{ route('books.index') }}">Daftar Buku</a>
    <a href="{{ route('categories.index') }}">Kategori Buku</a>

    <!-- Tombol Logout -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>
</div>
    <!-- Content -->
    <div class="container content mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div>
                    <h3 class="text-center my-4">PERPUSTAKAAN DIGITAL</h3>
                    <h5 class="text-center"><a href="">created by</a></h5>
                    <h5 class="text-center"><a href="https://www.instagram.com/fazelianprmd/">Fazelian Alsya Pramudia</a></h5> 
                    <hr>
                </div>
                <div class="card border-0 shadow-sm rounded">
                    <div class="card-body">
                        <!-- Form Filter -->
                        <form method="GET" action="{{ route('books.index') }}">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="category">Filter Berdasarkan Kategori</label>
                                    <select id="category" name="category_id" class="form-control">
                                        <option value="">Semua Kategori</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2">
                                    <button type="submit" class="btn btn-primary mt-4">Filter</button>
                                </div>
                            </div>
                        </form>
                        <a href="{{ route('books.create') }}" class="btn btn-md btn-success mb-3">TAMBAH BUKU</a>
                        <a href="{{ route('books.export') }}" class="btn btn-md btn-success mb-3">EXPORT EXCEL</a>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">COVER BUKU</th>
                                    <th scope="col">JUDUL</th>
                                    <th scope="col">KATEGORI</th>
                                    <th scope="col">DESKRIPSI</th>
                                    <th scope="col">JUMLAH</th>
                                    <th scope="col">FILE</th>
                                    <th scope="col">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><img src="{{ asset('storage/books/' . $book->cover_image) }}" width="100"></td>
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->category->name }}</td>
                                        <td>{{ $book->description }}</td>
                                        <td>{{ $book->quantity }}</td>
                                        <td><a href="{{ asset('storage/books/files/' . $book->file_path) }}" target="_blank">Download</a></td>
                                        <td class="text-center">
                                                <a href="{{ route('books.show', $book->id) }}" class="btn btn-sm btn-warning">SHOW</a>
                                                <a href="{{ route('books.edit', $book->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                                <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('books.destroy', $book->id) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">DELETE</button>
                                                </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada buku yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        {{ $books->links() }}
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
