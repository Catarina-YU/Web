<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::find($request->user_id);

        if ($user->debt_amount > 0) {
            return redirect()->back()
                ->withErrors(['debt_pending' => 'O usuário possui débitos pendentes e não pode realizar novos empréstimos.'])
                ->withInput();
        }

        $openBorrowingForBook = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->first();

        if ($openBorrowingForBook) {
            return redirect()->back()
                ->withErrors(['book_unavailable' => 'Este livro já está emprestado e não foi devolvido.'])
                ->withInput();
        }

        $borrowedBooksCount = Borrowing::where('user_id', $user->id)
            ->whereNull('returned_at')
            ->count();

        if ($borrowedBooksCount >= 5) {
            return redirect()->back()
                ->withErrors(['limit_exceeded' => 'O usuário já atingiu o limite de 5 livros emprestados.'])
                ->withInput();
        }

        Borrowing::create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $borrowedAt = Carbon::parse($borrowing->borrowed_at);
        $returnedAt = now();
        $dueDate = $borrowedAt->addDays(15);
        $fineAmount = 0;

        if ($returnedAt->greaterThan($dueDate)) {
            $daysLate = $returnedAt->diffInDays($dueDate);
            $fineAmount = $daysLate * 0.50;

            $user = $borrowing->user;
            $user->debt_amount += $fineAmount;
            $user->save();
        }

        $borrowing->update([
            'returned_at' => $returnedAt,
        ]);

        $message = 'Devolução registrada com sucesso.';
        if ($fineAmount > 0) {
            $message .= " Multa de R$ " . number_format($fineAmount, 2, ',', '.') . " foi adicionada ao débito do usuário.";
        }

        return redirect()->route('books.show', $borrowing->book_id)->with('success', $message);
    }

    public function clearDebt(User $user)
    {
        $user->debt_amount = 0.00;
        $user->save();

        return redirect()->route('users.show', $user)->with('success', 'Débito do usuário zerado com sucesso.');
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }
}
