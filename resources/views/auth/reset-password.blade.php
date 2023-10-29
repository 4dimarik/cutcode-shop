@extends('layouts.auth')
@section('title', __('Сбросить пароль'))

@section('content')
    <x-forms.auth-forms title="{{__('Сбросить пароль')}}" action="{{route('password.update')}}" method="POST">
        <input type="hidden" name="token" value="{{$token}}"/>
        <x-forms.text-input
            name="email"
            type="email"
            placeholder="E-mail"
            value="{{request('email')}}"
            required="true"
        />
        <x-forms.text-input
            name="password"
            type="password"
            placeholder="Пароль"
            required="true"
        />
        <x-forms.text-input
            name="password_confirmation"
            type="password"
            placeholder="Повторно пароль"
            required="true"
        />
        <x-forms.primary-button>{{__('Отправить')}}</x-forms.primary-button>
        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons></x-slot:buttons>
    </x-forms.auth-forms>

@endsection
