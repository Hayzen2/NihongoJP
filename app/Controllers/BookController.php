<?php
namespace App\Controllers;

use App\Models\BookModel;

class BookController {
    private $bookModel;

    public function __construct() {
        $this->bookModel = new BookModel();
    }

    // Show all books, optionally filter by JLPT level
    public function showBooks() {
        $jlpt = $_GET['jlpt'] ?? '';

        if ($jlpt) {
            $books = $this->bookModel->getBooksByJlptLevel($jlpt);
        } else {
            $books = $this->bookModel->getAllBooks();
        }

        render('buyBooks/book-list', [
            'books' => $books,
            'jlptFilter' => $jlpt,
            'styles' => [],
            'scripts' => []
        ]);
    }
}
?>