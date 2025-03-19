<?php

namespace App\Http\Controllers\Api;

use App\Models\Quote;
use App\Http\Requests\StoreQuoteRequest;
use App\Http\Requests\UpdateQuoteRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\Http\Resources\QuoteCollection;
use App\Http\Resources\QuoteResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Exceptions\HttpResponseException;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return new QuoteCollection(Quote::all());
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuoteRequest $request)
    {
        //
        // return response()->json(['user'=>Auth::user()->id]);
        $userID = Auth::user()->id;
        $validate = $request->validate([
            'quote' => ['required','string'],
            'author' => ['required','string'],
            'source' => ['required','string'],
            'tags' => ['required','array'],
            'categories' => ['required','array']
        ]);
        $wordCount = str_word_count($request->quote);
       $quote =  Quote::create([
            'quote' => $request->quote,
            'author' => $request->author,
            'source' => $request->source,
            'word_count' => $wordCount,
            'user_id' => $userID ?? null,
            
        ]);
        foreach($request->tags as $tag){
            $quote->tags()->attach($tag);
        }
        return response()->json([
            'message' => 'User ' . Auth::user()->name . ' added a quote successfully',
            
        ],201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Quote $quote)
    {
        //
        $this->incrementViews($quote);
        return new QuoteResource($quote);
    }

  

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateQuoteRequest $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'quote' => ['required','string'],
            'author' => ['required','string'],
            'source' => ['required','string'],
            'quote_id' => ['required','numeric', 'exists:quotes,id']
        ]);


        if ($validator->fails()) {
            return response()->json([
            'errors' => $validator->errors()
            ], 422);
        }
        $quote = Quote::find($request->quote_id);
        
        // Gates 
        if(!Gate::alows('manage_quote',$quote)){
            abort(403,'Unauthorized Action');
        }

        $newWordCount = str_word_count($request->quote);
        $quote->update([
            'quote' => $request->quote,
            'author' => $request->author,
            'source' => $request->source,
            'word_count' => $newWordCount,
        ]);
        return response()->json([
            'message' => 'Quote Number : ' . $quote->id . 'has been edited succesfully ',
            
        ],201);
        

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $validate = Validator::make(['id' => $id], [
            'id' => ['required','numeric']
        ]);
        $quote = Quote::find($id);
        if(!Gate::alows('manage_quote',$quote)){
            abort(403,'Unauthorized Action');
        }
        // return response()->json(['user'=>Auth::user()->id]);
        $quote->delete();

        if($quote->delete()){

            return response()->json([
                'message' => 'Quote Number : ' . $id . 'has been deleted successfully',
            ],201);
        }else {
            return response()->json([
                'message' => 'Quote Number : ' . $id . ' has not been deleted ',
            ],500);
        }

    }
    public function randomQuote(Request $request){

        // $request->validate([
        //     'number' => ['numeric','min:1']
        // ]);
        $validator = Validator::make($request->all(), [
            'number' => ['numeric', 'min:1']
        ]);
        // return response()->json(['randomQuote' => $request->number]);
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'errors' => $validator->errors()
            ], 422));
        }

        if($request->has('number')){
            $quote = Quote::inRandomOrder()->take($request->number)->get();

        }else{
            $quote = Quote::inRandomOrder()->first();
        }

        $this->incrementViews($quote);

        return response()->json(['randomQuote' => $quote]);
    }
    public function filteredQuotes(Request $request){

        $validate = $request->validate([
            'number' => ['required','numeric']
        ]);
        $quotes = Quote::where('word_count', '<', $request->number);

        if ($request->has('order') && in_array($request->order, ['asc', 'desc'])) {
            $quotes = $quotes->orderBy('created_at', $request->order);
        }

        return new QuoteCollection($quotes->get());
    }
    public function showAllQuotes(){
        $quotes = Quote::all();
        $data = new QuoteCollection($quotes);
        return response()->json([
            'user' => Auth::user()->name,
            'userQuotes' => $data
        ]);
    }
    public function showUserQuotes(){
        $quotes = Quote::where('user_id',Auth::user()->id)->get();
        $data = new QuoteCollection($quotes);
        return response()->json([
            'user' => Auth::user()->name,
            'userQuotes' => $data
        ]);
    }
    private function incrementViews($quotes){
        
        foreach ($quotes as $quote) {
            
            $quote->increment('view_count');
        }
        return response()->json([
            'message' => 'Quote Number : ' . $quote->id . ' has been viewed successfully',
            
        ],201);
    }
     public function allPopularQuotes(){
        $quotes = Quote::orderByDesc('view_count')->take(3)->get();
        return response([
            'quotes' => $quotes
        ],200);
     }
     public function userPopularQuotes(){
        $quotes = Quote::where('user_id',Auth::user()->id)->orderByDesc('view_count')->take(3)->get();
        return response([
            'quotes' => $quotes
        ],200);
     }
}
