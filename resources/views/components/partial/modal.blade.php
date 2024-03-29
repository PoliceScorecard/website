<!-- Modal -->
<div id="modal-wrapper">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modal-label" id="dialog">
        <div id="modal-header-tabs">
            <button title="Close Modal" id="modal-close" {!! trackData('Nav', 'Modal' , 'Close' ) !!}>✖</button>

            @if (isset($stateData['police-department']) && !empty($stateData['police-department']))
            <button title="List Police Departments" class="show-button{{ ($type === 'state' || $type === 'police-department') ? ' active' : '' }}" id="show-police" {!! trackData('Nav', 'Modal' , 'Show Police' ) !!}>Police</button>
            @endif

            @if (isset($stateData['sheriff']) && !empty($stateData['sheriff']))
            <button title="List Sheriff Departments" class="show-button{{ $type === 'sheriff' ? ' active' : '' }}" id="show-sheriff" {!! trackData('Nav', 'Modal' , 'Show Sheriffs' ) !!}>Sheriffs</button>
            @endif
        </div>

        <div id="modal-content">
            <div id="modal-label">Select Department</div>
            <div id="more-info-content"></div>
            <div id="results-info-content"></div>

            @if ($stateData)
            <ul id="city-select" class="{{ $type }}">
                @if (isset($stateData['police-department']) && !empty($stateData['police-department']))
                @php usort($stateData['police-department'], function($a, $b) { return strcmp($a['agency_name'], $b['agency_name']); }); @endphp
                @foreach($stateData['police-department'] as $index => $department)
                <li class="police-department">
                <a href="{{ $department['url_pretty'] }}" title="View Report: {{ $department['title'] }}" {!! ($type === 'police-department' && $location === $department['slug']) ? ' class="selected-city"' : '' !!} {!! trackData('Nav', 'Modal Police' , $department['agency_name'] ) !!}>
                        {{ $department['agency_name'] }} Police
                    </a>
                </li>
                @endforeach
                @endif

                @if (isset($stateData['sheriff']) && !empty($stateData['sheriff']))
                @php usort($stateData['sheriff'], function($a, $b) { return strcmp($a['agency_name'], $b['agency_name']); }); @endphp
                @foreach($stateData['sheriff'] as $index => $department)
                <li class="sheriff">
                    <a href="{{ $department['url_pretty'] }}" title="View Report: {{ $department['title'] }}" {!! ($type === 'sheriff' && $location === $department['slug']) ? ' class="selected-city"' : '' !!} {!! trackData('Nav', 'Modal Sheriff' , $department['agency_name'] ) !!}>
                        {{ $department['agency_name'] }} Sheriff
                    </a>
                </li>
                @endforeach
                @endif
            </ul>
            @endif

            <ul id="state-select">
                @foreach ($states as $key => $departments)
                <li>{!! generateStateLink($key, $state) !!}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div id="overlay"></div>
</div>
