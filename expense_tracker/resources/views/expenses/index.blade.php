
@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista e Shpenzimeve</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('expenses.create') }}" class="btn btn-primary mb-3">Shto Shpenzim</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Shuma</th>
                <th>Kategoria</th>
                <th>Data</th>
                <th>Veprime</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expenses as $expense)
                <tr>
                    <td>{{ $expense->amount }} €</td>
                    <td>{{ $expense->category->name ?? 'Other' }}</td>
                    <td>{{ $expense->date }}</td>
                    <td>
                        <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning btn-sm">Edito</a>
                        <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Fshij</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Charts Section -->
    <div class="row mt-5">
        <div class="col-md-8">
            <canvas id="dailyChart"></canvas>
        </div>
        <div class="col-md-4">
            <canvas id="categoryChart" height="200"></canvas>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <canvas id="monthlyChart"></canvas>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const categoryCtx = document.getElementById('categoryChart');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: @json($categoryNames),
            datasets: [{
                label: 'Shpenzime sipas kategorive',
                data: @json($categorySums),
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#A6D785', '#D39EFF'],
            }]
        }
    });

    const dailyCtx = document.getElementById('dailyChart');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: @json($expenses->pluck('date')->unique()->values()),
            datasets: [{
                label: 'Shpenzime ditore',
                data: @json($expenses->groupBy('date')->map->sum('amount')->values()),
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }]
        }
    });

    const monthlyCtx = document.getElementById('monthlyChart');
    new Chart(monthlyCtx, {
        type: 'bar',
        data: {
            labels: @json($expenses->groupBy(function($item) {
                return \Carbon\Carbon::parse($item->date)->format('F');
            })->keys()),
            datasets: [{
                label: 'Shpenzime mujore',
                data: @json($expenses->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->date)->format('F');
                })->map->sum('amount')->values()),
                backgroundColor: '#4e73df',
            }]
        }
    });
</script>
@endsection
