<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\BaseResource;
use App\Http\Resources\API\UserCollection;
use App\Http\Resources\API\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::paginate(10);

        $usersCollection = new UserCollection($users, 'Users retrieved successfully');

        return $usersCollection->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users',
                'registration_number' => 'required|string|max:50|unique:users'
            ]);

            $createdUser = User::factory()->create($validatedData);

            $message = 'User created successfully';

            $sucessResponse = new UserResource($createdUser, $message);

            return $sucessResponse
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->sendValidationException('Failed to validate user', $e);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to create user', $th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $foundUser = User::findOrFail($id);

            $message = 'User retrieved successfully';

            $sucessResponse = new UserResource($foundUser, $message);

            return $sucessResponse->response();
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to find user', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to find user', $th);
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
                'email' => 'required|email|max:255|unique:users',
                'registration_number' => 'required|string|max:50|unique:users'
            ]);

            $foundUser = User::findOrFail($id);

            $foundUser->update($validatedData);

            $message = 'User updated successfully';

            $sucessResponse = new UserResource($foundUser, $message);

            return $sucessResponse->response();
        } catch (ValidationException $e) {
            return $this->sendValidationException('Failed to validate user', $e);
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to update user', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to update user', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $foundUser = User::findOrFail($id);

            $foundUser->delete();

            return $this->sendBaseResponse('User deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to delete user', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to delete user', $th);
        }
    }
}
