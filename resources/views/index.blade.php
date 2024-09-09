@extends('layouts.layouts')

@section('title', 'Başlıkkksss')

@section('content')
    bu bölüm site içeriği
    <p>Hello! {{ $user->username }}</p>
    <a href="{{ route('about') }}">tıkla</a>
@endsection