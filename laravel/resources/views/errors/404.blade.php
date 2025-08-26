@extends('layouts.error-page')

@section('title', '404 - Not Found')
@section('body_id', 'error-404')

@section('content')

<div class="text">
    <p>404</p>
</div>

<div class="sky">
    <div class="sun" aria-hidden="true"></div>

    <!-- hanging lamp -->
    <div class="lamp" aria-hidden="true">
        <div class="cord"></div>
        <div class="bulb"></div>
    </div>
</div>

<div class="container">
    <!-- caveman left -->
    <div class="caveman">
        <div class="leg">
            <div class="foot">
                <div class="fingers"></div>
            </div>
        </div>
        <div class="leg">
            <div class="foot">
                <div class="fingers"></div>
            </div>
        </div>
        <div class="shape">
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
        <div class="head">
            <div class="eye">
                <div class="nose"></div>
            </div>
            <div class="mouth"></div>
        </div>
        <div class="arm-right">
            <div class="club"></div>
        </div>
    </div>
    
    <!-- caveman right -->
    <div class="caveman">
        <div class="leg">
            <div class="foot">
                <div class="fingers"></div>
            </div>
        </div>
        <div class="leg">
            <div class="foot">
                <div class="fingers"></div>
            </div>
        </div>
        <div class="shape">
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
        <div class="head">
            <div class="eye">
                <div class="nose"></div>
            </div>
            <div class="mouth"></div>
        </div>
        <div class="arm-right">
            <div class="club"></div>
        </div>
    </div>
</div>
@endsection