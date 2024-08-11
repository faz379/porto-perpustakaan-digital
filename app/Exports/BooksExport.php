<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class BooksExport implements FromCollection, WithHeadings
{
    protected $books;

    public function __construct(Collection $books)
    {
        $this->books = $books;
    }

    public function collection()
    {
        return $this->books;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Category',
            'Description',
            'Quantity',
            'File Path',
            'Cover Image',
            'Created At',
            'Updated At',
        ];
    }
}
