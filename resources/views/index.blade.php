@extends('layouts.layouts')

@section('title', 'Başlıkkksss')

@section('content')
    bu bölüm index içeriği

    <a href="{{ route('about') }}">deneme</a>

    <form action="{{ route('post') }}" method="POST">
        @csrf
        <input type="text" name="name">
        <button type="submit">Gönder</button>
    </form>
@endsection