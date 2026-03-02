@extends('layouts.frontend')

@section('title', 'Forgot Password')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="text-center text-3xl font-bold text-gray-900">Forgot Your Password?</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('customer.password.email') }}" method="POST">
            @csrf

            @if(session('status'))
                <div class="bg-green-50 text-green-600 p-4 rounded-lg text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
            </div>

            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Send Reset Link
            </button>

            <p class="text-center text-sm text-gray-600">
                Remember your password? <a href="{{ route('customer.login') }}" class="text-primary-600 hover:underline">Sign in</a>
            </p>
        </form>
    </div>
</div>
@endsection
