<!-- Main Menu -->
<header>
    <nav class="section bg-gray header" role="navigation">
        <div class="content">
            <a href="/" class="logo" {!! trackData('Nav', 'Header', 'Logo') !!}>
                Police Scorecard
            </a>

            <div class="mobile-search-wrapper hide-desktop" id="mobile-search-wrapper">
                <a href="#mobile-search" aria-label="Toggle Search" class="toggle-mobile-search" {!! trackData('Nav', 'Header', 'Mobile Search') !!}>
                    <i class="fa fa-search fa-lg"></i>
                </a>
                <form method="post" id="mobile-search-form" class="search-form" autocomplete="off" onsubmit="return false;">
                    <button type="button" title="Search" class="search-button" {!! trackData('Nav', 'Header', 'Mobile Search') !!}>
                        <i class="fa fa-search fa-lg" id="mobile-search-icon"></i>
                    </button>
                    <input type="text" id="mobile-search" placeholder="SEARCH" class="form-control search-field" autocomplete="off" {!! trackData('Form', 'Mobile Search') !!}>
                    <div id="mobile-search-results-container"></div>
                </form>
            </div>

            <a href="#" id="mobile-toggle" {!! trackData('Nav', 'Header', 'Toggle Menu') !!}>
                <span class="sr-only">Toggle Menu</span>
            </a>

            <div id="menu">
                <ul>
                    <li><a href="/about" class="{{ (request()->is('about')) ? 'active' : '' }}" {!! trackData('Nav', 'Header', 'About the Data') !!}>About</a></li>
                    <li><a href="/findings" class="{{ (request()->is('findings')) ? 'active' : '' }}" {!! trackData('Nav', 'Header', 'Key Findings') !!}>Key Findings</a></li>
                    <li><a href="/sandiego" class="{{ (request()->is('sandiego')) ? 'active' : '' }}" {!! trackData('Nav', 'Header', 'Reports') !!}>Reports</a></li>
                    <li><a href="https://forms.gle/WPC2Z6A92tBqxGWZ8" rel="noopener" target="_blank" {!! trackData('Nav', 'Header', 'Contribute Data') !!}>Contribute</a></li>
                    <li><a href="https://www.paypal.com/donate?hosted_button_id=U32Y7FCWBULNG" class="donate" title="Donate to Police Scorecard" rel="noopener" target="_blank" {!! trackData('Nav', 'Header', 'Donate') !!}>Donate</a></li>
                    <li class="menu-divider visible-lg">&nbsp;</li>
                    <li class="search-wrapper hide-mobile">
                        <a href="#search" aria-label="Toggle Search" class="toggle-search" {!! trackData('Nav', 'Header', 'Search') !!}>
                            <i class="fa fa-search fa-lg"></i>
                        </a>
                        <form method="post" id="search-form" class="search-form animated fadeIn" autocomplete="off" onsubmit="return false;">
                            <button type="button" title="Search" class="search-button" {!! trackData('Nav', 'Header', 'Search') !!}>
                                <i class="fa fa-search fa-lg" id="search-icon"></i>
                            </button>
                            <input type="text" id="search" placeholder="SEARCH" class="form-control search-field" autocomplete="off" {!! trackData('Form', 'Search') !!}>
                            <div id="search-results-container"></div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
