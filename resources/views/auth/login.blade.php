@extends('layouts.app')

@section('title', 'Login - E-Commerce Store')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">Login</h2>

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email Address</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       required 
                       autofocus
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-semibold mb-2">Password</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>

            <div class="mb-4 flex items-center">
                <input type="checkbox" 
                       id="remember" 
                       name="remember" 
                       class="mr-2">
                <label for="remember" class="text-gray-700">Remember me</label>
            </div>

            <div class="mb-6">
                <button type="submit" class="w-full bg-indigo-600 text-white py-3 rounded-lg hover:bg-indigo-700 font-semibold">
                    Login
                </button>
            </div>

            <div class="text-center">
                <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-800">
                    Forgot your password?
                </a>
            </div>

            <div class="text-center mt-4">
                <span class="text-gray-600">Don't have an account?</span>
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 font-semibold ml-1">
                    Register
                </a>
            </div>
        </form>
    </div>

    <div class="mt-6 p-4 bg-blue-50 rounded-lg">
        <p class="text-sm text-gray-600 mb-2"><strong>Demo Accounts:</strong></p>
        <p class="text-sm text-gray-600">Admin: admin@ecommerce.com / password</p>
        <p class="text-sm text-gray-600">Customer: customer@example.com / password</p>
    </div>

</div>
@endsection