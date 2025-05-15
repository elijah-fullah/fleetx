@extends('home')

@section('content')
    
    <h2 class="main-title">OMCs for: {{ $dealer->first_name }} {{ $dealer->middle_name }} {{ $dealer->last_name }}</h2>

    <div class="back-button">
        <a href="{{ route('viewDealerProfile', $dealer->id) }}" 
           class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
           <i class="fa-solid fa-arrow-left"></i> Back to {{ $dealer->first_name }} {{ $dealer->middle_name }} {{ $dealer->last_name }} Profile
        </a>
    </div>

    <div class="row px-4">

        <div class="col-lg-12">
            
            <div class="detail-table w-full">

                <div class="filt">

                    <div>
                        <form method="GET" action="{{ route('dealerOMCOverview', $dealer->id) }}">
                            <select name="per_page" onchange="this.form.submit()" class="border-num px-2 py-1 rounded">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                                <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            </select>
                        </form>
                    </div>
                    
                    <div>
                        <form method="GET" action="{{ route('dealerOMCOverview', $dealer->id) }}">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="border px-2 py-1 rounded" />
                            <button type="submit" class="border px-2 py-1 rounded" hidden>Search</button>
                        </form>
                    </div>
                    
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>OMC Name</th>
                            <th>Phone No.</th>
                            <th>E-mail</th>
                            <th>License No.</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($omcs as $omc)
                            <tr>
                                <td>{{ $omc->omcName }}</td>
                                <td>{{ $omc->phone }}</td>
                                <td>{{ $omc->email }}</td>
                                <td>{{ $omc->licence_no }}</td>
                                <td>
                                    @php
                                        $statusClass = match($omc->status) {
                                            'active' => 'badge-active',
                                            'pending' => 'badge-pending',
                                            'suspended' => 'badge-trashed',
                                            'success' => 'badge-success',
                                            default => 'badge-pending',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ ucfirst($omc->status) }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="detail-footer">
                    <div class="pagination-info">
                        Showing {{ $omcs->firstItem() }} to {{ $omcs->lastItem() }} of {{ $omcs->total() }} results
                    </div>
                    {{ $omcs->links('pagination::bootstrap-4') }}
                </div>

            </div>
            
        </div>

    </div>

    <div class="row px-4">

        <div class="col-lg-9">

            <div class="chart">

                <canvas id="omcChart" aria-label="Site statistics" role="img"></canvas>

            </div>

        </div>

        <div class="col-lg-3">

            <article class="customers-wrapper">

                <canvas id="omcAverageChart" aria-label="Customers statistics" role="img"></canvas>

            </article>

        </div>

    </div>

    <script>

        var charts = {};
        var gridLine;
        var titleColor;

        var width, height, gradient;

        function getGradient(ctx, chartArea) {
            var chartWidth = chartArea.right - chartArea.left;
            var chartHeight = chartArea.bottom - chartArea.top;
  
            if (gradient === null || width !== chartWidth || height !== chartHeight) {
                width = chartWidth;
                height = chartHeight;
                gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                gradient.addColorStop(0, 'rgba(255, 255, 255, 0)');
                gradient.addColorStop(1, 'rgba(255, 255, 255, 0.4)');
            }
  
            return gradient;

        }
        
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('omcChart');
    
            if (ctx) {
                var myCanvas = ctx.getContext('2d');
        
                fetch("{{ route('getDealerOMCStats', $dealer->id) }}")
                .then(response => response.json())
                .then(data => {
                    var omcChart = new Chart(myCanvas, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'This Week',
                                data: data.currentWeek,
                                cubicInterpolationMode: 'monotone',
                                tension: 0.4,
                                backgroundColor: 'rgba(95, 46, 234, 1)',
                                borderColor: 'rgba(95, 46, 234, 1)',
                                borderWidth: 2
                            }, {
                                label: 'Last Week',
                                data: data.previousWeek,
                                cubicInterpolationMode: 'monotone',
                                tension: 0.4,
                                backgroundColor: 'rgba(75, 222, 151, 1)',
                                borderColor: 'rgba(75, 222, 151, 1)',
                                borderWidth: 2
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    min: 0,
                                    ticks: {
                                        stepSize: 1
                                    },
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    grid: {
                                        color: gridLine
                                    }
                                }
                            },
                            elements: {
                                point: {
                                    radius: 3
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                    labels: {
                                        boxWidth: 8,
                                        boxHeight: 8,
                                        usePointStyle: true,
                                        font: {
                                            size: 12,
                                            weight: '500'
                                        }
                                    }
                                },
                                title: {
                                    display: true,
                                    text: ['OMC Registrations', 'This Week vs Last Week'],
                                    align: 'start',
                                    font: {
                                        size: 16,
                                        family: 'Inter',
                                        weight: '600',
                                        lineHeight: 1.4
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching station data:', error));
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            var omcAverageChart = document.getElementById('omcAverageChart');
            var omc_id = {{ $dealer->omc_id }};
    
            if (omcAverageChart) {
                var customersChartCanvas1 = omcAverageChart.getContext('2d');
        
                fetch("{{ route('getDealerOMCAverage', $dealer->id) }}")
                .then(response => response.json())
                .then(data => {
                    var myOMCAverageChart = new Chart(customersChartCanvas1, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'This Week',
                                data: data.currentWeek,
                                tension: 0.4,
                                backgroundColor: function (context) {
                                    var chart = context.chart;
                                    var ctx = chart.ctx,
                                    chartArea = chart.chartArea;
                                    if (!chartArea) {
                                        return null;
                                    }
                                    return getGradient(ctx, chartArea);
                                },
                                borderColor: ['#fff'],
                                borderWidth: 2,
                                fill: true
                            }, {
                                label: 'Last Week',
                                data: data.previousWeek,
                                tension: 0.4,
                                backgroundColor: 'rgba(75, 222, 151, 0.2)',
                                borderColor: 'rgba(75, 222, 151, 1)',
                                borderWidth: 2,
                                fill: true
                            }]
                        },
                        options: {
                            scales: {
                                y: {
                                    display: false
                                },
                                x: {
                                    display: false
                                }
                            },
                            elements: {
                                point: {
                                    radius: 1
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                    labels: {
                                        color: '#fff',
                                        size: 18,
                                        fontStyle: 800,
                                        boxWidth: 0
                                    }
                                },
                                title: {
                                    display: true,
                                    text: [
                                        'OMC Statistics',
                                        `Avg: ${data.currentWeekAverage.toFixed(2)} (This Week) | ${data.previousWeekAverage.toFixed(2)} (Last Week)`
                                    ],
                                    align: 'start',
                                    color: '#fff',
                                    font: {
                                        size: 14,
                                        family: 'Inter',
                                        weight: '600',
                                        lineHeight: 1.4
                                    },
                                    padding: {
                                        top: 20
                                    }
                                }
                            },
                            maintainAspectRatio: false
                        }
                    });
                    omcAverageChart.dealers = myOMCAverageChart;
                })
                .catch(error => console.error('Error fetching station data:', error));
            }
        });

        function addData() {
            var darkMode = localStorage.getItem('darkMode');

            if (darkMode === 'enabled') {
                gridLine = '#37374F';
                titleColor = '#EFF0F6';
            } else {
                gridLine = '#EEEEEE';
                titleColor = '#171717';
            }

        }

        addData();

    </script>

@endsection

