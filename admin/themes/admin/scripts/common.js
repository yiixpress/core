/* Utility functions */
/*left sidebar menu*/


// scroll to element animation
function scrollTo(id) {
    if ($(id).length)
        $('html,body').animate({scrollTop: $(id).offset().top}, 'slow');
}

// handle menu toggle button action
function toggleMenuHidden() {
    //console.log('toggleMenuHidden');
    $('.container-fluid:first').toggleClass('menu-hidden');
    $('#menu').toggleClass('hidden-phone', function () {
        if ($('.container-fluid:first').is('.menu-hidden')) {
            if (typeof resetResizableMenu != 'undefined')
                resetResizableMenu(true);
        }
        else {
            removeMenuHiddenPhone();

            if (typeof lastResizableMenuPosition != 'undefined')
                lastResizableMenuPosition();
        }

        if (typeof $.cookie != 'undefined')
            $.cookie('menuHidden', $('.container-fluid:first').is('.menu-hidden'));
    });

    if (typeof masonryGallery != 'undefined')
        masonryGallery();
}

function removeMenuHiddenPhone() {
    if (!$('.container-fluid:first').is('.menu-hidden') && $('#menu').is('.hidden-phone'))
        $('#menu').removeClass('hidden-phone');
}

// handle generate sparkline charts
function genSparklines() {
    if ($('.sparkline').length) {
        $.each($('#content .sparkline'), function (k, v) {
            var size = { w: 150, h: 28 };
            if ($(this).parent().is('.widget-stats'))
                size = { w: 150, h: 35 }

            var color = primaryColor;
            if ($(this).is('.danger')) color = dangerColor;
            if ($(this).is('.success')) color = successColor;
            if ($(this).is('.warning')) color = warningColor;
            if ($(this).is('.inverse')) color = inverseColor;

            var data = [
                [1, 3 + randNum()],
                [2, 5 + randNum()],
                [3, 8 + randNum()],
                [4, 11 + randNum()],
                [5, 14 + randNum()],
                [6, 17 + randNum()],
                [7, 20 + randNum()],
                [8, 15 + randNum()],
                [9, 18 + randNum()],
                [10, 22 + randNum()]
            ];
            $(v).sparkline(data,
                {
                    type: 'bar',
                    width: size.w,
                    height: size.h,
                    stackedBarColor: ["#dadada", color],
                    lineWidth: 2
                });
        });
        $.each($('#menu .sparkline'), function (k, v) {
            var size = { w: 150, h: 20 };
            if ($(this).parent().is('.widget-stats-3'))
                size = { w: 150, h: 35 }

            var color = primaryColor;
            if ($(this).is('.danger')) color = dangerColor;
            if ($(this).is('.success')) color = successColor;
            if ($(this).is('.warning')) color = warningColor;
            if ($(this).is('.inverse')) color = inverseColor;

            var data = [
                [1, 3 + randNum()],
                [2, 5 + randNum()],
                [3, 8 + randNum()],
                [4, 11 + randNum()],
                [5, 14 + randNum()],
                [6, 17 + randNum()],
                [7, 20 + randNum()],
                [8, 15 + randNum()],
                [9, 18 + randNum()],
                [10, 22 + randNum()]
            ];
            $(v).sparkline(data,
                {
                    type: 'bar',
                    width: size.w,
                    height: size.h,
                    stackedBarColor: ["#dadada", color],
                    lineWidth: 2
                });
        });
    }
}

/*
 * Helper function for JQueryUI Sliders Create event
 */
function JQSliderCreate() {
    $(this)
        .removeClass('ui-corner-all ui-widget-content')
        .wrap('<span class="ui-slider-wrap"></span>')
        .find('.ui-slider-handle')
        .removeClass('ui-corner-all ui-state-default');
}

