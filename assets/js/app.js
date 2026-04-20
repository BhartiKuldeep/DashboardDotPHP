document.addEventListener('DOMContentLoaded', () => {
    if (window.dashboardCharts?.financeTrend && document.getElementById('financeTrendChart')) {
        const financeTrend = window.dashboardCharts.financeTrend;
        const ctx = document.getElementById('financeTrendChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: financeTrend.labels,
                datasets: [
                    {
                        label: 'Income',
                        data: financeTrend.income,
                        borderWidth: 0,
                    },
                    {
                        label: 'Expense',
                        data: financeTrend.expense,
                        borderWidth: 0,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        labels: {
                            color: '#cbd5e1'
                        }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#94a3b8' },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    },
                    y: {
                        ticks: { color: '#94a3b8' },
                        grid: { color: 'rgba(148, 163, 184, 0.08)' }
                    }
                }
            }
        });
    }

    if (window.dashboardCharts?.taskStatus && document.getElementById('taskStatusChart')) {
        const taskStatus = window.dashboardCharts.taskStatus;
        const ctx = document.getElementById('taskStatusChart');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: taskStatus.labels,
                datasets: [{ data: taskStatus.values, borderWidth: 0 }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: '#cbd5e1' }
                    }
                }
            }
        });
    }
});
