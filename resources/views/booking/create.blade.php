@extends('layouts.app')
@section('content')

<?php
function build_calendar($month, $year, $booked) {
    
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
    // $calendar.= "<a class='btn btn-xs btn-primary' onclick='scroll(".date('m', mktime(0, 0, 0, $month-1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month-1, 1, $year)).")'' href='?month=".date('m', mktime(0, 0, 0, $month-1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month-1, 1, $year))."'><</a> ";

    // $calendar.= " <a class='btn btn-xs btn-primary' onclick='scroll(".date('m').",".date('Y').")'' href='?month=".date('m')."&year=".date('Y')."'>Current Month</a> ";

    // $calendar.= "<a class='btn btn-xs btn-primary' onclick='scroll(".date('m', mktime(0, 0, 0, $month+1, 1, $year)).",".date('Y', mktime(0, 0, 0, $month+1, 1, $year)).")'' href='?month=".date('m', mktime(0, 0, 0, $month+1, 1, $year))."&year=".date('Y', mktime(0, 0, 0, $month+1, 1, $year))."'>></a></center><br>";

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
        $today = $date==date('Y-m-d')? "d-none" : "";

        if(in_array($date, $booked)){
            $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-danger btn-xs btn-sm' disabled>Booked</button>";
        }elseif($date<date('Y-m-d')){
            $calendar.="<td><h4>$currentDay</h4> <button class='btn btn-secondary btn-xs btn-sm' disabled>N/A</button>";
        }
        else{
            // $date = date('Y-m-d', strtotime($date));
            $calendar.="<td><h4>$currentDay</h4> <a onclick='checkin(event)' x='$date' class='btn btn-success btn-xs btn-sm ".$today."'>Choose</a>";
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
    echo $calendar;
}
?>
    <div class="card">
        <div class="card-header">Create Booking</div>
        <div class="card-body">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-4 col-sm-4">
                            <img class="shadow-sm" style="width: 100%" src="/storage/myimage/{{$hs->image}}" alt="homestay image">
                        </div>
                        <div class="col-md-8 col-sm-8">
                            <h3><a href="/homestay/{{$hs->id}}">{{$hs->name}}</a></h3>
                            <ul>
                                <li>{{$hs->address}}</li>
                                <li>{{$hs->type}}</li>
                                <li>{{$hs->numRoom}} Rooms</li>
                                <li>RM{{$hs->price}} / Night</li>
                            </ul>
                            <div>
                                <hr>
                                <div>
                                    <p>Landlord : {{$hs->landlord->name}} ({{$hs->landlord->phone}})</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row d-flex">
                <div class="col-md-8">
                    <h4 class="text-center" id="mytext">Choose Your Check-In Date :</h4>
                    <div id="calendar">
                    <?php
                        $dateComponents = getdate();
                        if(isset($_GET['month']) && isset($_GET['year'])){
                            $month = $_GET['month']; 			     
                            $year = $_GET['year'];
                        }else{
                            $month = $dateComponents['mon']; 			     
                            $year = $dateComponents['year'];
                        }
                        echo build_calendar($month,$year, $booked);
                    ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                <h5 class="mt-2">Booking Details</h5>
                                <button onClick="window.location.reload();" class="btn btn-danger">Reset</button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="/booking" method="post">
                                @csrf
                                <input type="hidden" id="hs_id" name="homestay_id" value="{{$hs->id}}">
                                <div class="row">
                                    <label for="checkInDate" class="col-md-5">Check In :</label>
                
                                    <div class="col-md-7">
                                        <h4 id="inputIn" class="text-success font-weight-bold">_____________</h4>
                                        <input type="hidden" id="hiddenIn" name="checkInDate" value="">
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="checkInDate" class="col-md-5">Check Out :</label>
                
                                    <div class="col-md-7">
                                        <h4 id="inputOut" class="text-success font-weight-bold">_____________</h4>
                                        <input type="hidden" id="hiddenOut" name="checkOutDate" value="">
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-secondary btn-block" id="submitBtn" disabled>Book Now</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
function checkin(event) {
    var hs_id = document.getElementById('hs_id').value;
    var date = event.target.getAttribute('x');
    var date2 = date; // pass the same date to date2 to be sent to controller
    var newdate = moment(date).format("DD MMM YYYY");
    document.getElementById('inputIn').innerHTML = newdate;
    document.getElementById('hiddenIn').value = date2;

    var mymoment = moment(date2, "YYYY-M-D");
    var month = mymoment.month()+1;
    var year = mymoment.year();
    console.log(month);    

    $.ajax({
        type:'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:'{{ route('mycheckout') }}',
        dataType:'html',
        data: {
            month: month,
            year: year,
            checkin: date2,
            homestay_id: hs_id
        },
        success: function(response) {
            document.getElementById('calendar').innerHTML = response;
        },
        error: function(e) {
            console.log("Have error : "+e.responseText);
        }
    });

    document.getElementById('mytext').innerHTML = "Choose Your Check-Out Date";
}

function checkout(event) {
    var hs_id = document.getElementById('hs_id').value;
    var checkin = document.getElementById('myin').value;
    var date = event.target.getAttribute('x');
    var date2 = date; // pass the same date to date2 to be sent to controller
    var newdate = moment(date).format("DD MMM YYYY");
    document.getElementById('inputOut').innerHTML = newdate;
    document.getElementById('hiddenOut').value = date2;
    var btn = document.getElementById('submitBtn');
    btn.disabled = false;
    btn.className = "btn btn-success font-weight-bold btn-block";

    var mymoment = moment(date2, "YYYY-M-D");
    var month = mymoment.month()+1;
    var year = mymoment.year();
    console.log("The"+month);
    $.ajax({
        type:'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:'{{ route('mycheckoutChosen') }}',
        dataType:'html',
        async: false,
        data: {
            month: month,
            year: year,
            checkin: checkin,
            checkout: date2,
            homestay_id: hs_id
        },
        success: function(response) {
            document.getElementById('calendar').innerHTML = response;
        },
        error: function(e) {
            console.log("Have error : "+e.responseText);
        }
    });
    var myclass = "btn btn-xs btn-secondary"
    
    var btnA = document.getElementById('myA');
    btnA.disabled = true;
    btnA.className = myclass;

    var btnB = document.getElementById('myB');
    btnB.disabled = true;
    btnB.className = myclass;

    var btnC = document.getElementById('myC');
    btnC.disabled = true;
    btnC.className = myclass;
}

function thescroll(month, year){
    var hs_id = document.getElementById('hs_id').value;
    var checkin = document.getElementById('hiddenIn').value;
    $.ajax({
        type:'post',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url:'{{ route('myscroll') }}',
        dataType:'html',
        data: {
            month: month,
            year: year,
            checkin: checkin,
            homestay_id: hs_id
        },
        success: function(response) {
            document.getElementById('calendar').innerHTML = response;
        },
        error: function(e) {
            console.log("Have error : "+e.responseText);
        }
    });
}
</script>
@endsection