<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\User;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index() {

        $arr = [
            'listings' => Listing::latest()
                ->filter(request(['tag', 'search']))
                ->paginate(6),
        ];
        return view('listings.index', $arr);
    }


    public function show(Listing $listing) {
        return view('listings.show', [
            'listing' => $listing
        ]);
    }

    //Show the create form
    public function create() {
        return view('listings.create');
    }

    //Store listing data on DB
    public function store(Request $request) {

       $formFields = $request->validate([
            'title' => 'required',
            'logo' => 'nullable',
            'company' => 'required|unique:listings,company',
            'location' => 'required',
            'website' => 'required',
            'email' => 'required|email',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formFields['user_id'] = auth()->id();

        Listing::create($formFields);

        return redirect()->route('listings.index')->with('message', 'Listing created successfully');
    }

    //Show edit form
    public function edit(Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            return redirect()->route('listings.index')->with('message', "You can't update someone else gigs");
        }

        return view('listings.edit', ['listing' => $listing]);
    }

    //Update listing
    public function update(Request $request, Listing $listing)
    {
        
        $formFields = $request->validate([
            'title' => 'required',
            'company' => 'required',
            'location' => 'required',
            'website' => 'required',
            'email' => 'required|email',
            'tags' => 'required',
            'description' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $formFields['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formFields);

        return redirect()->route('listings.index')->with('message', 'Listing updated successfully');
    }

    public function destroy(Listing $listing) {
        
        if ($listing->user_id != auth()->id()) {
            return redirect()->route('listings.index')->with('message', "You can't update someone else gigs");
        }

        $listing->delete();

        return redirect()->route('listings.index')->with('message', 'Listing deleted successfully');
    }

    public function manage() {
        
        $listings = auth()->user()->listing()->get();

        return view('listings.menage', compact('listings'));
    }
}
