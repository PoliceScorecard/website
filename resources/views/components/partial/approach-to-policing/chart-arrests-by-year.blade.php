@if (isset($scorecard['arrests']['arrests_2016']) && isset($scorecard['arrests']['arrests_2017']) && isset($scorecard['arrests']['arrests_2018']))
<div class="stat-wrapper">
    <h3>Arrests By Year</h3>
    <p>{{ num($scorecard['report']['total_arrests']) }} Arrests Reported from 2013-2021</p>

    <div class="keys" style="margin-top: 18px; margin-bottom: 6px;">
        <span class="key key-red tooltip" data-tooltip="Black"></span> Low Level Arrests
        <span class="key key-grey tooltip" data-tooltip="Grey"></span> Other Arrests
    </div>

    <p style="margin-top: 18px; margin-bottom: 6px;">
        <canvas id="bar-chart-arrests"></canvas>
    </p>
</div>
@endif