$(function () {
	$("#menu > .inner > ul > li > a").toggle(function() {
		$("#menu .sub-menu").addClass("hide");
		$(this).parent().addClass("active");
		$(this).parent().find(".sub-menu").removeClass("hide");
	},function() {
		$(this).parent().removeClass("active");
		$(this).parent().find(".sub-menu").addClass("hide");
	});
	
	
    // Sidebar menu collapsibles
    $('#menu .collapse').on('show', function (e) {
        e.stopPropagation();
        $(this).parents('.hasSubmenu:first').addClass('active');
    })
        .on('hidden', function (e) {
            e.stopPropagation();
            $(this).parents('.hasSubmenu:first').removeClass('active');
        });

    // main menu visibility toggle
    $('.navbar.main .btn-navbar').click(function () {
        var disabled = typeof toggleMenuButtonWhileTourOpen != 'undefined' ? toggleMenuButtonWhileTourOpen(true) : false;
        if (!disabled)
            toggleMenuHidden();
    });

    // multi-level top menu
    $('.submenu').hover(function () {
        $(this).children('ul').removeClass('submenu-hide').addClass('submenu-show');
    }, function () {
        $(this).children('ul').removeClass('.submenu-show').addClass('submenu-hide');
    })

    //.find("a:first").append(" &raquo; ");

    // tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // popovers
    $('[data-toggle="popover"]').popover();

    // print
    $('[data-toggle="print"]').click(function (e) {
        e.preventDefault();
        window.print();
    });

    // collapsible widgets
    $('.widget[data-toggle="collapse-widget"] .widget-body')
        .on('show', function () {
            $(this).parents('.widget:first').attr('data-collapse-closed', "false");
        })
        .on('hidden', function () {
            $(this).parents('.widget:first').attr('data-collapse-closed', "true");
        });

    $('.widget[data-toggle="collapse-widget"]').each(function () {
        // append toggle button
        $(this).find('.widget-head').append('<span class="collapse-toggle"></span>');

        // make the widget body collapsible
        $(this).find('.widget-body').addClass('collapse');

        // verify if the widget should be opened
        if ($(this).attr('data-collapse-closed') !== "true")
            $(this).find('.widget-body').addClass('in');

        // bind the toggle button
        $(this).find('.collapse-toggle').on('click', function () {
            $(this).parents('.widget:first').find('.widget-body').collapse('toggle');
        });
    });

    // generate sparkline charts
    genSparklines();

    // Google Code Prettify
    if ($('.prettyprint').length)
        prettyPrint();

    // bind window resize event
    $(window).resize(function () {
        /*
         if (!$('#menu').is(':visible') && !$('.container-fluid:first').is('.menu-hidden') && !$('.container-fluid:first').is('.documentation') && !$('.container-fluid:first').is('.login'))
         {
         $('.container-fluid:first').addClass('menu-hidden');

         if (Modernizr.touch)
         return;

         resetResizableMenu(true);
         }
         else
         {
         if (Modernizr.touch)
         return;

         if (!$('#menu').is(':visible') && parseInt($(document).width()) > 767 && !$('.container-fluid:first').is('.menu-hidden'))
         {
         $('.container-fluid:first').removeClass('menu-hidden');
         lastResizableMenuPosition();
         }
         }
         */
    });

    // trigger window resize event
    $(window).resize();

    // view source toggle buttons
    $('.btn-source-toggle').click(function (e) {
        e.preventDefault();
        $('.code:not(.show)').toggleClass('hide');
    });

    // show/hide toggle buttons
    $('[data-toggle="hide"]').click(function () {
        $($(this).attr('data-target')).toggleClass('hide');
        if ($(this).is('.scrollTarget') && !$($(this).attr('data-target')).is('.hide'))
            scrollTo($(this).attr('data-target'));
    });

    // handle menu position change
    $('#toggle-menu-position').on('change', function () {
        $('.container-fluid:first').toggleClass('menu-right');

        if ($(this).prop('checked'))
            $('.container-fluid:first').removeClass('menu-left');
        else
            $('.container-fluid:first').addClass('menu-left');

        if (typeof $.cookie != 'undefined')
            $.cookie('rightMenu', $(this).prop('checked') ? $(this).prop('checked') : null);

        if (typeof resetResizableMenu != 'undefined' && typeof lastResizableMenuPosition != 'undefined') {
            resetResizableMenu(true);
            lastResizableMenuPosition();
        }
        removeMenuHiddenPhone();
    });

    // handle persistent menu position on page load
    if (typeof $.cookie != 'undefined' && $.cookie('rightMenu') && $('#toggle-menu-position').length) {
        $('#toggle-menu-position').prop('checked', true);
        $('.container-fluid:first').not('.menu-right').removeClass('menu-left').addClass('menu-right');
    }

    // handle layout type change
    $('#toggle-layout').on('change', function () {
        if ($(this).prop('checked')) {
            $('.container-fluid:first').addClass('fixed');
        }
        else
            $('.container-fluid:first').removeClass('fixed');

        if (typeof $.cookie != 'undefined')
            $.cookie('layoutFixed', $(this).prop('checked') ? $(this).prop('checked') : null);
    });

    // handle persistent layout type on page load
    if (typeof $.cookie != 'undefined' && $.cookie('layoutFixed') && $('#toggle-layout').length) {
        $('#toggle-layout').prop('checked', true);
        $('.container-fluid:first').addClass('fixed');
    }

    // handle persistent menu visibility on page load
    if (typeof $.cookie != 'undefined' && $.cookie('menuHidden') && $.cookie('menuHidden') == 'true' || (!$('.container-fluid').is('.menu-hidden') && !$('#menu').is(':visible')))
        toggleMenuHidden();
    else if ($('#menu').is(':visible')) {
        removeMenuHiddenPhone();

        if (typeof lastResizableMenuPosition != 'undefined')
            lastResizableMenuPosition();
    }


    /* wysihtml5 */
    if ($('textarea.wysihtml5').size() > 0)
        $('textarea.wysihtml5').wysihtml5();

    /*
     * Boostrap Extended
     */
    // custom select for Boostrap using dropdowns
    if ($('.selectpicker').length) $('.selectpicker').selectpicker();

    // bootstrap-toggle-buttons
    if ($('.toggle-button').length) $('.toggle-button').toggleButtons();

    /*
     * UniformJS: Sexy form elements
     */
    if ($('.uniformjs').length) $('.uniformjs').find("select, input, button, textarea").uniform();

    // colorpicker
    if ($('#colorpicker').length) $('#colorpicker').farbtastic('#colorpickerColor');

    // datepicker
    if ($('#datepicker').length) $("#datepicker").datepicker({ showOtherMonths: true });
    if ($('#datepicker-inline').length) $('#datepicker-inline').datepicker({ inline: true, showOtherMonths: true });

    // daterange
    if ($('#dateRangeFrom').length && $('#dateRangeTo').length) {
        $("#dateRangeFrom").datepicker({
            defaultDate: "+1w",
            changeMonth: false,
            numberOfMonths: 2,
            onClose: function (selectedDate) {
                $("#dateRangeTo").datepicker("option", "minDate", selectedDate);
            }
        }).datepicker("option", "maxDate", $('#dateRangeTo').val());

        $("#dateRangeTo").datepicker({
            defaultDate: "+1w",
            changeMonth: false,
            numberOfMonths: 2,
            onClose: function (selectedDate) {
                $("#dateRangeFrom").datepicker("option", "maxDate", selectedDate);
            }
        }).datepicker("option", "minDate", $('#dateRangeFrom').val());
    }

    /* Table select / checkboxes utility */
    $('.checkboxs thead :checkbox').change(function () {
        if ($(this).is(':checked')) {
            $('.checkboxs tbody :checkbox').prop('checked', true).parent().addClass('checked');
            $('.checkboxs tbody tr.selectable').addClass('selected');
            $('.checkboxs_actions').show();
        }
        else {
            $('.checkboxs tbody :checkbox').prop('checked', false).parent().removeClass('checked');
            $('.checkboxs tbody tr.selectable').removeClass('selected');
            $('.checkboxs_actions').hide();
        }
    });

    $('.checkboxs tbody').on('click', 'tr.selectable', function (e) {
        var c = $(this).find(':checkbox');
        var s = $(e.srcElement);

        if (e.srcElement.nodeName == 'INPUT') {
            if (c.is(':checked'))
                $(this).addClass('selected');
            else
                $(this).removeClass('selected');
        }
        else if (e.srcElement.nodeName != 'TD' && e.srcElement.nodeName != 'TR' && e.srcElement.nodeName != 'DIV') {
            return true;
        }
        else {
            if (c.is(':checked')) {
                c.prop('checked', false).parent().removeClass('checked');
                $(this).removeClass('selected');
            }
            else {
                c.prop('checked', true).parent().addClass('checked');
                $(this).addClass('selected');
            }
        }
        if ($('.checkboxs tr.selectable :checked').size() == $('.checkboxs tr.selectable :checkbox').size())
            $('.checkboxs thead :checkbox').prop('checked', true).parent().addClass('checked');
        else
            $('.checkboxs thead :checkbox').prop('checked', false).parent().removeClass('checked');

        if ($('.checkboxs tr.selectable :checked').size() >= 1)
            $('.checkboxs_actions').show();
        else
            $('.checkboxs_actions').hide();
    });

    if ($('.checkboxs tbody :checked').size() == $('.checkboxs tbody :checkbox').size() && $('.checkboxs tbody :checked').length)
        $('.checkboxs thead :checkbox').prop('checked', true).parent().addClass('checked');

    if ($('.checkboxs tbody :checked').length)
        $('.checkboxs_actions').show();

    $('.radioboxs tbody tr.selectable').click(function (e) {
        var c = $(this).find(':radio');
        if (e.srcElement.nodeName == 'INPUT') {
            if (c.is(':checked'))
                $(this).addClass('selected');
            else
                $(this).removeClass('selected');
        }
        else if (e.srcElement.nodeName != 'TD' && e.srcElement.nodeName != 'TR') {
            return true;
        }
        else {
            if (c.is(':checked')) {
                c.attr('checked', false);
                $(this).removeClass('selected');
            }
            else {
                c.attr('checked', true);
                $('.radioboxs tbody tr.selectable').removeClass('selected');
                $(this).addClass('selected');
            }
        }
    });

    // sortable tables
    if ($(".js-table-sortable").length) {
        $(".js-table-sortable").sortable(
            {
                placeholder: "ui-state-highlight",
                items: "tbody tr",
                handle: ".js-sortable-handle",
                forcePlaceholderSize: true,
                helper: function (e, ui) {
                    ui.children().each(function () {
                        $(this).width($(this).width());
                    });
                    return ui;
                },
                start: function (event, ui) {
                    if (typeof mainYScroller != 'undefined') mainYScroller.disable();
                    ui.placeholder.html('<td colspan="' + $(this).find('tbody tr:first td').size() + '">&nbsp;</td>');
                },
                stop: function () {
                    if (typeof mainYScroller != 'undefined') mainYScroller.enable();
                }
            });
    }
});