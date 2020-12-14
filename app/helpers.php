<?php

if (! function_exists('markdown')) {
    function markdown($text = null)
    {
        return app(Parsedown::class)->text($text);
    }
}
if (! function_exists('gravatar_url')) {
    function gravatar_url($email, $size = 48)
    {
        return sprintf('//www.gravatar.com/avatar/%s?s=%s', md5($email), $size);
    }
}

if (! function_exists('gravatar_profile_url')) {
    function gravatar_profile_url($email)
    {
        return sprintf('//www.gravatar.com/%s', md5($email));
    }
}

if (! function_exists('attachments_path')) {
    function attachments_path($path = '')
    {
        return public_path('files' . ($path ? DIRECTORY_SEPARATOR.$path : $path));
    }
}

if (! function_exists('format_filesize')) {
    /**
     * Calculate human-readable file size string.
     *
     * @param $bytes
     * @return string
     */
    function format_filesize($bytes)
    {
        if (! is_numeric($bytes)) return 'NaN';

        $decr = 1024;
        $step = 0;
        $suffix = ['bytes', 'KB', 'MB'];

        while (($bytes / $decr) > 0.9) {
            $bytes = $bytes / $decr;
            $step ++;
        }

        return round($bytes, 2) . $suffix[$step];
    }
}

if (! function_exists('link_for_sort')) {
    function link_for_sort($colum, $text, $params = [])
    {
        $direction = request()->input('order');
        $reverse = ($direction == 'asc') ? 'desc' : 'asc';

        if (request()->input('sort') == $colum) {
            $text = sprintf("%s %s",
                $direction == 'asc'
                    ? '<i class="fa fa-sort-alpha-asc"></i>'
                    : '<i class="fa fa-sort-alpha-desc"></i>',
                $text
            );
        }

        $queryString = http_build_query(array_merge(
            request()->except(['sort', 'order']),
            ['sort' => $colum, 'order' => $reverse],
            $params
        ));

        return sprintf(
            '<a href="%s?%s">%s</a>',
            urldecode(request()->url()),
            $queryString,
            $text
        );
    }
}