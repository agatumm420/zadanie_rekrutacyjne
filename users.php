<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
class UserController extends Controller
{
    public function updateUsers(Request $request){
         $res=$request->input('data');
        $users=$res['users'];
        foreach ($users as $user) {
    	try {
    		if ($user['name'] && $user['login'] && $user['email'] && $user['password'] && strlen($user['name']) >= 10)
    			DB::table('users')->where('id', $user['id'])->upsert([ //lepiej byłboby pracować na modelu usera
    				'name' => $user['name'],
    				'login' => $user['login'],
    				'email' => $user['email'],
    				'password' => md5($user['password'])

    			],[ 'email'], ['name', 'login', 'email', 'password']);
    	} catch (\Throwable $e) {
    		return Redirect::back()->withErrors(['error', 'We couldn\'t update user: ' . $e->getMessage()]);
    	}
         }
         return Redirect::back()->with(['success', 'All users updated.']);
    }

     public function storeUsers(Request $request){
    $res=$request->input('data');
    $users=$res['users'];

    foreach ($users as $user) {

        try {
    		if ($user['name'] && $user['login'] && $user['email'] && $user['password'] && strlen($user['name']) >= 10)

    			DB::table('users')->upsert([  //lepiej byłboby pracować na modelu usera
    				'name' => $user['name'],
    				'login' => $user['login'],
    				'email' => $user['email'],
    				'password' => md5($user['password'])
                ],[ 'email'], ['name', 'login', 'email', 'password']);

        } catch (\Throwable $e) {
            return Redirect::back()->withErrors(['error', ['We couldn\'t store user: ' . $e->getMessage()]]);
        }
    }
    if ($this->sendEmail($users)) {
        return Redirect::back()->with(['success', 'All users created.']);
    }
    }

    private function sendEmail($users){

    foreach ($users as $user) {

        if ($user['email']) {
            Mail::to($user['email'])->send(new UserCreated($user));
        }
    }
    return true;
    }
}


