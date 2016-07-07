<!-- Filters -->
<div id="filters">
    <div class="placeholder"></div>

    <div class="section">
        <div class="container row valign-wrapper">
            <div class="col s6 valign">
                <input type="checkbox" id="show_plugins" checked="checked" class="filled-in checkbox-blue"/>
                <label for="show_plugins" class="plugin uppercase">Plugin <i class="fa fa-plug plugin"></i></label>

                <input type="checkbox" id="show_themes" checked="checked" class="filled-in checkbox-magenta"/>
                <label for="show_themes" class="theme uppercase">Theme <i class="fa fa-paint-brush theme"></i></label>
            </div>
            <div class="col s6 right-align">
                <!-- Dropdown Trigger -->
                <a id="sortby_trigger" class="dropdown-button btn waves-effect waves-light light-blue" href='#'
                   data-activates='sortby'>Sorted by
                    stars <i class="fa fa-toggle-down"></i></a>
                <!-- Dropdown Structure -->
                <ul id="sortby" class='dropdown-content'>
                    <li><a href="#" data-sort="stars" class="light-blue-text">Sort by stars <i
                                class="fa fa-star"></i></a>
                    </li>
                    <li><a href="#" data-sort="forks" class="light-blue-text">Sort by forks <i
                                class="fa fa-code-fork"></i></a>
                    </li>
                    <li><a href="#" data-sort="issues" class="light-blue-text">Sort by issues <i class="fa fa-bug"></i></a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="#!" data-sort="name" class="light-blue-text">Sort by name <i
                                class="fa fa-sort-alpha-asc"></i></a>
                    </li>
                    <!--            <li><a href="#!">three</a></li>-->
                </ul>
            </div>

            <script type="text/javascript">
                $('#sortby a').on('click', function () {
                    var $cards = $('.card-container'),
                        sortBy = $(this).attr('data-sort');

                    $cards.sort(function (a, b) {
                        var an, bn;

                        if ('name' === sortBy) {
                            an = $(a).find('.card-title').text();
                            bn = $(b).find('.card-title').text();

                            return an.localeCompare(bn);
                        }

                        // By GitHub details.
                        an = parseInt($(a).find('.github-details .' + sortBy + ' span').text());
                        bn = parseInt($(b).find('.github-details .' + sortBy + ' span').text());

                        // Descending sort.
                        if (an > bn)
                            return -1;

                        if (an < bn)
                            return 1;

                        return 0;
                    });

                    $cards.detach().appendTo($('#frameworks > .container'));

                    $('#sortby_trigger').html($(this).html().replace('Sort', 'Sorted'))
                });

                $('#show_plugins').change(function () {
                    if ($(this).is(':checked'))
                        $('.card-container.plugins').fadeIn();
                    else
                        $('.card-container.plugins:not(.themes)').fadeOut();
                });
                $('#show_themes').change(function () {
                    if ($(this).is(':checked'))
                        $('.card-container.themes').fadeIn();
                    else
                        $('.card-container.themes:not(.plugins)').fadeOut();
                });
            </script>
        </div>
    </div>
</div>
<!--/ Filters -->