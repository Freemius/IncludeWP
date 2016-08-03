<?php
    if (empty($framework_slug))
    {
        // GitHub data.
        $sort_by = array(
            'stars'  => 'star',
            'forks'  => 'code-fork',
            'issues' => 'bug',
        );
    }
    else
    {
        // WordPress.org data.
        $sort_by = array(
            'installs'  => 'heartbeat',
            'rate'      => 'star',
            'downloads' => 'download',
        );
    }

    reset($sort_by);
    $default_sort_by = key($sort_by);
?>
<!-- Filters -->
<div id="filters">
    <div class="placeholder"></div>

    <div class="section">
        <div class="container row valign-wrapper">
            <div class="col s6 valign">
                <input type="checkbox" id="show_plugins" checked="checked" class="filled-in checkbox-blue"/>
                <label for="show_plugins"
                       class="plugin uppercase">Plugin<?php echo isset($plugins_count) ? " [{$plugins_count}]" : '' ?>
                    <i class="fa fa-plug plugin"></i></label>

                <input type="checkbox" id="show_themes" checked="checked" class="filled-in checkbox-magenta"/>
                <label for="show_themes"
                       class="theme uppercase">Theme<?php echo isset($themes_count) ? " [{$themes_count}]" : '' ?> <i
                        class="fa fa-paint-brush theme"></i></label>
            </div>
            <div class="col s6 right-align">
                <!-- Dropdown Trigger -->
                <a id="sortby_trigger" class="dropdown-button btn waves-effect waves-light light-blue" href='#'
                   data-activates='sortby'>Sorted by
                    <?php echo $default_sort_by ?> <i class="fa fa-toggle-down"></i></a>
                <!-- Dropdown Structure -->
                <ul id="sortby" class='dropdown-content'>
                    <?php foreach ($sort_by as $metric => $icon) : ?>
                        <li><a href="#!" data-sort="<?php echo $metric ?>" class="light-blue-text">Sort
                                by <?php echo $metric ?> <i
                                    class="fa fa-<?php echo $icon ?>"></i></a>
                        </li>
                    <?php endforeach ?>
                    <li class="divider"></li>
                    <li><a href="#!" data-sort="name" class="light-blue-text">Sort by name <i
                                class="fa fa-sort-alpha-asc"></i></a>
                    </li>
                </ul>
            </div>

            <script type="text/javascript">
                var sortItems = function (sortBy, label) {
                    var $cards = $('.card-container');

                    $cards.sort(function (a, b) {
                        var an, bn;

                        if ('name' === sortBy) {
                            an = $(a).find('.card-title').text();
                            bn = $(b).find('.card-title').text();

                            return an.localeCompare(bn);
                        }

                        // By details.
                        an = parseInt($(a).find('.details .' + sortBy + ' span').text().replace(/,/g, ""));
                        bn = parseInt($(b).find('.details .' + sortBy + ' span').text().replace(/,/g, ""));

                        // Descending sort.
                        return (an > bn) ? -1 : ((an < bn) ? 1 : 0);
                    });

                    $cards.detach().appendTo($('#frameworks > .container'));

                    $('#sortby_trigger').html(label)
                };

                $('#sortby a').on('click', function () {
                    sortItems(
                        $(this).attr('data-sort'),
                        $(this).html().replace('Sort', 'Sorted')
                    );
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