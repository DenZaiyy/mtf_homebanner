/**
 * 2023-2024 MTFibertech
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 */

$(document).ready(function () {
    console.log("MTF HomeBanner admin.js loaded");

    // We'll take a completely different approach using real DOM elements and not cloning
    // This will be more compatible with the rich editors and other complex elements

    // Step 1: Create a tab container above the panels
    var $tabContainer = $('<div class="panel tab-container"></div>');
    var $tabNav = $('<ul class="nav nav-tabs"></ul>');

    // Step 2: Define our tabs
    var tabTitles = [
        "General Settings",
        "Banner 1",
        "Banner 2",
        "Banner 3",
        "Banner 4",
    ];

    // Step 3: Create tab buttons
    $.each(tabTitles, function (index, title) {
        $tabNav.append(
            '<li><a href="#" data-panel-id="fieldset_' +
                index +
                "_" +
                index +
                '">' +
                title +
                "</a></li>"
        );
    });

    // Special case for first tab (General Settings has a different ID format)
    $tabNav.find("li:first a").attr("data-panel-id", "fieldset_0");

    // Step 4: Add the tab navigation to the page
    $tabContainer.append(
        '<div class="panel-heading"><i class="icon-cogs"></i> Home Banner Configuration</div>'
    );
    $tabContainer.append('<div class="panel-body"></div>');
    $tabContainer.find(".panel-body").append($tabNav);

    // Insert before the first panel
    $("#fieldset_0").before($tabContainer);

    // Step 5: Add special classes to panels for styling
    $('.panel[id^="fieldset_"]').addClass("content-panel");

    // Step 6: Hide all panels initially except the first one
    $(".content-panel:not(#fieldset_0)").hide();

    // Step 7: Make the first tab active
    $tabNav.find("li:first").addClass("active");

    // Step 8: Handle tab clicks
    $tabNav.find("a").click(function (e) {
        e.preventDefault();

        // Get the target panel ID
        var targetId = $(this).data("panel-id");

        // Hide all panels
        $(".content-panel").hide();

        // Show the target panel
        $("#" + targetId).show();

        // Update active tab
        $tabNav.find("li").removeClass("active");
        $(this).parent().addClass("active");
    });

    // Toggle columns field based on layout selection
    function toggleColumnsField() {
        var selectedValue = $("#MTF_HOMEBANNER_DISPLAY").val();
        var $columnsField = $(
            'select[name="MTF_HOMEBANNER_DISPLAY_COLUMN"]'
        ).closest(".form-group");

        if (selectedValue === "slider") {
            $columnsField.hide();
        } else {
            $columnsField.show();
        }
    }

    // Initial call
    toggleColumnsField();

    // On change event
    $("#MTF_HOMEBANNER_DISPLAY").change(toggleColumnsField);
});
