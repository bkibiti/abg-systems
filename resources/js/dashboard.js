import { CategoryScale, Chart, Filler, Legend, LinearScale, LineController, LineElement, PointElement, Tooltip } from 'chart.js';

Chart.register(LineController, LineElement, PointElement, CategoryScale, LinearScale, Filler, Tooltip, Legend);

const rootStyles = getComputedStyle(document.documentElement);
const cssVar = (name, fallback) => rootStyles.getPropertyValue(name).trim() || fallback;

const colors = {
    sales: cssVar('--abg-sales', '#2F6B8A'),
    expenses: cssVar('--abg-expense', '#E0874E'),
    grid: '#EFEBE2',
    axisText: cssVar('--abg-muted', '#5C5C5C'),
};

function formatFullAmount(value) {
    return `TZS ${Math.round(value).toLocaleString('en-US')}`;
}

/** "#2F6B8A" + 0.08 -> "rgba(47, 107, 138, 0.08)" */
function withAlpha(hex, alpha) {
    const value = hex.replace('#', '');
    const [red, green, blue] = [0, 2, 4].map((start) => parseInt(value.slice(start, start + 2), 16));

    return `rgba(${red}, ${green}, ${blue}, ${alpha})`;
}

const lineStyle = (color) => ({
    borderColor: color,
    borderWidth: 3,
    tension: 0.3,
    pointRadius: 3,
    pointHoverRadius: 6,
    pointBackgroundColor: '#FFFFFF',
    pointBorderColor: color,
    pointBorderWidth: 2,
    pointHoverBackgroundColor: '#FFFFFF',
    pointHoverBorderColor: color,
});

const canvas = document.getElementById('sales-expenses-chart');
const dataElement = document.getElementById('sales-expenses-data');

if (canvas && dataElement) {
    const months = JSON.parse(dataElement.textContent);

    new Chart(canvas, {
        type: 'line',
        data: {
            labels: months.map((month) => month.label),
            datasets: [
                {
                    label: 'Sales',
                    // Months without sales are 0, never a gap
                    data: months.map((month) => Number(month.total) || 0),
                    ...lineStyle(colors.sales),
                    fill: 'origin',
                    backgroundColor: withAlpha(colors.sales, 0.08),
                },
                {
                    label: 'Expenses',
                    data: months.map((month) => Number(month.expenses) || 0),
                    ...lineStyle(colors.expenses),
                    borderDash: [6, 4],
                    fill: false,
                    backgroundColor: colors.expenses,
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: colors.axisText, font: { family: 'Archivo' } },
                },
                y: {
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
                    // A short line sample in the legend, so the dashed Expenses line is recognisable there too
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'line',
                        pointStyleWidth: 28,
                        color: colors.axisText,
                        font: { family: 'Archivo' },
                    },
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: (context) => `${context.dataset.label}: ${formatFullAmount(context.raw)}`,
                    },
                },
            },
        },
    });
}
