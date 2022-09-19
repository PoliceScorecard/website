<!-- Hero -->
<div class="section bg-dotted current-state"id="content">
  <div class="content">
    <a href="#" id="state-selection" {!! trackData('Nav', 'Hero', 'State Selection') !!}>
      <span class="state-symbol">{{ getStateIcon($state) }}</span>
      {{ getStateName($state) }}
    </a>
  </div>
</div>

<div class="section hero report">
  <div class="content">
    <div class="right">
      @if (isset($stateData['police-department']) && isset($stateData['sheriff'])):
        <h1>We obtained data on {{ num(count($stateData['police-department'])) }} Police and {{ num(count($stateData['sheriff'])) }} Sheriff’s Depts in the state of {{ getStateName($state) }}.</h1>
      @elseif (isset($stateData['police-department'])):
        <h1>We obtained data on {{ num(count($stateData['police-department'])) }} Police and Depts in the state of {{ getStateName($state) }}.</h1>
      @elseif (isset($stateData['sheriff'])):
        <h1>We obtained data on {{ num(count($stateData['sheriff'])) }} Sheriff’s Depts in the state of {{ getStateName($state) }}.</h1>
      @endif
    </div>
    <div class="left">
      <div class="map" id="state-map-layer">
        <svg style="position: absolute; left: -10000px; top: -10000px;">
          <defs>
            <filter id="drop-shadow">
              <feOffset dx='0' dy='0' />
              <feGaussianBlur stdDeviation='2' result='offset-blur' />
              <feComposite operator='out' in='SourceGraphic' in2='offset-blur' result='inverse' />
              <feFlood flood-color='black' flood-opacity='1' result='color' />
              <feComposite operator='in' in='color' in2='inverse' result='shadow' />
              <feComposite operator='over' in='shadow' in2='SourceGraphic' />
            </filter>
          </defs>
        </svg>

        <div id="map-loading">
          <i class="fa fa-spinner fa-spin"></i>&nbsp; Loading Map ...
        </div>
        <div id="state-map" class="{{ $type }}"></div>
        <div id="state-map-shadow" class="{{ $type }}"></div>
      </div>
    </div>
    <div class="clear">&nbsp;</div>
  </div>
</div>
