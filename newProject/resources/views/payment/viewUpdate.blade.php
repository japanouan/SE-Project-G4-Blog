@extends('layouts.main')

@section('content')
    <div class="container mx-auto px-6 py-12 max-w-4xl">
        @if (session('success'))
            <div class="flex items-center bg-green-100 border border-green-300 text-green-800 px-6 py-4 rounded-xl mb-8 shadow-lg text-xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mr-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <h2 class="text-4xl font-bold text-gray-900 mb-8">💸 รายการชำระเงินสำหรับ Booking #{{ $booking->booking_id }}</h2>

        @forelse($payments as $payment)
            <div class="bg-white/70 backdrop-blur-lg border border-gray-200 rounded-3xl shadow-2xl p-8 mb-8 transition-all hover:shadow-3xl">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <div>
                        <p class="text-2xl text-gray-700 mb-1">ยอดที่ต้องชำระ</p>
                        <p class="text-4xl font-bold text-green-600">{{ number_format($payment->total, 2) }} บาท</p>
                    </div>
                    <div class="mt-4 sm:mt-0">
                        <span class="text-lg bg-gray-100 text-gray-700 px-5 py-2 rounded-full shadow">
                            🌀 รอบที่ {{ $payment->booking_cycle }}
                        </span>
                    </div>
                </div>

                <form action="{{ route('payment.updateMethod', $payment->payment_id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="payment_method" class="block text-2xl font-medium text-gray-800 mb-3">
                            เลือกวิธีชำระเงิน
                        </label>
                        <select name="payment_method" id="payment_method"
                            class="w-full text-lg border-gray-300 rounded-xl shadow-md px-4 py-3 focus:ring-2 focus:ring-green-300 focus:border-green-500 bg-white"
                            required>
                            <option value="">-- กรุณาเลือกวิธี --</option>
                            <option value="credit_card">💳 บัตรเครดิต</option>
                            <option value="paypal">🅿️ PayPal</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-600 text-white text-xl font-semibold py-3 px-7 rounded-full shadow-lg transition-all duration-200">
                            ✅ ยืนยันการชำระเงิน
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div class="bg-yellow-100 border border-yellow-300 text-yellow-800 px-6 py-6 rounded-xl text-xl shadow-md">
                <p>🔍 ไม่พบรายการที่ต้องชำระ</p>
            </div>
        @endforelse
    </div>
@endsection
