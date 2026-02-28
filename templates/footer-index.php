<!-- Chart.js library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DASHBOARD-SPECIFIC JAVASCRIPT -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let revenueEnergyChart;
        const chartCanvas = document.getElementById('revenueEnergyChart');
        if (!chartCanvas) return; // Exit if the chart canvas is not on this page

        // Helper function to get the current theme's colors from CSS variables
        function getComputedChartStyles() {
            const styles = getComputedStyle(document.documentElement);
            return {
                accentBlue: styles.getPropertyValue('--accent-blue').trim(),
                accentGreen: styles.getPropertyValue('--accent-green').trim(),
                borderColor: styles.getPropertyValue('--border-color').trim(),
                textMuted: styles.getPropertyValue('--text-muted').trim()
            };
        }

        // Function that specifically updates the chart's colors
        function updateChartColors() {
            if (!revenueEnergyChart) return;
            const newStyles = getComputedChartStyles();

            revenueEnergyChart.data.datasets[0].backgroundColor = newStyles.accentBlue;
            revenueEnergyChart.data.datasets[0].borderColor = newStyles.accentBlue;
            revenueEnergyChart.data.datasets[1].backgroundColor = newStyles.accentGreen;
            revenueEnergyChart.data.datasets[1].borderColor = newStyles.accentGreen;

            revenueEnergyChart.options.scales.x.ticks.color = newStyles.textMuted;
            revenueEnergyChart.options.scales.y.grid.color = newStyles.borderColor;
            revenueEnergyChart.options.scales.y.ticks.color = newStyles.textMuted;
            revenueEnergyChart.options.scales.y1.ticks.color = newStyles.textMuted;

            revenueEnergyChart.update('none'); // Update without animation for a smooth switch
        }

        // Function to create the chart initially
        function createRevenueEnergyChart() {
            const ctx = chartCanvas.getContext('2d');
            const labels = Array.from({
                length: 30
            }, (_, i) => i + 1);
            const revenueData = labels.map(() => Math.random() * 4000 + 1000);
            const energyData = revenueData.map(rev => rev / 20 + Math.random() * 20);
            const initialStyles = getComputedChartStyles();

            revenueEnergyChart = new Chart(ctx, {
                type: 'bar', // Main chart type is bar
                data: {
                    labels: labels,
                    datasets: [
                        // MODIFIED: This is now a BAR chart for Amount
                        {
                            type: 'bar', // Changed from 'line' back to 'bar'
                            label: 'Amount (₹)',
                            data: revenueData,
                            backgroundColor: initialStyles.accentBlue,
                            borderColor: initialStyles.accentBlue,
                            yAxisID: 'y',
                            // Bar-specific properties
                            borderRadius: 4,
                            borderWidth: 1,
                            barPercentage: 0.5, // Adjusted to make bars slightly thinner
                            categoryPercentage: 0.8
                        },
                        // This remains a BAR chart for Energy
                        {
                            type: 'bar',
                            label: 'Energy (kWh)',
                            data: energyData,
                            backgroundColor: initialStyles.accentGreen,
                            borderColor: initialStyles.accentGreen,
                            yAxisID: 'y1',
                            // Bar-specific properties
                            borderRadius: 4,
                            borderWidth: 1,
                            barPercentage: 0.5, // Adjusted to make bars slightly thinner
                            categoryPercentage: 0.8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            /* tooltip callbacks */ }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: initialStyles.textMuted
                            }
                        },
                        y: {
                            /* Your existing Y-axis options */ },
                        y1: {
                            /* Your existing Y1-axis options */ }
                    }
                }
            });
        }

        // Theme change observer (no changes needed here)
        const themeObserver = new MutationObserver((mutationsList) => {
            for (const mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'data-theme') {
                    updateChartColors();
                }
            }
        });
        themeObserver.observe(document.documentElement, {
            attributes: true
        });

        // Initial Setup
        createRevenueEnergyChart();
    });
</script>