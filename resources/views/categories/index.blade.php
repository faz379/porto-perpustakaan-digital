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
        <a href="{{ route('books.my') }}">Daftar Buku</a>
        <a href="{{ route('books.index') }}">Daftar Buku Saya</a>
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
                        <a href="{{ route('categories.create') }}" class="btn btn-md btn-success mb-3">TAMBAH KATEGORI</a>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">NO</th>
                                    <th scope="col">KATEGORI</th>
                                    <th scope="col">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ $category->id }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-sm btn-warning">SHOW</a>
                                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-primary">EDIT</a>
                                            <form onsubmit="return confirm('Apakah Anda Yakin ?');" action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display: inline-block;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">DELETE</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada kategori yang tersedia</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            
                        </table>
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
