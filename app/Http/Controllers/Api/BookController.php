<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function createBook(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'string',
            'book_cost' => 'required|integer',
        ]);

        $book = new Book();
        $book->title = $request->title;
        $book->description = $request->description ? $request->description : null;
        $book->book_cost = $request->book_cost;
        $book->author_id = auth()->user()->id;
        $book->save();

        return response()->json([
            "status" => 1,
            "message" => "Book Created",
            "data" => $book
        ], 201);
    }

    public function listBooks()
    {
        $books = Book::all();

        return response()->json([
            "status" => 1,
            "message" => "Book Fetched",
            "data" => $books
        ], 200);
    }

    public function authorBook()
    {
        $authorId = auth()->user()->id;

        //has many function inside Authoir Model linked to Book model
        $books = Author::find($authorId)->authorBooks;

        return response()->json([
            "status" => 1,
            "message" => "Book Fetched",
            "data" => $books
        ], 200);
    }

    public function singleBook($id)
    {
        $authorId = auth()->user()->id;
        $book = Book::where(['id' => $id, 'author_id' => $authorId])->first();

        if ($book) {
            return response()->json([
                "status" => true,
                "message" => "Book Fetched",
                "data" => $book
            ], 200);
        }
        return response()->json([
            "status" => false,
            "message" => "No Book Found",
        ], 404);
    }

    public function updateBook(Request $request, $id)
    {
        $request->validate([
            'title' => 'string',
            'description' => 'string',
            'book_cost' => 'integer',
        ]);
    
        $authorId = auth()->user()->id;
        $book = Book::where(['id' => $id, 'author_id' => $authorId])->first();

        if (!$book) {
            return response()->json([
                "status" => false,
                "message" => "No Book Found",
            ], 404);
        }

        $book->title = isset($request->title) ? $request->title : $book->title;
        $book->description = isset($request->description) ? $request->description : $book->description;
        $book->book_cost = isset($request->book_cost) ? $request->book_cost : $book->book_cost;
        $book->save();

        return response()->json([
            "status" => true,
            "message" => "Book Updated",
            "data" => $book
        ], 201);
    }

    public function deleteBook($id)
    {
        $authorId = auth()->user()->id;
        $book = Book::where(['id' => $id, 'author_id' => $authorId])->first();
        $book->delete();

        return response()->json([
            "status" => 1,
            "message" => "Book Deleted Successfully",
        ], 200);
    }
}