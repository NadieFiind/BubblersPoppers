<?php declare(strict_types=1);

function render(): void
{
    echo <<<HTML
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div style="height: 100vh; width: 100vw;">
        <canvas id="chart"></canvas>
    </div>
    <span class="bubbles-count"></span>
    <script src="/public/scripts/stats.js"></script>
HTML;
}
