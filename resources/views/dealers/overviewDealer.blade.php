@extends('home')

@section('content')
    
    <h2 class="main-title">Dealer's Overview</h2>

    <div class="row stat-cards">

        <div class="col-md-6 col-xl-3">

            <article class="stat-cards-item">

                <div class="stat-cards-icon primary">
                    <i data-feather="bar-chart-2" aria-hidden="true"></i>
                </div>

                <div class="stat-cards-info">

                    <p class="stat-cards-info__num">1478 286</p>
                    <p class="stat-cards-info__title">Total visits</p>
                    <p class="stat-cards-info__progress">
                        <span class="stat-cards-info__profit success">
                            <i data-feather="trending-up" aria-hidden="true"></i>4.07%
                        </span>Last month
                    </p>
                
                </div>
    
            </article>

        </div>
  
        <div class="col-md-6 col-xl-3">

            <article class="stat-cards-item">

                <div class="stat-cards-icon warning">
                    <i data-feather="file" aria-hidden="true"></i>
                </div>
      
                <div class="stat-cards-info">

                    <p class="stat-cards-info__num">1478 286</p>
                    <p class="stat-cards-info__title">Total visits</p>
                    <p class="stat-cards-info__progress">
                        <span class="stat-cards-info__profit success">
                            <i data-feather="trending-up" aria-hidden="true"></i>0.24%
                        </span>
                        Last month
                    </p>

                </div>
    
            </article>
  
        </div>
  
        <div class="col-md-6 col-xl-3">

            <article class="stat-cards-item">

                <div class="stat-cards-icon purple">

                    <i data-feather="file" aria-hidden="true"></i>
                </div>

                <div class="stat-cards-info">

                    <p class="stat-cards-info__num">1478 286</p>
                    <p class="stat-cards-info__title">Total visits</p>
                    <p class="stat-cards-info__progress">
                        <span class="stat-cards-info__profit danger">
                            <i data-feather="trending-down" aria-hidden="true"></i>1.64%
                        </span>
                        Last month
                    </p>
      
                </div>

            </article>

        </div>

        <div class="col-md-6 col-xl-3">

            <article class="stat-cards-item">

                <div class="stat-cards-icon success">

                    <i data-feather="feather" aria-hidden="true"></i>

                </div>

                <div class="stat-cards-info">
                     <p class="stat-cards-info__num">1478 286</p>
                     <p class="stat-cards-info__title">Total visits</p>
                     <p class="stat-cards-info__progress">
                        <span class="stat-cards-info__profit warning">
                            <i data-feather="trending-up" aria-hidden="true"></i>0.00%
                        </span>
                        Last month
                    </p>

                </div>

            </article>

        </div>

    </div>

    <div class="row px-4">

        <div class="col-lg-12">

            <div class="detail-table w-full">

                <div class="filt">

                    <div>
                        <form method="GET" action="{{ route('overviewDealer') }}">
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
                        <form method="GET" action="{{ route('overviewDealer') }}">
                            <input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="border px-2 py-1 rounded" />
                            <button type="submit" class="border px-2 py-1 rounded" hidden>Search</button>
                        </form>
                    </div>
                    
                </div>

                <table>
                    <thead>
                        <tr>
                            <th>Full Name</th>
                            <th>License No</th>
                            <th>License Exp Date</th>
                            <th>Phone No.</th>
                            <th>E-mail</th>
                            <th>No. of OMCs</th>
                            <th>No. of Stations</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allDealer as $dealer)
                            <tr>
                                <td>{{ $dealer->first_name }} {{ $dealer->middle_name }} {{ $dealer->last_name }}</td>
                                <td>{{ $dealer->licence_no }}</td>
                                <td>{{ $dealer->licence_exp }}</td>
                                <td>{{ $dealer->phone }}</td>
                                <td>{{ $dealer->email }}</td>
                                <td>{{ $dealer->omc_count }}</td>
                                <td>{{ $dealer->station_count }}</td>
                                <td>
                                    @php
                                        $statusClass = match($dealer->status) {
                                            'active' => 'badge-active',
                                            'pending' => 'badge-pending',
                                            'suspended' => 'badge-trashed',
                                            'success' => 'badge-success',
                                            default => 'badge-pending',
                                        };
                                    @endphp
                                    <span class="{{ $statusClass }}">{{ ucfirst($dealer->status) }}</span>
                                </td>
                                <td>
                                    <button class="dropdown-btn transparent-btn" type="button" title="More info">
                                        <div class="sr-only">More info</div>
                                        <i data-feather="more-horizontal" aria-hidden="true"></i>
                                    </button>
                                    <ul class="users-item-dropdown dropdown">
                                        <li><a href="{{ route('editDealer', $dealer->id ?? 0) }}">Edit</a></li>
                                        <li><a href="{{ route('viewDealerProfile', $dealer->id ?? 0) }}">View Profile</a></li>
                                        <li><a href="{{ route('dealerStationOverview', $dealer->id) }}">View Stations</a></li>
                                        <li><a href="{{ route('dealerOMCOverview', $dealer->id) }}">View OMCs</a></li>
                                        <li>
                                            <a href="#" onclick="openSuspendModal('{{ route('suspendDealer', $dealer->id) }}','{{ $dealer->status }}','{{ $dealer->first_name }}','{{ $dealer->middle_name ?? '' }}','{{ $dealer->last_name }}')" class="text-red-500">
                                                @if($dealer->status === 'pending')
                                                    Approve
                                                @elseif($dealer->status === 'active')
                                                    Suspend
                                                @elseif($dealer->status === 'suspended')
                                                    Activate
                                                @endif
                                            </a>
                                        </li>                                                                                     
                                        <li>
                                            <a href="#" onclick="openModal('{{ route('deleteDealer', $dealer->id) }}', '{{ $dealer->first_name }} {{ $dealer->middle_name }} {{ $dealer->last_name }}')" class="text-red-500">
                                                Trash
                                            </a>
                                        </li>
                                        
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            
                <div class="detail-footer">
                    <div class="pagination-info">
                        Showing {{ $allDealer->firstItem() }} to {{ $allDealer->lastItem() }} of {{ $allDealer->total() }} results
                    </div>
                    {{ $allDealer->links('pagination::bootstrap-4') }}
                </div>

                <div id="deleteModal" class="delete-modal fixed inset-0 hidden">
                    <div class="delete-mod rounded-md">
                        <h2 class="font-semibold mb-4">Confirm Deletion</h2>
                        <p>Are you sure you want to delete <span id="dealerNamePlaceholder"></span>?</p>
                        <form id="deleteForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="delete-modal__button">
                                <button type="submit" class="confirm-button">Delete</button>
                                <button type="button" onclick="closeModal()" class="cancel-button">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="suspendModal" class="delete-modal fixed inset-0 hidden">
                    <div class="delete-mod rounded-md">
                        <h2 class="font-semibold mb-4">Confirm Suspension</h2>
                        <p>Are you sure you want to <span id="suspendActionText">suspend</span> <span id="suspendDealerName"></span>?</p>
                        <form id="suspendForm" method="POST">
                            @csrf
                            <input type="hidden" id="newStatus" name="status" value="">
                            <div class="delete-modal__button">
                                <button type="submit" class="confirm-button" id="suspendButtonText">Suspend</button>
                                <button type="button" onclick="closeSuspendModal()" class="cancel-button">Cancel</button>
                            </div>
                        </form>                        
                    </div>
                </div>

            </div>

        </div>

    </div>

    <div class="row px-4">

        <div class="col-lg-9">

            <div class="chart">

                <canvas id="dealerChart" aria-label="Site statistics" role="img"></canvas>

            </div>

        </div>

        <div class="col-lg-3">

            <article class="white-block">

                <div class="top-cat-title">
                    <h3>Top Dealers</h3>
                    <p>Dealers with the most stations</p>
                </div>
            
                <ul class="top-cat-list" id="topDealerList">
                    
                </ul>

            </article>

            <article class="customers-wrapper">

                <canvas id="dealerAverageChart" aria-label="Customers statistics" role="img"></canvas>

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
            var ctx = document.getElementById('dealerChart');
            
            if (ctx) {
                var myCanvas = ctx.getContext('2d');
                
                fetch("{{ route('getDealerStats') }}")
                .then(response => response.json())
                .then(data => {
                    var dealerChart = new Chart(myCanvas, {
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
                                    text: ['Dealer Registrations', 'This Week vs Last Week'],
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
                .catch(error => console.error('Error fetching OMC data:', error));
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            var dealerAverageChart = document.getElementById('dealerAverageChart');
            
            if (dealerAverageChart) {
                var customersChartCanvas1 = dealerAverageChart.getContext('2d');
                
                fetch("{{ route('getDealerAverage') }}")
                .then(response => response.json())
                .then(data => {
                    var myDealerAverageChart = new Chart(customersChartCanvas1, {
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
                                        'Dealer Statistics',
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
                    dealerAverageChart.dealers = myDealerAverageChart;
                })
                .catch(error => console.error('Error fetching dealer data:', error));
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            fetch("{{ route('getTopDealers') }}")
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                let listElement = document.getElementById('topDealerList');
                listElement.innerHTML = '';
                
                if (!data || data.error) {
                    listElement.innerHTML = '<li>Error: ' + (data.error || 'No data available') + '</li>';
                    return;
                }
                
                if (data.length === 0) {
                    listElement.innerHTML = '<li>No Dealers available.</li>';
                    return;
                }
                
                data.forEach((dealer) => {
                    let li = document.createElement('li');
                    li.innerHTML = `
                    <a href="##">
                        <div class="top-cat-list__title">${dealer.name} <span>${dealer.station_count}</span></div>
                        <div class="top-cat-list__subtitle">Total Stations: <span class="purple">${dealer.station_count}</span></div>
                    </a>
                    `;
                    listElement.appendChild(li);
                });
            })
            .catch(error => {
                console.error('Error fetching top Dealers:', error);
                document.getElementById('topDealerList').innerHTML = '<li>Error loading Dealers. Please try again later.</li>';
            });
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

        function openModal(actionUrl, omcName) {
            const modal = document.getElementById("deleteModal");
            modal.classList.add("show");
            
            document.getElementById("deleteForm").setAttribute("action", actionUrl);
            
            document.getElementById("dealerNamePlaceholder").textContent = omcName;
        }

        function openSuspendModal(actionUrl, status, first_name, middle_name, last_name) {
            const modal = document.getElementById("suspendModal");
            modal.classList.add("show");
            
            document.getElementById("suspendForm").setAttribute("action", actionUrl);
            document.getElementById("suspendDealerName").textContent = [first_name, middle_name, last_name].filter(Boolean).join(" ");
            
            const form = document.getElementById("suspendForm");
            const confirmButton = form.querySelector(".confirm-button");
            const actionText = document.getElementById("suspendActionText");
            
            if (status === 'pending') {
                document.querySelector("#suspendModal h2").textContent = "Confirm Approval";
                actionText.textContent = "approve";
                confirmButton.textContent = "Approve";
            } else if (status === 'active') {
                document.querySelector("#suspendModal h2").textContent = "Confirm Suspension";
                actionText.textContent = "suspend";
                confirmButton.textContent = "Suspend";
            } else if (status === 'suspended') {
                document.querySelector("#suspendModal h2").textContent = "Confirm Activation";
                actionText.textContent = "activate";
                confirmButton.textContent = "Activate";
            }
        }
        
        function closeModal() {
            const modal = document.getElementById("deleteModal");
            modal.classList.remove("show");
        }
        
        function closeSuspendModal() {
            const modal = document.getElementById("suspendModal");
            modal.classList.remove("show");
        }

    </script>

@endsection