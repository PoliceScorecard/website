<div class="stat-wrapper">
    <h3>Total civilian complaints</h3>

    @if (output($scorecard['police_accountability']['civilian_complaints_reported']) === '0')
    <p>0 Complaints Reported</p>
    @else
    <p>
        {{ num($scorecard['police_accountability']['civilian_complaints_reported']) }} from {{ $scorecard['police_accountability']['years_of_complaints_data'] }}
        <span class="divider">&nbsp;|&nbsp;</span>
        {{ num($scorecard['report']['complaints_sustained'], 0, '%') }} Ruled in Favor of Civilians
    </p>
    @endif

    @if (!isset($scorecard['report']['complaints_sustained']))
    <div class="progress-bar-wrapper">
        <div class="progress-bar no-data" style="width: 0"></div>
    </div>
    <x-partial.no-data-found />
    @else
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar {{ progressBar(100 - intval($scorecard['report']['complaints_sustained']), 'reverse') }}" data-percent="{{ output(intval($scorecard['report']['complaints_sustained']), 0, '%') }}"></div>
    </div>
    <p class="note">&nbsp;</p>
    @endif

    <h3>Complaints by Year</h3>

    <div class="keys">
        <span class="key key-red tooltip" data-tooltip="Black"></span> Civilian Reported
        <span class="key key-orange tooltip" data-tooltip="Hispanic"></span> Use of Force Reported
    </div>
    <div class="keys" style="margin-top: 6px;">
        <span class="key key-grey tooltip" data-tooltip="Native American"></span> Discrimination Reported
        <span class="key key-black tooltip" data-tooltip="Asian Pacific Islander"></span> Criminal Reported
    </div>
    <div class="keys" style="margin-top: 6px;">
        <span class="key key-green tooltip" data-tooltip="Other"></span> In Detention Reported
        <span class="key key-white tooltip" data-tooltip="White"></span> Sustained
    </div>

    <p style="margin-top: 18px; margin-bottom: 6px;">
        <canvas id="bar-chart-complaints" height="350"></canvas>
    </p>
</div>
