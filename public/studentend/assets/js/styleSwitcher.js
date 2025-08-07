"use strict";

/**
 * Theme Switcher Configuration
 * Customizable admin panel theme switcher with color options and layout settings
 */

// ========================================
// HTML TEMPLATES
// ========================================

/**
 * Main theme switcher sidebar HTML template
 */
function getThemeSwitcherHTML() {
    return `
        <div class="sidebar-right">
            <div class="bg-overlay"></div>
            
            <!-- Trigger Button -->
            <a class="sidebar-right-trigger wave-effect wave-effect-x" 
               data-bs-toggle="tooltip" 
               data-placement="right" 
               data-original-title="Change Layout" 
               href="javascript:void(0);">
                <span><i class="fa fa-cog fa-spin"></i></span>
            </a>
            
            <!-- Close Button -->
            <a class="sidebar-close-trigger" href="javascript:void(0);">
                <span><i class="la-times las"></i></span>
            </a>
            
            <!-- Sidebar Content -->
            <div class="sidebar-right-inner">
                <h4>Pick your style 
                    <a href="javascript:void(0);" 
                       onclick="deleteAllCookie()" 
                       class="btn btn-primary btn-sm pull-right">
                        Delete All Cookie
                    </a>
                </h4>
                
                <!-- Tab Navigation -->
                <div class="card-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#tab1" data-bs-toggle="tab">Theme</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab2" data-bs-toggle="tab">Header</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tab3" data-bs-toggle="tab">Content</a>
                        </li>
                    </ul>
                </div>
                
                <!-- Tab Content -->
                <div class="tab-content tab-content-default tabcontent-border">
                    ${getThemeTabContent()}
                    ${getHeaderTabContent()}
                    ${getContentTabContent()}
                </div>
            </div>
        </div>
    `;
}

/**
 * Theme tab content (colors and background)
 */
