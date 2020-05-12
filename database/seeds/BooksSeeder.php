<?php

use Illuminate\Database\Seeder;
use App\Author;
use App\Book;
use App\BorrowLog;
use App\User;

class BooksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Author sample
        $author1 = Author::create(['name' => 'M. Rudyanto Arief']);
        $author2 = Author::create(['name' => 'Yudhi Purwanto']);
        $author3 = Author::create(['name' => 'Andy Harris']);
        $author4 = Author::create(['name' => 'Achmad Solichin']);
        $author5 = Author::create(['name' => 'A.M. HIRIN & VIRGI']);

        // Book sample without Cover
        $book1 = Book::create(['title' => 'Pemograman Web Dinamis menggunakan PHP dan MySQL', 'amount' => 10, 'author_id' => $author1->id]);
        $book2 = Book::create(['title' => 'Pemograman Web dengan PHP', 'amount' => 5, 'author_id' => $author2->id]);
        $book3 = Book::create(['title' => 'PHP/MySQL Programming', 'amount' => 20, 'author_id' => $author3->id]);
        $book4 = Book::create(['title' => 'Pemograman Web dengan PHP dan MYSQL', 'amount' => 25, 'author_id' => $author4->id]);
        $book5 = Book::create(['title' => 'Cepat Mahir Pemograman Web dengan PHP dan MYSQL', 'amount' => 10, 'author_id' => $author5->id]);

        // Borrow book sample
        $member = User::where('email', 'member@gmail.com')->first();
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book1->id, 'is_returned' => 1]);
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book2->id, 'is_returned' => 0]);
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book3->id, 'is_returned' => 1]);
        BorrowLog::create(['user_id' => $member->id, 'book_id' => $book4->id, 'is_returned' => 0]);
    }
}
