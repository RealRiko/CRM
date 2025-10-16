@extends('layouts.app')

@section('content')

{{-- === Custom Amber Color === --}}
<style>
    /* Amber / Sienna Accent */
    .text-amber-sienna { color: #CA8A04; }
    .border-amber-sienna { border-color: #CA8A04; }
    .bg-amber-sienna { background-color: #CA8A04; }
</style>

{{-- === Main Dashboard Container === --}}
<div class="p-6 md:p-10 max-w-7xl mx-auto pb-8">
    
    {{-- === Page Title === --}}
    <h1 class="text-3xl font-extrabold mb-8 text-gray-900 dark:text-white border-b pb-3 border-gray-200 dark:border-gray-700">
        Executive Overview
    </h1>

    {{-- === Stat Cards === --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border-l-4 border-amber-sienna hover:shadow-xl transition duration-300">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Products</p>
            <p class="text-4xl font-bold text-amber-sienna mt-1">{{ $productCount }}</p>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border-l-4 border-amber-sienna hover:shadow-xl transition duration-300">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Clients</p>
            <p class="text-4xl font-bold text-amber-sienna mt-1">{{ $clientCount }}</p>
        </div>

        <div class="p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border-l-4 border-amber-sienna hover:shadow-xl transition duration-300">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Documents</p>
            <p class="text-4xl font-bold text-amber-sienna mt-1">{{ $documentCount }}</p>
        </div>
    </div>

    {{-- === Goal Progress & Chart === --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- Monthly Revenue Goal --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg flex flex-col h-full min-h-[360px]">
            <h2 class="font-bold text-xl mb-4 text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3">
                Monthly Revenue Target
            </h2>

            <div class="text-sm text-gray-600 dark:text-gray-300 flex justify-between items-center mb-6 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg border-l-4 border-amber-sienna">
                <span>This Month's Revenue:</span>
                <span class="font-bold text-xl text-amber-sienna">€{{ number_format($thisMonthRevenue, 2) }}</span>
            </div>

            {{-- Animated Amber Progress Bar --}}
            <div class="my-auto text-center flex-grow flex flex-col justify-center items-center">
                <p class="text-6xl font-extrabold text-amber-sienna mb-4">{{ $goalProgress }}%</p>

                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 mb-4 overflow-hidden">
                    <div 
                        id="progress-bar"
                        class="h-3 rounded-full transition-all duration-1000 ease-out"
                        style="
                            width: 0%;
                            background: linear-gradient(90deg, #CA8A04 0%, #EAB308 100%);
                            box-shadow: 0 0 10px rgba(202,138,4,0.5);
                        ">
                    </div>
                </div>
            </div>

            <div class="pt-3 border-t border-gray-100 dark:border-gray-700">
                <p class="text-lg text-gray-600 dark:text-gray-300 flex justify-between font-semibold">
                    <span>Target Goal:</span>
                    <span class="text-gray-800 dark:text-white">€{{ number_format($goal, 2) }}</span>
                </p>
            </div>
        </div>

        {{-- Revenue Line Chart --}}
        <div class="lg:col-span-3 bg-white dark:bg-gray-800 p-8 rounded-xl shadow-lg h-full min-h-[360px]">
            <h2 class="font-bold text-xl mb-4 text-gray-800 dark:text-white">Revenue Trend (Last 6 Months)</h2>
            <div class="h-[calc(100%-48px)]"> 
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- === Scripts === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Smooth progress animation
    const progress = {{ $goalProgress }};
    const bar = document.getElementById('progress-bar');
    setTimeout(() => {
        bar.style.width = progress + '%';
    }, 300);

    // Chart.js configuration
    const ctx = document.getElementById('revenueChart').getContext('2d');
    const primaryColor = '#CA8A04';
    const secondaryColor = 'rgba(202, 138, 4, 0.15)';
    const gridColor = 'rgba(107, 114, 128, 0.3)';

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($months),
            datasets: [{
                label: 'Revenue (€)',
                data: @json($revenues),
                fill: true,
                borderColor: primaryColor,
                backgroundColor: secondaryColor,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: primaryColor,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => `€${ctx.formattedValue}`
                    },
                    backgroundColor: 'rgba(31, 41, 55, 0.9)',
                    titleColor: '#fff',
                    bodyColor: primaryColor,
                    bodyFont: { weight: 'bold', size: 14 },
                }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: gridColor, drawBorder: false },
                    ticks: {
                        color: '#9CA3AF',
                        callback: function(value) {
                            return '€' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#9CA3AF' }
                }
            }
        }
    });
});
</script>
@endsection
