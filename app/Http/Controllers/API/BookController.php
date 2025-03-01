<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\BookCollection;
use App\Http\Resources\API\BookResource;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::paginate(10);

        $booksCollection = new BookCollection($books, 'Books retrieved successfully');

        return $booksCollection->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'registration_number' => 'required|string|max:50|unique:books',
                'status' => 'sometimes|in:available,borrowed',
                'genres' => 'sometimes|array',
                'genres.*' => 'exists:genres,name'
            ]);

            DB::beginTransaction();

            $createdBook = Book::create([
                'name' => $validatedData['name'],
                'author' => $validatedData['author'],
                'registration_number' => $validatedData['registration_number'],
                'status' => $validatedData['status'],
            ]);

            if ($request->has('genres')) {
                $genreIds = Genre::whereIn('name', $request->genres)
                    ->pluck('id')
                    ->toArray();

                $createdBook->genres()->attach($genreIds);
            }

            DB::commit();

            $message = 'Book created successfully';

            $sucessResponse = new BookResource($createdBook, $message);

            return $sucessResponse
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->sendValidationException('Failed to validate user', $e);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendThrowable('Failed to create user', $th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $foundBook = Book::findOrFail($id);

            $message = 'Book retrieved successfully';

            $sucessResponse = new BookResource($foundBook, $message);

            return $sucessResponse->response();
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to find book', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to find book', $th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'author' => 'required|string|max:255',
                'registration_number' => 'required|string|max:50|unique:books',
                'status' => 'sometimes|in:available,borrowed',
                'genres' => 'sometimes|array',
                'genres.*' => 'exists:genres,name'
            ]);

            $foundBook = Book::findOrFail($id);

            DB::beginTransaction();

            $foundBook->update([
                'name' => $validatedData['name'],
                'author' => $validatedData['author'],
                'registration_number' => $validatedData['registration_number'],
                'status' => $validatedData['status'],
            ]);

            if ($request->has('genres')) {
                $genreIds = Genre::whereIn('name', $request->genres)
                    ->pluck('id')
                    ->toArray();

                $foundBook->genres()->sync($genreIds);
            }

            DB::commit();

            $message = 'Book updated successfully';

            $sucessResponse = new BookResource($foundBook, $message);

            return $sucessResponse->response();
        } catch (ValidationException $e) {
            DB::rollBack();
            return $this->sendValidationException('Failed to validate book', $e);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return $this->sendThrowable('Failed to update book', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendThrowable('Failed to update book', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $foundBook = Book::findOrFail($id);

            $foundBook->delete();

            return $this->sendBaseResponse('Book deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to delete book', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to delete book', $th);
        }
    }
}
