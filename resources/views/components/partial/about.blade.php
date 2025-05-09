<!-- About This Scorecard -->
<div class="section why">
    <div class="content">
        <h1 class="title">
            About This Scorecard
        </h1>
        <p>
            <strong>This is the first nationwide evaluation of policing in the United States.</strong> It was built using data from state and federal databases, public records requests to local police departments, and media reports. While police data is never perfect, and there are additional indicators that still need to be tracked, the Police Scorecard is designed to provide insight into many important issues in policing.
        </p>
        <p>&nbsp;</p>
        <p>
            <strong>Police Scorecard is an independent 501(c)(3) organization, learn more about our team <a href="/about" {!! trackData('Nav', 'About', 'Team') !!}>here</a>.</strong> If you have feedback, questions about the project, or need support with an advocacy campaign, contact our Founder, <a href="mailto:samswey1@gmail.com" rel="noopener" target="_blank" {!! trackData('External Nav', 'About', 'Contact Founder') !!}>Samuel Sinyangwe</a>.
        </p>
        <p>
            <a href="/about" class="button" {!! trackData('Nav', 'About', 'Methodology') !!}>methodology</a>
            <a href="https://drive.google.com/drive/folders/1XAT1uFPXj5AsvNTzFeNeeTXGLP09HEIh" rel="noopener" target="_blank" class="button" {!! trackData('External Nav', 'About', 'Source Data') !!}>Source Data</a>
        </p>
        <p>&nbsp;</p>
        <p>
            <strong>Use this Scorecard to identify issues within police departments that require the most urgent interventions and hold officials accountable for implementing solutions.</strong> For example, cities with higher rates of low level arrests could benefit most from solutions that create alternatives to policing and arrest for these offenses. In cities where police make fewer arrests overall but use more force when making arrests, communities could benefit significantly from policies designed to hold police accountable for excessive force. And cities where complaints of police misconduct are rarely ruled in favor of civilians could benefit from creating an oversight structure to independently investigate these complaints.
        </p>
    </div>
    <div class="content">
        <p>&nbsp;</p>
    </div>
    <div class="content">
        <h2 class="take-action">Here's how to start pushing for change</h2>

        <div class="tabs">
            <div role="tablist" class="tab-buttons" aria-label="Push for Change">
                <button role="tab" aria-selected="true" aria-controls="advocacy-tab" id="advocacy" {!! trackData('Tab', 'Push for Change', 'Advocacy') !!}>Advocacy</button>
                <button role="tab" aria-selected="false" aria-controls="research-tab" id="research" tabindex="-1" {!! trackData('Tab', 'Push for Change', 'Research') !!}>Research</button>
                <button role="tab" aria-selected="false" aria-controls="data-visualization-tab" id="data-visualization" tabindex="-1" {!! trackData('Tab', 'Push for Change', 'Data Visualization') !!}>Data Visualization</button>
            </div>
            <div class="tab-pane" tabindex="0" role="tabpanel" id="advocacy-tab" aria-labelledby="advocacy">
                <div class="left number number-1">
                    <ul>
                        <li>
                        @if ($type === 'state')
                            <strong>Contact your State's Governor and Attorney General</strong>, share your scorecard with them and urge them to enact policies to address the issues you've identified:
                        @elseif ($type === 'police-department')
                            <strong>Contact your Mayor and Police Chief</strong>, share your scorecard with them and urge them to enact policies to address the issues you've identified:
                        @elseif ($type === 'sheriff')
                            <strong>Contact Your County Sheriff</strong>, share your scorecard with them and urge them to enact policies to address the issues you've identified:
                        @endif

                        @if (empty($scorecard['agency']))
                        <ul class="contacts">
                            <li>
                                <a href="https://www.usa.gov/state-governor" class="button" rel="noopener" target="_blank" {!! trackData('External Nav', 'Contact', 'Find Find State Governors') !!}>Find State Governors</a>
                            </li>
                        </ul>
                        @endif

                        @if (!empty($scorecard['agency']))
                        <ul class="contacts">
                            @if (!empty($scorecard['agency']['mayor_name']))
                            <li>
                                <strong>{{ ($type !== 'state') ? 'Mayor ' : '' }}{{ $scorecard['agency']['mayor_name'] }}</strong>

                                @if (!empty($scorecard['agency']['mayor_phone']))
                                <br> Phone:&nbsp;
                                <a href="tel:1-{{ $scorecard['agency']['mayor_phone'] }}" {!! trackData('Contact', 'Mayor Phone', $scorecard['agency']['mayor_name']) !!}>
                                    {{ $scorecard['agency']['mayor_phone'] }}
                                </a>
                                @endif

                                @if (!empty($scorecard['agency']['mayor_email']))
                                <br> Email:&nbsp;
                                <a href="mailto:{{ $scorecard['agency']['mayor_email'] }}" {!! trackData('Contact', 'Mayor Email', $scorecard['agency']['mayor_name']) !!}>
                                    {{ $scorecard['agency']['mayor_email'] }}
                                </a>
                                @endif
                            </li>
                            @endif

                            @if (!empty($scorecard['agency']['police_chief_name']))
                            <li>
                                <strong>{{ ($type === 'police-department') ? 'Police Chief' : '' }} {{ $scorecard['agency']['police_chief_name'] }}</strong>

                                @if (!empty($scorecard['agency']['police_chief_phone']))
                                <br> Phone:&nbsp;
                                <a href="tel:1-{{ $scorecard['agency']['police_chief_phone'] }}" {!! trackData('Contact', 'Police Chief Phone', $scorecard['agency']['police_chief_name']) !!}>
                                    {{ $scorecard['agency']['police_chief_phone'] }}
                                </a>
                                @endif

                                @if (!empty($scorecard['agency']['police_chief_email']))
                                <br> Email:&nbsp;
                                <a href="mailto:{{ $scorecard['agency']['police_chief_email'] }}" {!! trackData('Contact', 'Police Chief Email', $scorecard['agency']['police_chief_name']) !!}>
                                    {{ $scorecard['agency']['police_chief_email'] }}
                                </a>
                                @endif
                            </li>
                            @endif

                            @if (empty($scorecard['agency']['mayor_name']) && empty($scorecard['agency']['police_chief_name']))
                            <li>
                                <a href="https://www.usa.gov/state-governor" class="button" rel="noopener" target="_blank" {!! trackData('External Nav', 'Contact', 'Find Find State Governors') !!}>Find State Governors</a>
                            </li>
                            @endif
                        </ul>
                        @endif

                        @if (!empty($scorecard['agency']['advocacy_tip']))
                        <div class="advocacy-tip">
                            <strong>Advocacy Tip:</strong>&nbsp; {{ $scorecard['agency']['advocacy_tip'] }}
                        </div>
                        @endif
                        </li>
                    </ul>
                </div>
                <div class="right number number-2">
                    <ul>
                        <li>
                            <strong>Look up your state and federal representatives below</strong>, then tell them to take action to hold police accountable in your community.
                            <br />
                            <a href="https://www.usa.gov/elected-officials" class="button" rel="noopener" target="_blank" {!! trackData('External Nav', 'Contact', 'Find Elected Officials') !!}>Find Elected Officials</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="tab-pane" tabindex="0" role="tabpanel" id="research-tab" aria-labelledby="research" hidden="">
                <p>Join a team of researchers, students, data scientists, activists and organizers working to collect, analyze and use data for justice and accountability.</p>
                <p><a href="https://form.typeform.com/to/jBvCkB?ref=research" class="button" rel="noopener" target="_blank" {!! trackData('External Nav', 'About', 'Join Research') !!}>Join</a></p>
            </div>
            <div class="tab-pane" tabindex="0" role="tabpanel" id="data-visualization-tab" aria-labelledby="data-visualization" hidden="">
                <p>Create data visualizations and content that raises awareness about solutions to the issues identified by the data.</p>
                <p><a href="https://form.typeform.com/to/jBvCkB?ref=data-visualization" class="button" rel="noopener" target="_blank" {!! trackData('External Nav', 'About', 'Join Data Visualization') !!}>Join</a></p>
            </div>
        </div>
    </div>
</div>
