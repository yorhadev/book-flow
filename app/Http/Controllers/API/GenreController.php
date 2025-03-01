<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\GenreCollection;
use App\Http\Resources\API\GenreResource;
use App\Models\Genre;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class GenreController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $genres = Genre::paginate(10);

        $genresCollection = new GenreCollection($genres, 'Genres retrieved successfully');

        return $genresCollection->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $createdGenre = Genre::create($validatedData);

            $message = 'Genre created successfully';

            $sucessResponse = new GenreResource($createdGenre, $message);

            return $sucessResponse
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->sendValidationException('Failed to validate genre', $e);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to create genre', $th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $foundGenre = Genre::findOrFail($id);

            $message = 'Genre retrieved successfully';

            $sucessResponse = new GenreResource($foundGenre, $message);

            return $sucessResponse->response();
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to find genre', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to find genre', $th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255'
            ]);

            $foundGenre = Genre::findOrFail($id);

            $foundGenre->update($validatedData);

            $message = 'Genre updated successfully';

            $sucessResponse = new GenreResource($foundGenre, $message);

            return $sucessResponse->response();
        } catch (ValidationException $e) {
            return $this->sendValidationException('Failed to validate genre', $e);
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to update genre', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to update genre', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $foundGenre = Genre::findOrFail($id);

            $foundGenre->delete();

            return $this->sendBaseResponse('Genre deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to delete genre', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to delete genre', $th);
        }
    }
}
