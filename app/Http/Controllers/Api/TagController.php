<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTagRequest;
use Illuminate\Support\Facades\Validator;


class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        //
        $validate = Validator::make($request->all());

        Tag::create([
            'tag_name' => $request->tag_name
        ]);
        return response([
            'Message' => 'Tag added with success'
        ],200);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
