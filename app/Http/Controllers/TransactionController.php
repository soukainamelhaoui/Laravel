<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Announcement;

class TransactionController extends Controller
{
    

    public function create($id)
    {
        $user = Auth::user();
        
        return view('transaction',['user'=>$user,'announcementId'=>$id]);
    }

    public function store($id)
    {
        $user = Auth::user();
        $announcement = Announcement::findOrFail($id);

        $newTransaction = new Transaction();
        $newTransaction->announcement_id = $id;
        $newTransaction->user_id = $user->id;
        $newTransaction->evaluation = request('evaluation');
        $newTransaction->comments = request('comments');
        $newTransaction->date = date("Y/m/d");
        $newTransaction->price = $announcement->price;

        $newTransaction->save();

        
        return redirect('/announcements/'.$id);
    }
}