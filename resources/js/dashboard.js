import { BarController, BarElement, CategoryScale, Chart, Legend, LinearScale, Tooltip } from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const colors = {
    items: '#2B6CB0',
    services: '#D9480F',
    expenses: '#2B9E8F',
};

/**
 * Matches formatCompactAmount() in app/Helpers.php: 36.50M from one million up, 850k below that.
 */
function formatCompactAmount(value) {
    const absolute = Math.abs(value);

    if (absolute >= 999500) {
        return `${(value / 1000000).toFixed(2)}M`;
    }

    if (absolute >= 1000) {
        return `${Math.round(value / 1000).toLocaleString('en-US')}k`;
    }

    return Math.round(value).toLocaleString('en-US');
}

function formatFullAmount(value) {
    return `TZS ${Math.round(value).toLocaleString('en-US')}`;
}

const canvas = document.getElementById('sales-expenses-chart');
const dataElement = document.getElementById('sales-expenses-data');

if (canvas && dataElement) {
    const months = JSON.parse(dataElement.textContent);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: months.map((month) => month.label),
            datasets: [
                { label: 'Items', data: months.map((month) => month.items), backgroundColor: colors.items, stack: 'sales' },
                { label: 'Services', data: months.map((month) => month.services), backgroundColor: colors.services, stack: 'sales' },
                { label: 'Expenses', data: months.map((month) => month.expenses), backgroundColor: colors.expenses, stack: 'expenses' },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            datasets: {
                bar: { borderRadius: 3, maxBarThickness: 36, categoryPercentage: 0.7, barPercentage: 0.9 },
            },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: { display: true, text: 'TZS' },
                    ticks: { callback: (value) => formatCompactAmount(value) },
                },
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'start',
                    labels: { boxWidth: 12, boxHeight: 12 },
                },
                tooltip: {
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${formatFullAmount(context.raw)}`,
                        afterLabel: (context) => context.datasetIndex === 1
                            ? `Total sales: ${formatFullAmount(months[context.dataIndex].total)}`
                            : '',
                    },
                },
            },
        },
    });
}
