@extends('layouts.auth')
@section('title', __('Восстановить пароль'))

@section('content')
    <x-forms.auth-forms
        title="{{__('Восстановить пароль')}}"
        action="{{route('password.email')}}"
        method="POST"
    >
        <x-forms.text-input
            name="email"
            type="email"
            placeholder="E-mail"
            required="true"
        />
        <x-forms.primary-button>{{__('Отправить')}}</x-forms.primary-button>
        <x-slot:socialAuth></x-slot:socialAuth>
        <x-slot:buttons>
            <div class="space-y-3 mt-5">
                <div class="text-xxs md:text-xs">
                    <a href="{{route('login')}}"
                       class="text-white hover:text-white/70 font-bold">{{__('Вспомнил пароль')}}</a>
                </div>
            </div>
        </x-slot:buttons>
    </x-forms.auth-forms>

@endsection
