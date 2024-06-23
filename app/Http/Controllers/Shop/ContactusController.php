<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\ShopContactRequest;
use App\Models\Admin\Center;
use App\Models\Admin\ContactRequest;

class ContactusController extends Controller
{
    public function view()
    {
        $centers = Center::query()->orderBy('order')->get();
        return view('shop.contactus',compact('centers'));
    }

    public function store(ShopContactRequest $request)
    {
        $data = $request->validated();
        ContactRequest::create($data);

        return redirect()->back()->with('success',__('shop.contactus.store'));
    }
}
