<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\LoanCollection;
use App\Http\Resources\API\LoanResource;
use App\Models\Book;
use App\Models\Loan;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoanController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::paginate(10);

        $loansCollection = new LoanCollection($loans, 'Loans retrieved successfully');

        return $loansCollection->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'loan_date' => 'required|date',
                'return_date' => 'required|date|after_or_equal:loan_date',
                'status' => 'sometimes|in:active,borrowed,delayed,returned'
            ]);

            $book = Book::findOrFail($validatedData['book_id']);

            if ($book->status !== 'available') {
                throw new \Throwable("Book is not available for loan");
            }

            $createdLoan = Loan::create($validatedData);

            $message = 'Loan created successfully';

            $sucessResponse = new LoanResource($createdLoan, $message);

            return $sucessResponse
                ->response()
                ->setStatusCode(Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return $this->sendValidationException('Failed to validate loan', $e);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to create loan', $th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $foundLoan = Loan::findOrFail($id);

            $message = 'Loan retrieved successfully';

            $sucessResponse = new LoanResource($foundLoan, $message);

            return $sucessResponse->response();
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to find loan', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to find loan', $th);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                'book_id' => 'required|exists:books,id',
                'loan_date' => 'required|date',
                'return_date' => 'required|date|after_or_equal:loan_date',
                'status' => 'sometimes|in:active,borrowed,delayed,returned'
            ]);

            $foundLoan = Loan::findOrFail($id);

            $foundLoan->update($validatedData);

            $message = 'Loan updated successfully';

            $sucessResponse = new LoanResource($foundLoan, $message);

            return $sucessResponse->response();
        } catch (ValidationException $e) {
            return $this->sendValidationException('Failed to validate loan', $e);
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to update loan', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to update loan', $th);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $foundLoan = Loan::findOrFail($id);

            $foundLoan->delete();

            return $this->sendBaseResponse('Loan deleted successfully');
        } catch (ModelNotFoundException $e) {
            return $this->sendThrowable('Failed to delete loan', $e, Response::HTTP_NOT_FOUND);
        } catch (\Throwable $th) {
            return $this->sendThrowable('Failed to delete loan', $th);
        }
    }
}
