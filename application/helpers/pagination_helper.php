<?php
if (!function_exists('page_offset')) {
    function page_offset($page = 0, $per_page=0)
    {
        $perPage = isset($per_page) && $per_page != "" && $per_page > 0 ?  $per_page : PER_PAGE;

        if ($page == 0) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $perPage;
        }
        if ($offset < 0) {
            $offset = 0;
        }

        return $offset;
    }
}
if (!function_exists('pagination_config')) {
    function pagination_config($page_data = array())
    {
        $ci = &get_instance();
        $ci->load->library('pagination');

        $config['base_url'] = $page_data['url'];
        $config['total_rows'] = isset($page_data['total_rows']) ? $page_data['total_rows'] : 0;
        $per_page = isset($page_data['per_page']) ? $page_data['per_page'] : PER_PAGE;
        $config['per_page'] = $per_page;
        if (count($_GET) > 0) {
            $config['suffix'] = '?' . http_build_query($_GET, '', "&");
        }
        $config['num_links'] = 2;
        $config['uri_segment'] = isset($page_data['uri_segment']) ? $page_data['uri_segment'] : 3;
        $config['use_page_numbers'] = true;
        $config['full_tag_open'] = '<div class="pagination-custom">';
        $config['full_tag_close'] = '</div>';
        $config['first_link'] = 'FIRST';
        $config['first_tag_open'] = '&nbsp;<span title="Go to first page">';
        $config['first_tag_close'] = '</span>';
        $config['last_link'] = 'LAST';
        $config['last_tag_open'] = '&nbsp;<span title="Go to last page">';
        $config['last_tag_close'] = '</span>';
        $config['next_link'] = 'NEXT';
        $config['next_tag_open'] = '&nbsp;<span title="Go to next page">';
        $config['next_tag_close'] = '</span>&nbsp;';
        $config['prev_link'] = 'PREVIOUS';
        $config['prev_tag_open'] = '&nbsp;<span title="Go to previous page">';
        $config['prev_tag_close'] = '</span>&nbsp;';
        $config['cur_tag_open'] = '&nbsp;<span class="current-page" title="Current page">';
        $config['cur_tag_close'] = '</span>&nbsp;';
        $config['first_url'] = $config['base_url'] . '?' . http_build_query($_GET);

        $ci->pagination->initialize($config);
    }
}
