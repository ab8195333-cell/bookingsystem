<?php
namespace App\Http\Controllers;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreController extends Controller {
    public function create() { return view('store.register'); }

    public function store(Request $request) {
        $request->validate(['name' => 'required', 'slug' => 'required|unique:stores,slug']);
        $store = Store::create([
            'name' => $request->name,
            'slug' => Str::slug($request->slug),
            'user_id' => auth()->id(),
        ]);
        auth()->user()->update(['store_id' => $store->id]);
        return redirect()->route('dashboard');
    }
}