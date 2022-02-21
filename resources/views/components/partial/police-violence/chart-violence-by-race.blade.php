<div class="stat-wrapper grouped">
    <a href="https://github.com/campaignzero/ca-police-scorecard/blob/master/ca_police_scorecard.ipynb" rel="noopener" target="_blank" class="external-link" {!! trackData('External Nav', 'Violence by Race', 'Source Data') !!}>
        <span class="sr-only">Open in New Window</span>
    </a>

    <h3>Police Violence by Race</h3>

    <div class="keys">
        <span class="key key-red tooltip" data-tooltip="Black"></span> Black
        <span class="key key-orange tooltip" data-tooltip="Hispanic"></span> Latinx
        <span class="key key-grey tooltip" data-tooltip="Native American"></span> N.Am
        <span class="key key-black tooltip" data-tooltip="Asian Pacific Islander"></span> API
        <span class="key key-green tooltip" data-tooltip="Other"></span> Other
        <span class="key key-white tooltip" data-tooltip="White"></span> White
    </div>

    <p>Population of {{ $scorecard['agency']['name'] }}</p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar grouped key-red" data-percent="{{ output(floatval($scorecard['agency']['black_population']), 0, '%') }}">
            <span>{{ (intval($scorecard['agency']['black_population']) > 5) ? num(intval($scorecard['agency']['black_population']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-orange" data-percent="{{ output(floatval($scorecard['agency']['hispanic_population']), 0, '%') }}">
            <span>{{ (intval($scorecard['agency']['hispanic_population']) > 5) ? num(intval($scorecard['agency']['hispanic_population']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-grey" data-percent="{{ output(floatval($scorecard['agency']['native_american_population']), 0, '%') }}">
            <span>{{ (intval($scorecard['agency']['native_american_population']) > 5) ? num(intval($scorecard['agency']['native_american_population']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-black" data-percent="{{ output(floatval($scorecard['agency']['asian_pacific_population']), 0, '%') }}">
            <span>{{ (intval($scorecard['agency']['asian_pacific_population']) > 5) ? num(intval($scorecard['agency']['asian_pacific_population']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-green" data-percent="{{ output(floatval($scorecard['agency']['other_population']), 0, '%') }}">
            <span>{{ (intval($scorecard['agency']['other_population']) > 5) ? num(intval($scorecard['agency']['other_population']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-white" data-percent="{{ output(floatval($scorecard['agency']['white_population']), 0, '%') }}">
            <span>{{ (intval($scorecard['agency']['white_population']) > 5) ? num(intval($scorecard['agency']['white_population']), 0, '%') : '' }}</span>
        </div>
    </div>

    <p>{{ $scorecard['agency']['name'] }} {{ $type === 'police-department' ? 'Police Dept' : 'Sheriff\'s Dept' }} Demographics</p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar grouped key-red" data-percent="{{ output(floatval($scorecard['report']['percent_officers_black']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_officers_black']) > 5) ? num(intval($scorecard['report']['percent_officers_black']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-orange" data-percent="{{ output(floatval($scorecard['report']['percent_officers_hispanic']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_officers_hispanic']) > 5) ? num(intval($scorecard['report']['percent_officers_hispanic']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-grey" data-percent="{{ output(floatval($scorecard['report']['percent_officers_native_american']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_officers_native_american']) > 5) ? num(intval($scorecard['report']['percent_officers_native_american']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-black" data-percent="{{ output(floatval($scorecard['report']['percent_officers_asian_pacific']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_officers_asian_pacific']) > 5) ? num(intval($scorecard['report']['percent_officers_asian_pacific']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-green" data-percent="{{ output(floatval($scorecard['report']['percent_officers_other']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_officers_other']) > 5) ? num(intval($scorecard['report']['percent_officers_other']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-white" data-percent="{{ output(floatval($scorecard['report']['percent_officers_white']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_officers_white']) > 5) ? num(intval($scorecard['report']['percent_officers_white']), 0, '%') : '' }}</span>
        </div>
    </div>

    <p>People Arrested</p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar grouped key-red" data-percent="{{ output(floatval($scorecard['report']['percent_black_arrests']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_black_arrests']) > 5) ? num(intval($scorecard['report']['percent_black_arrests']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-orange" data-percent="{{ output(floatval($scorecard['report']['percent_hispanic_arrests']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_hispanic_arrests']) > 5) ? num(intval($scorecard['report']['percent_hispanic_arrests']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-grey" data-percent="{{ output(floatval($scorecard['report']['percent_native_american_arrests']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_native_american_arrests']) > 5) ? num(intval($scorecard['report']['percent_native_american_arrests']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-black" data-percent="{{ output(floatval($scorecard['report']['percent_asian_pacific_arrests']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_asian_pacific_arrests']) > 5) ? num(intval($scorecard['report']['percent_asian_pacific_arrests']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-green" data-percent="{{ output(floatval($scorecard['report']['percent_other_arrests']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_other_arrests']) > 5) ? num(intval($scorecard['report']['percent_other_arrests']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-white" data-percent="{{ output(floatval($scorecard['report']['percent_white_arrests']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_white_arrests']) > 5) ? num(intval($scorecard['report']['percent_white_arrests']), 0, '%') : '' }}</span>
        </div>
    </div>

    <p>People Killed</p>
    <div class="progress-bar-wrapper">
        <div class="progress-bar animate-bar grouped key-red" data-percent="{{ output($scorecard['report']['percent_black_deadly_force'], 0, '%') }}">
            <span>{{ ($scorecard['report']['percent_black_deadly_force'] > 5) ? num($scorecard['report']['percent_black_deadly_force'], 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-orange" data-percent="{{ output($scorecard['report']['percent_hispanic_deadly_force'], 0, '%') }}">
            <span>{{ ($scorecard['report']['percent_hispanic_deadly_force'] > 5) ? num($scorecard['report']['percent_hispanic_deadly_force'], 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-grey" data-percent="{{ output(floatval($scorecard['report']['percent_native_american_deadly_force']), 0, '%') }}">
            <span>{{ (intval($scorecard['report']['percent_native_american_deadly_force']) > 5) ? num(intval($scorecard['report']['percent_native_american_deadly_force']), 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-black" data-percent="{{ output($scorecard['report']['percent_asian_pacific_islander_deadly_force'], 0, '%') }}">
            <span>{{ ($scorecard['report']['percent_asian_pacific_islander_deadly_force'] > 5) ? num($scorecard['report']['percent_asian_pacific_islander_deadly_force'], 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-green" data-percent="{{ output($scorecard['report']['percent_other_deadly_force'], 0, '%') }}">
            <span>{{ ($scorecard['report']['percent_other_deadly_force'] > 5) ? num($scorecard['report']['percent_other_deadly_force'], 0, '%') : '' }}</span>
        </div>
        <div class="progress-bar animate-bar grouped key-white" data-percent="{{ output($scorecard['report']['percent_white_deadly_force'], 0, '%') }}">
            <span>{{ ($scorecard['report']['percent_white_deadly_force'] > 5) ? num($scorecard['report']['percent_white_deadly_force'], 0, '%') : '' }}</span>
        </div>
    </div>

    @if($scorecard['report']['percentile_overall_disparity_index'])
    <p class="note" style="margin-top: 0">^&nbsp; More Racial Disparities in Deadly Force than {{ num((1 - intval($scorecard['report']['percentile_overall_disparity_index'])), 0, '%', true) }} of Depts &nbsp;&nbsp;</p>
    @endif

    <p class="source-link-wrapper">
        Source:
        <a href="https://crime-data-explorer.fr.cloud.gov/explorer/national/united-states/arrest" class="source-link" rel="noopener" target="_blank" {!! trackData('External Nav', 'Violence by Race', 'Uniform Crime Report') !!}>Uniform Crime Report</a>,
        <a href="https://http://mappingpoliceviolence.us/" class="source-link" rel="noopener" target="_blank" {!! trackData('External Nav', 'Violence by Race', 'Mapping Police Violence') !!}>Mapping Police Violence</a>,
        <a href="https://www.icpsr.umich.edu/web/NACJD/studies/37323" class="source-link" rel="noopener" target="_blank" {!! trackData('External Nav', 'Violence by Race', 'LEMAS') !!}>LEMAS</a>
    </p>
</div>
