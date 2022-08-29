@extends('template') 

@section('content')
    @if ($parentview=='ticket' && $subview=='')
        @if( Session::get('level_user')==1 )
            @include('ticket.user.index')
        @elseif( Session::get('level_user')==2 )
            @include('ticket.operator.index')
        @else
            @include('ticket.admin.index')
        @endif
    @elseif ($parentview=='ticket' && $subview=='detail')
        @if( Session::get('level_user')==1 )
            @include('ticket.user.detail')
        @elseif( Session::get('level_user')==2 )
            @include('ticket.operator.detail')
        @else
            @include('ticket.admin.detail')
        @endif    
    @elseif ($parentview=='ticket' && $subview=='create')
        @include('ticket.create')
    @endif
@endsection