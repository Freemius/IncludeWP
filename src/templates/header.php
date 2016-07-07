<div id="header" class="navbar-fixed">
    <nav>
        <ul id="sharing" class="dropdown-content">
            <li><a class="twitter" target="_blank"
                   href="https://twitter.com/intent/tweet?text=<?php echo urlencode('IncludeWP is a leaderboard of the top #frameworks for #WordPress #plugin & #theme devs. Code reusability is awesome!') ?>&url=<?php echo SITE_ADDRESS ?>">Tweet
                    <i class="fa fa-fw fa-twitter"></i></a></li>

            <li><a class="facebook" href="https://www.facebook.com/sharer.php?u=<?php echo SITE_ADDRESS ?>">Share <i
                        class="fa fa-fw fa-facebook"></i></a></li>

            <li><a class="google-plus" href="https://plus.google.com/share?url=<?php echo SITE_ADDRESS ?>">Share <i
                        class="fa fa-fw fa-google-plus"></i></a></li>

            <li class="divider"></li>
            <li><a class="whatsapp"
                   href="whatsapp://send?text=<?php echo urlencode('Hey, check out IncludeWP - ' . SITE_ADDRESS) ?>">Send
                    <i class="fa fa-fw fa-whatsapp"></i></a></li>
            <li><a class="email"
                   href="mailto:?subject=<?php echo rawurlencode('Top Frameworks for WordPress plugin & theme developers') ?>&body=<?php echo rawurlencode("Hey, check out IncludeWP:\n" . SITE_ADDRESS) ?>">Email
                    <i
                        class="fa fa-fw fa-envelope"></i></a></li>
        </ul>

        <div class="nav-wrapper container">
            <!--            <a href="/" class="brand-logo logo medium-center small-right"><h1 class="logo"><i class="fa fa-terminal"></i> IncludeWP</h1></a>-->
            <ul class="left">
                <li><a href="https://github.com/Freemius/IncludeWP/fork" target="_blank"><i
                            class="fa fa-github left"></i> Fork me on GitHub</a>
                <li><a class="dropdown-button" href="#!" data-activates="sharing" data-constrainwidth="false"><i
                            class="fa fa-share left"></i> Share</a></li>
            </ul>

            <ul class="right">
                <li><a href="https://github.com/Freemius/IncludeWP/"><i
                            class="fa fa-info left"></i> About</a></li>
                <li><a href="https://github.com/Freemius/IncludeWP/"><i
                            class="fa fa-plus left"></i> Add framework</a></li>
            </ul>
        </div>
    </nav>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var header = $('.navbar-fixed'),
            headerHeight = header.outerHeight();

        var $filters = $('#filters'),
            $filtersPlaceholder = $filters.find('.placeholder'),
            $filtersBody = $filters.find('> .section'),
            filtersTop = $filters.offset().top,
            $win = $(window),
            winScrollTop,
            stickyFilters;

//        console.log('navbar height', headerHeight);

        $win.scroll(function () {
            winScrollTop = $(window).scrollTop();

            header.toggleClass('bevel', (winScrollTop > 0));

            stickyFilters = (winScrollTop > filtersTop - headerHeight);

            if (stickyFilters) {
                $filtersPlaceholder.height($filters.height());
                $filtersBody.css('top', headerHeight + 'px');
            }

            $filters.toggleClass('sticky', stickyFilters);
        });
    });
</script>