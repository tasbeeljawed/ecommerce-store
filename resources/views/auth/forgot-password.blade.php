@extends('layouts.app')

@section('title', 'Forgot Password - E-Commerce Store')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Reset Password</h2>

        <p class="text-gray-600 mb-6 text-center">
            Enter your email address and we'll send you a link to reset your password.
        </p>

        @if (session('status'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required 
                       autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="mb-6">
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                    Send Reset Link
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800">
                    Back to Login
                </a>
            </div>
        </form>
    </div>

</div>
@endsection