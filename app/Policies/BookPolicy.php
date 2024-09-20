<?php
namespace App\Policies;

use App\Models\Book;
use App\Models\User;

class BookPolicy
{
    /**
     * Determine if the given book can be updated by the user.
     */
    public function update(User $user, Book $book): bool
    {
        // Misalnya, hanya pemilik buku yang dapat mengupdate buku
        return $user->id === $book->user_id;
    }

    /**
     * Determine if the given book can be deleted by the user.
     */
    public function delete(User $user, Book $book): bool
    {
        // Misalnya, hanya pemilik buku atau admin yang dapat menghapus buku
        return $user->id === $book->user_id || $user->role === 'admin';
    }
}