function getThemeTabContent() {
    return `
        <div class="fade tab-pane active show" id="tab1">
            <div class="admin-settings">
                <div class="row">
                    <!-- Background Theme -->
                    <div class="col-sm-12">
                        <p>Background</p>
                        <select class="default-select wide form-control" id="theme_version" name="theme_version">
                            <option value="light">Light</option>
                            <option value="dark">Dark</option>
                        </select>
                    </div>
                    
                    <!-- Primary Color -->
                    <div class="col-sm-6">
                        <p>Primary Color</p>
                        <div>${generateColorOptions('primary_color', 'primary_bg')}</div>
                    </div>
                    
                    <!-- Navigation Header -->
                    <div class="col-sm-6">
                        <p>Navigation Header</p>
                        <div>${generateColorOptions('nav_header_color', 'navigation_header')}</div>
                    </div>
                    
                    <!-- Header -->
                    <div class="col-sm-6">
                        <p>Header</p>
                        <div>${generateColorOptions('header_color', 'header_bg')}</div>
                    </div>
                    
                    <!-- Sidebar -->
                    <div class="col-sm-6">
                        <p>Sidebar</p>
                        <div>${generateColorOptions('sidebar_color', 'sidebar_bg')}</div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Header tab content (layout and position settings)
 */
function getHeaderTabContent() {
    return `
        <div class="fade tab-pane" id="tab2">
            <div class="admin-settings">
                <div class="row">
                    <!-- Layout -->
                    <div class="col-sm-6">
                        <p>Layout</p>
                        <select class="default-select wide form-control" id="theme_layout" name="theme_layout">
                            <option value="vertical">Vertical</option>
                            <option value="horizontal">Horizontal</option>
                        </select>
                    </div>
                    
                    <!-- Header Position -->
                    <div class="col-sm-6">
                        <p>Header position</p>
                        <select class="default-select wide form-control" id="header_position" name="header_position">
                            <option value="static">Static</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                    
                    <!-- Sidebar Style -->
                    <div class="col-sm-6">
                        <p>Sidebar</p>
                        <select class="default-select wide form-control" id="sidebar_style" name="sidebar_style">
                            <option value="full">Full</option>
                            <option value="mini">Mini</option>
                            <option value="compact">Compact</option>
                            <option value="modern">Modern</option>
                            <option value="overlay">Overlay</option>
                            <option value="icon-hover">Icon-hover</option>
                        </select>
                    </div>
                    
                    <!-- Sidebar Position -->
                    <div class="col-sm-6">
                        <p>Sidebar position</p>
                        <select class="default-select wide form-control" id="sidebar_position" name="sidebar_position">
                            <option value="static">Static</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Content tab (container and typography settings)
 */
function getContentTabContent() {
    return `
        <div class="fade tab-pane" id="tab3">
            <div class="admin-settings">
                <div class="row">
                    <!-- Container Layout -->
                    <div class="col-sm-6">
                        <p>Container</p>
                        <select class="default-select wide form-control" id="container_layout" name="container_layout">
                            <option value="wide">Wide</option>
                            <option value="boxed">Boxed</option>
                            <option value="wide-boxed">Wide Boxed</option>
                        </select>
                    </div>
                    
                    <!-- Typography -->
                    <div class="col-sm-6">
                        <p>Body Font</p>
                        <select class="default-select wide form-control" id="typography" name="typography">
                            <option value="roboto">Roboto</option>
                            <option value="poppins">Poppins</option>
                            <option value="opensans">Open Sans</option>
                            <option value="HelveticaNeue">HelveticaNeue</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    `;
}

/**
 * Demo panel HTML template
 */
function getDemoPanelHTML() {
    return `
        <div class="dz-demo-panel">
            <div class="bg-close"></div>
            
            <!-- Demo Trigger -->
            <a class="dz-demo-trigger" 
               data-toggle="tooltip" 
               data-placement="right" 
               data-original-title="Check out more demos" 
               href="javascript:void(0)">
                <span><i class="las la-tint"></i></span>
            </a>
            
            <!-- Demo Content -->
            <div class="dz-demo-inner">
                <a href="javascript:void(0);" 
                   class="btn btn-primary btn-sm px-2 py-1 mb-3" 
                   onclick="deleteAllCookie()">
                    Delete All Cookie
                </a>
                
                <div class="dz-demo-header">
                    <h4>Select A Demo</h4>
                    <a class="dz-demo-close" href="javascript:void(0);">
                        <span><i class="las la-times"></i></span>
                    </a>
                </div>
                
                <div class="dz-demo-content">
                    <div class="dz-wrapper">
                        ${generateDemoOptions()}
                    </div>
                    <div class="fs-12 pt-3">
                        <span class="text-danger">*Note :</span> This theme switcher is not part of product. 
                        It is only for demo. you will get all guideline in documentation. 
                        please check <a href="javascript:void(0);" class="text-primary">documentation.</a>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// ========================================
// HELPER FUNCTIONS
// ========================================

/**
 * Generate color option radio buttons
 * @param {string} idPrefix - Prefix for input IDs
 * @param {string} inputName - Name attribute for radio group
 * @returns {string} HTML string for color options
 */
function generateColorOptions(idPrefix, inputName) {
    const colors = [1, 2, 3, 4, 5, 6, 7, 9, 10, 11, 12, 13, 14, 15];
    let html = '';
    
    colors.forEach(colorNum => {
        const isFirstColor = colorNum === 1;
        const labelClass = isFirstColor ? ' class="bg-label-pattern"' : '';
        const tooltip = isFirstColor ? ' data-placement="top" data-bs-toggle="tooltip" title="Color 1"' : '';
        
        html += `
            <span${tooltip}>
                <input class="chk-col-primary filled-in" 
                       id="${idPrefix}_${colorNum}" 
                       name="${inputName}" 
                       type="radio" 
                       value="color_${colorNum}">
                <label for="${idPrefix}_${colorNum}"${labelClass}></label>
            </span>
        `;
    });
    
    return html;
}

/**
 * Generate demo option panels
 * @returns {string} HTML string for demo options
 */
function generateDemoOptions() {
    let html = '';
    
    for (let i = 1; i <= 6; i++) {
        html += `
            <div class="overlay-bx dz-demo-bx">
                <div class="overlay-wrapper${i === 3 ? ' ' : ''}">
                    <img src="images/demo/pic${i}.jpg" alt="" class="w-100">
                </div>
                <div class="overlay-layer">
                    <a href="javascript:void(0);" 
                       data-theme="${i}" 
                       class="btn dz_theme_demo btn-secondary btn-sm mr-2">
                        Demo ${i}
                    </a>
                </div>
            </div>
            <h5 class="text-black mb-2">Demo ${i}</h5>
            ${i < 6 ? '<hr>' : ''}
        `;
    }
    
    return html;
}

// ========================================
// MAIN SWITCHER FUNCTION
// ========================================

/**
 * Initialize and add the theme switcher to the page
 */
function addSwitcher() {
    // Check if switcher already exists
    if ($("#dzSwitcher").length > 0) {
        return;
    }
    
    // Combine switcher and demo panel HTML
    const switcherHTML = getThemeSwitcherHTML();
    const demoPanelHTML = getDemoPanelHTML();
    
    // Append to body
    jQuery('body').append(switcherHTML + demoPanelHTML);
    
    // Initialize event handlers
    initializeSwitcherEvents();
}

/**
 * Initialize all event handlers for the theme switcher
 */
function initializeSwitcherEvents() {
    // Sidebar toggle events
    $('.sidebar-right-trigger').on('click', function() {
        $('.sidebar-right').toggleClass('show');
    });
    
    $('.sidebar-close-trigger, .bg-overlay').on('click', function() {
        $('.sidebar-right').removeClass('show');
    });
}

// ========================================
// THEME CONTROLLER FUNCTIONS
// ========================================

/**
 * Main theme controller initialization
 */
(function($) {
    "use strict";
    
    // Initialize the switcher
    addSwitcher();
    
    // Cache DOM elements
    const body = $('body');
    const html = $('html');
    
    // Get control elements
    const controls = {
        typography: $('#typography'),
        themeVersion: $('#theme_version'),
        themeLayout: $('#theme_layout'),
        sidebarStyle: $('#sidebar_style'),
        sidebarPosition: $('#sidebar_position'),
        headerPosition: $('#header_position'),
        containerLayout: $('#container_layout'),
        themeDirection: $('#theme_direction')
    };
    
    // ========================================
    // EVENT HANDLERS
    // ========================================
    
    /**
     * Typography change handler
     */
    controls.typography.on('change', function() {
        body.attr('data-typography', this.value);
        setCookie('typography', this.value);
    });
    
    /**
     * Theme version change handler (light/dark)
     */
    controls.themeVersion.on('change', function() {
        body.attr('data-theme-version', this.value);
        setCookie('version', this.value);
    });
    
    /**
     * Theme layout change handler (vertical/horizontal)
     */
    controls.themeLayout.on('change', function() {
        if (body.attr('data-sidebar-style') === 'overlay') {
            body.attr('data-sidebar-style', 'full');
            body.attr('data-layout', this.value);
            return;
        }
        
        body.attr('data-layout', this.value);
        setCookie('layout', this.value);
    });
    
    /**
     * Sidebar style change handler
     */
    controls.sidebarStyle.on('change', function() {
        // Validation checks
        if (body.attr('data-layout') === "horizontal" && this.value === "overlay") {
            alert("Sorry! Overlay is not possible in Horizontal layout.");
            return;
        }
        
        if (body.attr('data-layout') === "vertical") {
            if (body.attr('data-container') === "boxed" && this.value === "full") {
                alert("Sorry! Full menu is not available in Vertical Boxed layout.");
                return;
            }
            
            if (this.value === "modern" && body.attr('data-sidebar-position') === "fixed") {
                alert("Sorry! Modern sidebar layout is not available in the fixed position. Please change the sidebar position into Static.");
                return;
            }
        }
        
        body.attr('data-sidebar-style', this.value);
        
        // Handle icon-hover functionality
        if (body.attr('data-sidebar-style') === 'icon-hover') {
            $('.deznav').on('hover', function() {
                $('#main-wrapper').addClass('iconhover-toggle');
            }, function() {
                $('#main-wrapper').removeClass('iconhover-toggle');
            });
        }
        
        setCookie('sidebarStyle', this.value);
    });
    
    /**
     * Sidebar position change handler
     */
    controls.sidebarPosition.on('change', function() {
        if (this.value === "fixed" && 
            body.attr('data-sidebar-style') === "modern" && 
            body.attr('data-layout') === "vertical") {
            alert("Sorry, Modern sidebar layout doesn't support fixed position!");
            return;
        }
        
        body.attr('data-sidebar-position', this.value);
        setCookie('sidebarPosition', this.value);
    });
    
    /**
     * Header position change handler
     */
    controls.headerPosition.on('change', function() {
        body.attr('data-header-position', this.value);
        setCookie('headerPosition', this.value);
    });
    
    /**
     * Container layout change handler
     */
    controls.containerLayout.on('change', function() {
        if (this.value === "boxed") {
            if (body.attr('data-layout') === "horizontal" && body.attr('data-sidebar-style') === "full") {
                body.attr('data-sidebar-style', 'overlay');
                body.attr('data-container', this.value);
                
                setTimeout(function() {
                    $(window).trigger('resize');
                }, 200);
                
                return;
            }
        }
        
        body.attr('data-container', this.value);
        setCookie('containerLayout', this.value);
    });
    
    /**
     * Theme direction change handler (RTL/LTR)
     */
    controls.themeDirection.on('change', function() {
        html.attr('dir', this.value);
        html.attr('class', '');
        html.addClass(this.value);
        body.attr('direction', this.value);
        setCookie('direction', this.value);
    });
    
    // ========================================
    // COLOR CHANGE HANDLERS
    // ========================================
    
    /**
     * Navigation header background change
     */
    $('input[name="navigation_header"]').on('click', function() {
        body.attr('data-nav-headerbg', this.value);
        setCookie('navheaderBg', this.value);
    });
    
    /**
     * Header background change
     */
    $('input[name="header_bg"]').on('click', function() {
        body.attr('data-headerbg', this.value);
        setCookie('headerBg', this.value);
    });
    
    /**
     * Sidebar background change
     */
    $('input[name="sidebar_bg"]').on('click', function() {
        body.attr('data-sibebarbg', this.value);
        setCookie('sidebarBg', this.value);
    });
    
    /**
     * Primary color change
     */
    $('input[name="primary_bg"]').on('click', function() {
        body.attr('data-primary', this.value);
        setCookie('primary', this.value);
    });
    
})(jQuery);

// ========================================
// UTILITY FUNCTIONS
// ========================================

/**
 * Set a single unified color for all theme elements
 * @param {string} colorValue - Color value (e.g., 'color_2')
 */
function setUnifiedColor(colorValue) {
    const body = $('body');
    
    // Apply color to all elements
    body.attr('data-primary', colorValue);
    body.attr('data-nav-headerbg', colorValue);
    body.attr('data-headerbg', colorValue);
    body.attr('data-sibebarbg', colorValue);
    
    // Check corresponding radio buttons
    $(`input[name="primary_bg"][value="${colorValue}"]`).prop('checked', true);
    $(`input[name="navigation_header"][value="${colorValue}"]`).prop('checked', true);
    $(`input[name="header_bg"][value="${colorValue}"]`).prop('checked', true);
    $(`input[name="sidebar_bg"][value="${colorValue}"]`).prop('checked', true);
    
    // Save to cookies
    setCookie('primary', colorValue);
    setCookie('navheaderBg', colorValue);
    setCookie('headerBg', colorValue);
    setCookie('sidebarBg', colorValue);
}

/**
 * Initialize theme with default blue settings to match dashboard
 */
function initializeThemeDefaults() {
    // Set default unified color to blue (matching your dashboard)
    const defaultColor = 'color_2'; // This should match your blue theme
    
    $(document).ready(function() {
        setTimeout(function() {
            setUnifiedColor(defaultColor);
            
            // Also set light theme by default
            $('body').attr('data-theme-version', 'light');
            $('#theme_version').val('light');
            setCookie('version', 'light');
        }, 100);
    });
}

// Auto-apply blue theme on page load to match your dashboard
initializeThemeDefaults();