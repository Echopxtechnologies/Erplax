<?php

namespace Modules\Book\Http\Controllers;

use App\Http\Controllers\Admin\AdminController;
use Modules\Book\Models\Book;
use Illuminate\Http\Request;

class BookController extends AdminController
{
    /**
     * Display a listing of books.
     */
    public function index()
    {
        $this->authorizeAdmin();
        $this->authorize('book.list.read');

        $books = Book::paginate(15);

        return view('book::index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $this->authorize('book.create.create');

        return view('book::create');
    }

    /**
     * Store a newly created book in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $this->authorize('book.create.create');
        try {
            $validated = $this->validateRequest($request, [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'author' => 'required|string|max:255',
            ]);

            $book = Book::create($validated);

            $this->logAction('create', ['entity' => 'Book', 'book_id' => $book->id, 'title' => $book->title]);

            return $this->redirectWithSuccess('admin.book.index', 'Book created successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to create Book', $e);

            return $this->redirectWithError('admin.book.create', 'Failed to create book: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified book.
     */
    public function show($id)
    {
        $this->authorizeAdmin();

        $book = Book::findOrFail($id);

        return view('book::show', compact('book'));
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit($id)
    {
        $this->authorizeAdmin();

        $book = Book::findOrFail($id);

        return view('book::edit', compact('book'));
    }

    /**
     * Update the specified book in storage.
     */
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        try {
            $book = Book::findOrFail($id);

            $validated = $this->validateRequest($request, [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'author' => 'required|string|max:255',
            ]);

            $book->update($validated);

            $this->logAction('update', ['entity' => 'Book', 'book_id' => $book->id, 'title' => $book->title]);

            return $this->redirectWithSuccess('admin.book.index', 'Book updated successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to update Book', $e);

            return $this->redirectWithError('admin.book.edit', 'Failed to update book: ' . $e->getMessage(), ['id' => $id]);
        }
    }

    /**
     * Remove the specified book from storage.
     */
    public function destroy($id)
    {
        $this->authorizeAdmin();

        try {
            $book = Book::findOrFail($id);
            $title = $book->title;

            $book->delete();

            $this->logAction('delete', ['entity' => 'Book', 'title' => $title]);

            return $this->redirectWithSuccess('admin.book.index', 'Book deleted successfully');
        } catch (\Exception $e) {
            $this->logError('Failed to delete Book', $e);

            return $this->redirectWithError('admin.book.index', 'Failed to delete book: ' . $e->getMessage());
        }
    }
}
