<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Homestay;
use App\Facility;
use DB;
use Auth;

class HomestayController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:landlord,web',['except' => ['index','search']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function homepage(){
    //     $homestays = Homestay::all();
    //     return view('index')->with('homestays', $homestays);
    // }
    
    public function index()
    {
        if (Auth::guard('landlord')->check()) {
            $landlord_id = Auth::guard('landlord')->user()->id; // get id of currently logged landlord
            $homestays = Homestay::where('landlord_id', $landlord_id)->get();
            return view('homestay.homestay-list')->with('homestays', $homestays);
        } else {
            $homestays = Homestay::all();
            $facilities = Facility::all();
            return view('index')->with('homestays', $homestays)->with('fac', $facilities);
        }
    }

    public function search(Request $request)
    {
        $facArr = $request->input('facilityArray.*');
        $sql = '';
        foreach ($facArr as $facility) {
            $sql .= ' facility_id = '.$facility.' OR';
        }
        //Remove last AND
        $words = explode( " ", $sql );
        array_splice( $words, -1 );
        $sql = implode( " ", $words );

        DB::enableQueryLog();
        // $h_f = DB::select(DB::raw('SELECT * FROM facility_homestay where'.$sql));
        $hs = Homestay::select('homestays.id', 'homestays.name', 'address', 'image', 'homestays.type', 'homestays.numRoom', 'homestays.price', 'homestays.landlord_id', DB::raw('COUNT(homestays.id)'))->join('facility_homestay', 'homestays.id', '=', DB::raw('facility_homestay.homestay_id WHERE'.$sql))->groupBy('homestays.id')->get();
        // dd($hs);
        // dd(DB::getQueryLog());
        // $hs = Homestay::where('type','=',$request->input('type'))->where('numRoom','=',$request->input('numRoom'))->where('price','=',$request->input('price'))->toSql();
        
        // $h_f2 = [];
        // foreach ($h_f as $key => $value) {
        //     $h_f2 = [$key => $value];
        // }
        // dd($h_f2);
        // $hs = Homestay::find($hs);
        $facilities = Facility::all();
        return view('index')->with('homestays', $hs)->with('fac', $facilities);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('homestay.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile('image')) {
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName."_".time().".".$extension;
            $path = $request->file('image')->storeAs('public/myimage', $fileNameToStore);
        } else {
            $fileNameToStore = 'noimage.jpg';
        }
        
        $homestay = new Homestay;
        $homestay->name = $request->input('name');
        $homestay->address = $request->input('address');
        $homestay->image = $fileNameToStore;
        $homestay->type = $request->input('type');
        $homestay->numRoom = $request->input('room');
        $homestay->price = $request->input('price');
        $homestay->landlord_id = Auth::guard('landlord')->user()->id;
        $homestay->save();

        return redirect('/homestay');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $homestay = Homestay::find($id);
        $facilities = new Facility;
        $notIn = $homestay->facilities->pluck('id')->toArray();
        $facilities = $facilities->whereNotIn('id',$notIn)->get();
        return view('homestay.show')->with('hs', $homestay)->with('fac', $facilities);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $homestay = Homestay::find($id);
        return view('homestay.edit')->with('hs', $homestay);
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
        if ($request->hasFile('image')) {
            $fileNameWithExt = $request->file('image')->getClientOriginalName();
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            $extension = $request->file('image')->getClientOriginalExtension();
            $fileNameToStore = $fileName."_".time().".".$extension;
            $path = $request->file('image')->storeAs('public/myimage', $fileNameToStore);
        }
        
        $homestay = Homestay::find($id);
        $homestay->name = $request->input('name');
        $homestay->address = $request->input('address');
        if($request->hasFile('image')){
            $homestay->image = $fileNameToStore;
        }
        $homestay->type = $request->input('type');
        $homestay->numRoom = $request->input('room');
        $homestay->price = $request->input('price');
        $homestay->save();

        return redirect('/homestay');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $homestay = Homestay::find($id);
        $homestay->delete();
        return redirect('/homestay');
    }

    public function removeFacility($hs_id, $fac_id)
    {
        $hs = Homestay::find($hs_id);
        $hs->facilities()->detach($fac_id);
        return redirect('/homestay/'.$hs_id);
    }

    public function assignFacility(Request $request)
    {
        $hs_id = $request->input('hs_id');
        $facArr = $request->input('facilityArray.*');
        
        $hs = Homestay::find($hs_id);
        $myArray = [];
        foreach ($facArr as $value) {
            $hs->facilities()->attach($value);
        }
        return redirect('/homestay/'.$hs_id);
    }
}
