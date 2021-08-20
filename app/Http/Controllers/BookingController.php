<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Homestay;
use App\Booking;
use Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:web,landlord',['except' => ['create','checkout','checkoutChosen','scroll']]);
        $this->middleware('auth:web,landlord');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guard('landlord')->check()) {
            $landlord_id = Auth::guard('landlord')->user()->id;
            $homestays = Homestay::where('landlord_id',$landlord_id)->get();
            return view('booking.landlord-booking-list')->with('homestays',$homestays);
        } else {
            $user_id = Auth::guard('web')->user()->id;
            $bookings = Booking::where('user_id',$user_id)->get();
            // $bookings = Booking::where('user_id',$user_id)->toSql();
            // dd($bookings);
            return view('booking.user-booking-list', compact('bookings',$bookings));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDateRangeArray($strDateFrom,$strDateTo)
    {
        // takes two dates formatted as YYYY-MM-DD and creates an
        // inclusive array of the dates between the from and to dates.

        // could test validity of dates here but I'm already doing
        // that in the main script

        $aryRange=array();

        $iDateFrom=mktime(1,0,0,substr($strDateFrom,5,2),     substr($strDateFrom,8,2),substr($strDateFrom,0,4));
        $iDateTo=mktime(1,0,0,substr($strDateTo,5,2),     substr($strDateTo,8,2),substr($strDateTo,0,4));

        if ($iDateTo>=$iDateFrom)
        {
            array_push($aryRange,date('Y-m-d',$iDateFrom)); // first entry
            while ($iDateFrom<$iDateTo)
            {
                $iDateFrom+=86400; // add 24 hours
                array_push($aryRange,date('Y-m-d',$iDateFrom));
            }
        }
        return $aryRange;
    }

    public function create($homestay_id)
    {
        $homestay = Homestay::find($homestay_id);
        $booked = [];
        foreach ($homestay->booking as $hs) {
            $bookedRange = self::createDateRangeArray($hs->checkInDate, $hs->checkOutDate);
            array_push($booked, ...$bookedRange);
        }
        // dd($booked);
        return view('booking.create')->with('hs', $homestay)->with('booked', $booked);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $booking = new Booking;
        $booking->user_id = Auth::guard('web')->user()->id;
        $booking->homestay_id = $request->input('homestay_id');
        $booking->bookingDate = date('Y-m-d ');
        $booking->checkInDate = $request->input('checkInDate');
        $booking->checkOutDate = $request->input('checkOutDate');
        $booking->save();
        return redirect()->route('booking.index')->with('success','Booking successful');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $booking = Booking::find($id);
        $to = \Carbon\Carbon::createFromFormat('Y-m-d', $booking->checkOutDate);
        $from = \Carbon\Carbon::createFromFormat('Y-m-d', $booking->checkInDate);
        $diff_in_days = $to->diffInDays($from);
        $price = $booking->homestay->price;
        $totalPrice = $diff_in_days*$price;
        return view('booking.user-booking')->with('booking', $booking)->with('total',$totalPrice)->with('days',$diff_in_days);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $booking = Booking::find($id);
        $booking->delete();
        return redirect()->route('booking.index')->with('success', 'Booking deleted');
    }

    function find_closest($array, $date)
    {
        // //$count = 0;
        // foreach($array as $day)
        // {
        //     //$interval[$count] = abs(strtotime($date) - strtotime($day));
        //     $interval[] = abs(strtotime($date) - strtotime($day));
        //     //$count++;
        // }

        // asort($interval);
        // $closest = key($interval);

        // echo $array[$closest];
        
        usort($array, "self::date_sort");
        $nextDate = '2050-12-12';
        foreach ($array as $count => $dateSingle) {
            if (strtotime($date) < strtotime($dateSingle))  {
                $nextDate = date('Y-m-d', strtotime($dateSingle));
                break;
            }
        }
        return $nextDate;
    }
    function date_sort($a, $b) {
        return strtotime($a) - strtotime($b);
    }

    public function checkout(Request $request) {
        $month = $request->month;
        $year = $request->year;
        $checkin = $request->checkin;
        $homestay_id = $request->homestay_id;

        $homestay = Homestay::find($homestay_id);
        $booked = [];
        foreach ($homestay->booking as $hs) {
            $bookedRange = self::createDateRangeArray($hs->checkInDate, $hs->checkOutDate);
            array_push($booked, ...$bookedRange);
        }

        $myclose = self::find_closest($booked, $checkin);

        // Create array containing abbreviations of days of week.
        $daysOfWeek = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
    
        // What is the first day of the month in question?
        $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    
        // How many days does this month contain?
        $numberDays = date('t',$firstDayOfMonth);
    
        // Retrieve some information about the first day of the
        // month in question.
        $dateComponents = getdate($firstDayOfMonth);
    
        // What is the name of the month in question?
        $monthName = $dateComponents['month'];
    
        // What is the index value (0-6) of the first day of the
        // month in question.
        $dayOfWeek = $dateComponents['wday'];
    
        // Create the table tag opener and day headers
        $datetoday = date('Y-m-d');
    
        $calendar = "<table class='table table-bordered'>";
        $calendar .= "<center><h2>$monthName $year</h2>";
        
        $calendar.= "<button class='btn btn-xs btn-primary' onclick='thescroll(".date('m', mktime(0, 0, 0, $month-1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month-1, 1, $year)).")'><</button> ";

        $calendar.= " <button class='btn btn-xs btn-primary' onclick='thescroll(".date('m').",".date('Y').")'>Current Month</button> ";

        $calendar.= "<button class='btn btn-xs btn-primary' onclick='thescroll(".date('m', mktime(0, 0, 0, $month+1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month+1, 1, $year)).")'>></button></center><br>";
        
        $calendar .= "<tr>";
    
        // Create the calendar headers
        foreach($daysOfWeek as $day) {
            $calendar .= "<th  class='header'>$day</th>";
        } 
    
        // Create the rest of the calendar
        // Initiate the day counter, starting with the 1st.
        $currentDay = 1;
    
        $calendar .= "</tr><tr>";
    
        // The variable $dayOfWeek is used to
        // ensure that the calendar
        // display consists of exactly 7 columns
        if ($dayOfWeek > 0) { 
            for($k=0;$k<$dayOfWeek;$k++){
                $calendar .= "<td  class='empty'></td>"; 
            }
        }
    
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    
        while ($currentDay <= $numberDays) {
    
            // Seventh column (Saturday) reached. Start a new row.
    
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }
            
            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            $dayname = strtolower(date('l', strtotime($date)));
            $eventNum = 0;
            $today = $date==date('Y-m-d')? "invisible" : "";

            

            if(in_array($date, $booked)){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm' disabled>Booked</button>";
            }
            elseif ($date>$myclose) {
                // dd($date.">".$myclose);
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm invisible' disabled>NOPE</button>";
            }
            elseif($checkin==$date){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-warning btn-xs btn-sm font-weight-bold' disabled>Booking</button>";
            }
            elseif($date<date('Y-m-d')){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-secondary btn-xs btn-sm' disabled>N/A</button>";
            }
            elseif($date>$checkin){
                $calendar.="<td><h4>$currentDay</h4> <button onclick='checkout(event)' x='$date' class='btn btn-success btn-xs btn-sm'>CheckOut</button>";
            }
            elseif($date<$checkin){
                $calendar.="<td><h4>$currentDay</h4>  <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm invisible'>Book</a>";
            }
            else{
                // $date = date('Y-m-d', strtotime($date));
                $calendar.="<td><h4>$currentDay</h4> <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm ".$today."'>Book</a>";
            }
            
            $calendar .="</td>";
            // Increment counters
            $currentDay++;
            $dayOfWeek++;
        }
    
        // Complete the row of the last week in month, if necessary
        if ($dayOfWeek != 7) { 
    
            $remainingDays = 7 - $dayOfWeek;
            for($l=0;$l<$remainingDays;$l++){
                $calendar .= "<td class='empty'></td>"; 
            }
        }
    
        $calendar .= "</tr>";
        $calendar .= "</table>";
        $calendar .= "<input type='hidden' id='myin' value='$checkin'>";
        // echo $calendar;
        return $calendar;
    }

    public function checkoutChosen(Request $request) {
        $month = $request->month;
        $year = $request->year;
        $checkin = $request->checkin;
        $checkout = $request->checkout;
        $homestay_id = $request->homestay_id;

        $homestay = Homestay::find($homestay_id);
        $booked = [];
        foreach ($homestay->booking as $hs) {
            $bookedRange = self::createDateRangeArray($hs->checkInDate, $hs->checkOutDate);
            array_push($booked, ...$bookedRange);
        }

        $myrange = self::createDateRangeArray($checkin, $checkout);

        // Create array containing abbreviations of days of week.
        $daysOfWeek = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
    
        // What is the first day of the month in question?
        $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    
        // How many days does this month contain?
        $numberDays = date('t',$firstDayOfMonth);
    
        // Retrieve some information about the first day of the
        // month in question.
        $dateComponents = getdate($firstDayOfMonth);
    
        // What is the name of the month in question?
        $monthName = $dateComponents['month'];
    
        // What is the index value (0-6) of the first day of the
        // month in question.
        $dayOfWeek = $dateComponents['wday'];
    
        // Create the table tag opener and day headers
        $datetoday = date('Y-m-d');
    
        $calendar = "<table class='table table-bordered'>";
        $calendar .= "<center><h2>$monthName $year</h2>";
        
        $calendar.= "<button class='btn btn-xs btn-primary' id='myA' onclick='thescroll(".date('m', mktime(0, 0, 0, $month-1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month-1, 1, $year)).")'><</button> ";

        $calendar.= " <button class='btn btn-xs btn-primary' id='myB' onclick='thescroll(".date('m').",".date('Y').")'>Current Month</button> ";

        $calendar.= "<button class='btn btn-xs btn-primary' id='myC' onclick='thescroll(".date('m', mktime(0, 0, 0, $month+1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month+1, 1, $year)).")'>></button></center><br>";
        
        $calendar .= "<tr>";
    
        // Create the calendar headers
        foreach($daysOfWeek as $day) {
            $calendar .= "<th  class='header'>$day</th>";
        } 
    
        // Create the rest of the calendar
        // Initiate the day counter, starting with the 1st.
        $currentDay = 1;
    
        $calendar .= "</tr><tr>";
    
        // The variable $dayOfWeek is used to
        // ensure that the calendar
        // display consists of exactly 7 columns
        if ($dayOfWeek > 0) { 
            for($k=0;$k<$dayOfWeek;$k++){
                $calendar .= "<td  class='empty'></td>"; 
            }
        }
    
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    
        while ($currentDay <= $numberDays) {
    
            // Seventh column (Saturday) reached. Start a new row.
    
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }
            
            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            $dayname = strtolower(date('l', strtotime($date)));
            $eventNum = 0;
            $today = $date==date('Y-m-d')? "invisible" : "";

            if(in_array($date, $booked)){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm' disabled>Booked</button>";
            }
            elseif(in_array($date, $myrange)){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-warning btn-xs btn-sm' disabled>Booking</button>";
            }
            elseif($checkout==$date){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-warning btn-xs btn-sm font-weight-bold' disabled>Booking</button>";
            }
            elseif($date<date('Y-m-d')){
                $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-secondary btn-xs btn-sm' disabled>N/A</button>";
            }
            //3<10 && 20>13
            elseif($date<$checkin || $date>$checkout){
                $calendar.="<td><h4>$currentDay</h4> <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm invisible'>NO</a>";
            }
            else{
                $calendar.="<td><h4>$currentDay</h4> <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm ".$today."'>Book</a>";
            }
            
            $calendar .="</td>";
            // Increment counters
            $currentDay++;
            $dayOfWeek++;
        }
    
        // Complete the row of the last week in month, if necessary
        if ($dayOfWeek != 7) { 
    
            $remainingDays = 7 - $dayOfWeek;
            for($l=0;$l<$remainingDays;$l++){
                $calendar .= "<td class='empty'></td>"; 
            }
        }
    
        $calendar .= "</tr>";
        $calendar .= "</table>";
        $calendar .= "<input type='hidden' id='myin' value='$checkin'>";
        // echo $calendar;
        return $calendar;
    }

    public function scroll(Request $request) {
        $month = $request->month;
        $year = $request->year;
        $checkin = $request->checkin;
        $homestay_id = $request->homestay_id;

        $homestay = Homestay::find($homestay_id);
        $booked = [];
        foreach ($homestay->booking as $hs) {
            $bookedRange = self::createDateRangeArray($hs->checkInDate, $hs->checkOutDate);
            array_push($booked, ...$bookedRange);
        }

        // Create array containing abbreviations of days of week.
        $daysOfWeek = array('Sun','Mon','Tue','Wed','Thu','Fri','Sat');
    
        // What is the first day of the month in question?
        $firstDayOfMonth = mktime(0,0,0,$month,1,$year);
    
        // How many days does this month contain?
        $numberDays = date('t',$firstDayOfMonth);
    
        // Retrieve some information about the first day of the
        // month in question.
        $dateComponents = getdate($firstDayOfMonth);
    
        // What is the name of the month in question?
        $monthName = $dateComponents['month'];
    
        // What is the index value (0-6) of the first day of the
        // month in question.
        $dayOfWeek = $dateComponents['wday'];
    
        // Create the table tag opener and day headers
        $datetoday = date('Y-m-d');
    
        $calendar = "<table class='table table-bordered'>";
        $calendar .= "<center><h2>$monthName $year</h2>";

        $calendar.= "<button class='btn btn-xs btn-primary' onclick='thescroll(".date('m', mktime(0, 0, 0, $month-1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month-1, 1, $year)).")'><</button> ";

        $calendar.= " <button class='btn btn-xs btn-primary' onclick='thescroll(".date('m').",".date('Y').")'>Current Month</button> ";

        $calendar.= "<button class='btn btn-xs btn-primary' onclick='thescroll(".date('m', mktime(0, 0, 0, $month+1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month+1, 1, $year)).")'>></button></center><br>";
        
        $calendar .= "<tr>";
    
        // Create the calendar headers
        foreach($daysOfWeek as $day) {
            $calendar .= "<th  class='header'>$day</th>";
        } 
    
        // Create the rest of the calendar
        // Initiate the day counter, starting with the 1st.
        $currentDay = 1;
    
        $calendar .= "</tr><tr>";
    
        // The variable $dayOfWeek is used to
        // ensure that the calendar
        // display consists of exactly 7 columns
        if ($dayOfWeek > 0) { 
            for($k=0;$k<$dayOfWeek;$k++){
                $calendar .= "<td  class='empty'></td>"; 
            }
        }
    
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
    
        while ($currentDay <= $numberDays) {
    
            // Seventh column (Saturday) reached. Start a new row.
    
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= "</tr><tr>";
            }
            
            $currentDayRel = str_pad($currentDay, 2, "0", STR_PAD_LEFT);
            $date = "$year-$month-$currentDayRel";
            $dayname = strtolower(date('l', strtotime($date)));
            $eventNum = 0;
            $today = $date==date('Y-m-d')? "invisible" : "";

            if($checkin==null){
                if(in_array($date, $booked)){
                    $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm' disabled>Booked</button>";
                }elseif($date<date('Y-m-d')){
                    $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-secondary btn-xs btn-sm' disabled>N/A</button>";
                }
                else{
                    // $date = date('Y-m-d', strtotime($date));
                    $calendar.="<td><h4>$currentDay</h4> <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm ".$today."'>Choose</a>";
                }
            }
            else{
                if(in_array($date, $booked)){
                    $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm' disabled>Booked</button>";
                }
                elseif($date==date('Y-m-d')){
                    $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm invisible'>Booked</button>";
                }
                elseif($checkin==$date){
                    $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-warning btn-xs btn-sm font-weight-bold' disabled>Booking</button>";
                }
                elseif($date<date('Y-m-d')){
                    $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-secondary btn-xs btn-sm' disabled>N/A</button>";
                }
                elseif($date>$checkin){
                    $calendar.="<td><h4>$currentDay</h4> <button onclick='checkout(event)' x='$date' class='btn btn-success btn-xs btn-sm'>CheckOut</button>";
                }
                elseif($date<$checkin){
                    $calendar.="<td><h4>$currentDay</h4>  <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm invisible'>Book</a>";
                }
                else{
                    // $date = date('Y-m-d', strtotime($date));
                    $calendar.="<td><h4>$currentDay</h4> <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm ".$today."'>Book</a>";
                }
            }
            
            $calendar .="</td>";
            // Increment counters
            $currentDay++;
            $dayOfWeek++;
        }
    
        // Complete the row of the last week in month, if necessary
        if ($dayOfWeek != 7) { 
    
            $remainingDays = 7 - $dayOfWeek;
            for($l=0;$l<$remainingDays;$l++){
                $calendar .= "<td class='empty'></td>"; 
            }
        }
    
        $calendar .= "</tr>";
        $calendar .= "</table>";
        $calendar .= "<input type='hidden' id='myin' value='$checkin'>";
        // echo $calendar;
        return $calendar;
    }
}
