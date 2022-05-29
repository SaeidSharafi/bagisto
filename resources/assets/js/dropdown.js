import Vue from "vue";

$(function() {
    const event = new Event('outSideNavClick');
    $(document).click(function(e) {
        var target = e.target;

        if (!$(target).parents('.vpd-wrapper').length) {
            if (!$(target).parents('.dropdown-open').length || $(target).is('li') || $(target).is('a')) {
                $('.dropdown-list').hide();
                $('.dropdown-toggle').removeClass('active');
            }
        }
        if ($(target).is("#modal-blocker") || $(target).is("#search-blocker")) {

            document.dispatchEvent(event);
        }
    });

    $('body').delegate('.dropdown-toggle', 'click', function(e) {
        e.stopImmediatePropagation();
        console.log('.dropdown-toggle');
        toggleDropdown(e);
    });

    function toggleDropdown(e) {
        var currentElement = $(e.currentTarget);

        $('.dropdown-list').hide();

        if (currentElement.hasClass('active')) {
            currentElement.removeClass('active');
        } else {
            currentElement.addClass('active');
            currentElement.parent().find('.dropdown-list').fadeIn(100);
            currentElement.parent().addClass('dropdown-open');

            autoDropupDropdown();
        }
    }

    function autoDropupDropdown() {
        let dropdown = $(".dropdown-open");

        if (! dropdown.find('.dropdown-list').hasClass('top-left') && ! dropdown.find('.dropdown-list').hasClass('top-right') && dropdown.length) {
            dropdown = dropdown.find('.dropdown-list');
            let height = dropdown.height() + 50;
            var topOffset = dropdown.offset().top - 70;
            var bottomOffset = $(window).height() - topOffset - dropdown.height();

            if (bottomOffset > topOffset || height < bottomOffset) {
                dropdown.removeClass("bottom");

                if(dropdown.hasClass('top-right')) {
                    dropdown.removeClass('top-right')
                    dropdown.addClass('bottom-right')
                } else if(dropdown.hasClass('top-left')) {
                    dropdown.removeClass('top-left')
                    dropdown.addClass('bottom-left')
                }
            } else {
                if(dropdown.hasClass('bottom-right')) {
                    dropdown.removeClass('bottom-right')
                    dropdown.addClass('top-right')
                } else if(dropdown.hasClass('bottom-left')) {
                    dropdown.removeClass('bottom-left')
                    dropdown.addClass('top-left')
                }
            }
        }
    }

    $('div').scroll(function() {
        autoDropupDropdown()
    });
});