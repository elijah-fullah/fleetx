


"use strict";

document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('omcChart');

    if (ctx) {
        var myCanvas = ctx.getContext('2d');

        fetch("/getOMCStats")
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
                                color: '#ddd'
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
                            color: '#171717',
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
