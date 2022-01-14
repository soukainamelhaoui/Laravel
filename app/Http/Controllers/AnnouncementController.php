<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Article;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $announcements = Announcement::latest()->paginate(9);
        return view('announcements',['announcements'=>$announcements,'user'=>$user]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $announcement = Announcement::findOrFail($id);
        $article = Article::findOrFail($announcement->article_id);
        $transactions = $announcement->transactions;
        
        foreach($transactions as $transaction){
            $temp = Transaction::findOrFail($transaction->id);
            $transaction->user = $temp->user;
        }


        return view('announcement',[
                                    'announcement'=>$announcement,
                                    'article'=>$article,
                                    'transactions'=>$transactions,
                                    'user'=>$user
                                    ]);
    }

    public function create(){
        $user = Auth::user();

        return view('createAnnouncementForm',['user'=>$user]);
    }

    public function store(Request $request){
        $article = new Article();
        $article->title = $request->input('articleName');
        $article->brand = $request->input('marque');
        $article->text = $request->input('texte');

        if($request->hasFile('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $fileName = time().'.'.$extension;
            $file->move(public_path('images'), $fileName);
            $article->photo = $fileName;
        }
        

        $article->save();

        $id = Auth::id(); 


        $announcement = new Announcement();
        $announcement->title = $request->input('AnnouncementTitle');
        $announcement->price = $request->input('prix');
        $announcement->description = $request->input('description');
    
        $announcement->user_id = $id;
        $announcement->article_id= $article->id;

        $announcement->image = $fileName;
        

        $announcement->save();

        return redirect('/announcements/'.$announcement->id);
    }


    public function edit($id){
        $user = Auth::user();
        $announcement = Announcement::findOrFail($id);

        return view('editAnnouncementForm', ['announcement'=>$announcement,'user'=>$user]);
    }

    public function update($id){
        $announcement = Announcement::findOrFail($id);

        $announcement->title = request('titre');
        $announcement->price = request('prix');
        $announcement->description = request('description');

        $announcement->update();

        return redirect('/announcements/'.$id);
    }
    
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $article = Article::findOrFail($announcement->article_id);
        
        $article->delete();
        $announcement->delete();

        return redirect('/home');
    }
}