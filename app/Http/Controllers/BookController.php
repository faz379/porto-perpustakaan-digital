<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use App\Exports\BooksExport;
use Maatwebsite\Excel\Facades\Excel;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['edit', 'update', 'destroy', 'show']);
        $this->middleware('check.ownership');
    }

    public function index(Request $request): View
    {
        $query = Book::query();

        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }

        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        } else {
            $query->get();
        }
        
        $books = $query->paginate(5);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'cover_image' => 'required|image|mimes:jpeg,png,jpg',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'file_path' => 'required|file|mimes:pdf',
        ]);

        $coverImage = $request->file('cover_image');
        $coverImagePath = $coverImage->storeAs('public/books', $coverImage->hashName());

        $filePath = $request->file('file_path');
        $filePathPath = $filePath->storeAs('public/books/files', $filePath->hashName());

        Book::create([
            'cover_image'   => $coverImage->hashName(),
            'title'         => $request->title,
            'category_id'   => $request->category_id,
            'description'   => $request->description,
            'quantity'      => $request->quantity,
            'file_path'     => $filePath->hashName(),
            'user_id'       => Auth::id(),
        ]);

        return redirect()->route('books.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    public function show(string $id)
    {
        $book = Book::findOrFail($id);

        // Admins can view any book; regular users can only view their own
        if (Auth::user()->role !== 'admin' && $book->user_id !== Auth::id()) {
            return redirect()->route('books.index')->withErrors('You do not have permission to view this book.');
        }

        $categories = Category::all();
        return view('books.show', compact('book'));
    }

    public function edit(string $id)
    {
        $book = Book::findOrFail($id);

        // Admins can edit any book; regular users can only edit their own
        if (Auth::user()->role !== 'admin' && $book->user_id !== Auth::id()) {
            return redirect()->route('books.index')->withErrors('You do not have permission to edit this book.');
        }

        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book): RedirectResponse
    {
    // Admins and book owners can update; authorization via policy
        $this->authorize('update', $book);

        $this->validate($request, [
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg',
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'file_path' => 'nullable|file|mimes:pdf',
        ]);

        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImage->storeAs('public/books', $coverImage->hashName());
            $book->cover_image = $coverImage->hashName();
        }

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path');
            $filePath->storeAs('public/books/files', $filePath->hashName());
            $book->file_path = $filePath->hashName();
        }

    // Set other attributes and save
        $book->title = $request->title;
        $book->category_id = $request->category_id;
        $book->description = $request->description;
        $book->quantity = $request->quantity;

        $book->save();

        return redirect()->route('books.index')->with(['success' => 'Data Berhasil Diupdate!']);
    }

    public function destroy(Book $book): RedirectResponse
    {
        // Admins and book owners can delete; authorization via policy
        $this->authorize('delete', $book);

        $book->delete();
        return redirect()->route('books.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

    public function export(Request $request)
    {
        $query = Book::query();
    
        if ($request->has('category_id') && $request->category_id != '') {
            $query->where('category_id', $request->category_id);
        }
    
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        }
    
        $books = $query->get();
        return Excel::download(new BooksExport($books), 'books.xlsx');
    }
    
}
