import { BarController, BarElement, CategoryScale, Chart, Legend, LinearScale, Tooltip } from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

const rootStyles = getComputedStyle(document.documentElement);
const cssVar = (name, fallback) => rootStyles.getPropertyValue(name).trim() || fallback;

const colors = {
    items: cssVar('--abg-item', '#5B6FB5'),
    services: cssVar('--abg-service', '#2A9D8F'),
    expenses: cssVar('--abg-expense', '#E0874E'),
    grid: '#EFEBE2',
    axisText: cssVar('--abg-muted', '#5C5C5C'),
};

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
                bar: { borderRadius: 4, maxBarThickness: 36, categoryPercentage: 0.7, barPercentage: 0.9 },
            },
            scales: {
                x: {
                    stacked: true,
                    grid: { display: false },
                    ticks: { color: colors.axisText, font: { family: 'Archivo' } },
                },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    title: { display: true, text: 'TZS', color: colors.axisText, font: { family: 'Archivo' } },
                    grid: { color: colors.grid },
                    ticks: {
                        color: colors.axisText,
                        font: { family: 'Archivo' },
                        callback: (value) => Math.round(value).toLocaleString('en-US'),
                    },
                },
            },
            plugins: {
                legend: {
                    position: 'top',
                    align: 'start',
                    labels: { boxWidth: 12, boxHeight: 12, color: colors.axisText, font: { family: 'Archivo' } },
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
