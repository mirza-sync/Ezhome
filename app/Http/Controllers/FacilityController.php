<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Facility;
use Auth;

class FacilityController extends Controller
{
    public function __construct()
    {
        //
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function homepage(){
    //     $facilitys = Homestay::all();
    //     return view('index')->with('homestays', $facilitys);
    // }
    
    public function index()
    {
        $facilitys = Facility::all();
        return view('facility.index')->with('facilitys', $facilitys);
    }

    public function search(Request $request)
    {
        $facilities = Facility::where('id','=',$request->input('facilies'))->where('numRoom','<=',$request->input('numRoom'))->where('price','<=',$request->input('price'))->get();
        foreach ($variable as $key => $value) {
            
        }
        
        // $hs = Homestay::where('type','=',$request->input('type'))->where('numRoom','=',$request->input('numRoom'))->where('price','=',$request->input('price'))->toSql();
        // dd($hs);
        return view('index')->with('homestays', $hs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facilitys = Facility::all();
        return view('facility.create')->with('facilitys', $facilitys);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $facility = new Facility;
        $facility->facilityName = $request->input('facilityName');
        $facility->save();

        return redirect('/facility')->with('success', 'Facility created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $facility = Facility::find($id);
        return view('facility.edit')->with('facility', $facility);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $facility = Facility::find($id);
        $facility->facilityName = $request->input('facilityName');
        $facility->save();

        return redirect('/facility')->with('success', 'Facility updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $facility = Facility::find($id);
        $facility->delete();
        return redirect('/facility')->with('success', 'Facility deleted');
    }
}
