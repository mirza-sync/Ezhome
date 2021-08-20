<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="{{asset('css/app.css')}}" rel="stylesheet">
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment.min.js"></script>
        <title>{{config('app.name', 'EZHOME')}}</title>

        <style>
            @media only screen and (max-width: 760px),
             (min-device-width: 802px) and (max-device-width: 1020px) {
     
                 /* Force table to not be like tables anymore */
                 table, thead, tbody, th, td, tr {
                     display: block;
     
                 }
                 
                 
     
                 .empty {
                     display: none;
                 }
     
                 /* Hide table headers (but not display: none;, for accessibility) */
                 th {
                     position: absolute;
                     top: -9999px;
                     left: -9999px;
                 }
     
                 tr {
                     border: 1px solid #ccc;
                 }
     
                 td {
                     /* Behave  like a "row" */
                     border: none;
                     border-bottom: 1px solid #eee;
                     position: relative;
                     padding-left: 50%;
                 }
     
     
     
                 /*
             Label the data
             */
                 td:nth-of-type(1):before {
                     content: "Sunday";
                 }
                 td:nth-of-type(2):before {
                     content: "Monday";
                 }
                 td:nth-of-type(3):before {
                     content: "Tuesday";
                 }
                 td:nth-of-type(4):before {
                     content: "Wednesday";
                 }
                 td:nth-of-type(5):before {
                     content: "Thursday";
                 }
                 td:nth-of-type(6):before {
                     content: "Friday";
                 }
                 td:nth-of-type(7):before {
                     content: "Saturday";
                 }
     
     
             }
     
             /* Smartphones (portrait and landscape) ----------- */
     
             @media only screen and (min-device-width: 320px) and (max-device-width: 480px) {
                 body {
                     padding: 0;
                     margin: 0;
                 }
             }
     
             /* iPads (portrait and landscape) ----------- */
     
             @media only screen and (min-device-width: 802px) and (max-device-width: 1020px) {
                 body {
                     width: 495px;
                 }
             }
     
             @media (min-width:641px) {
                 table {
                     table-layout: fixed;
                 }
                 td {
                     width: 33%;
                 }
             }
             
             .row{
                 margin-top: 20px;
             }
             
             .today{
                 background:yellow;
             }
             
             
             
         </style>
    </head>
    <body>
        @include('inc.navbar')
        <div class="container mt-4">
            @include('inc.messages')
            @yield('content')
        </div>
    </body>
</html>
