<?php
/**
 * Navigation helper functions
 */

/**
 * Returns language select menu for requested nav_item key
 * 
 * @param string $nav_item key for navigation items 'nav_key' - 'home' for default home pages (in all languages)
 * @param arrray $items array of items for language menu list item (text/image etc), key is language signature:
 *   i.e.: array('pl' => 'Polski', 'en' => 'English') or 
 *         array('pl' => '<img src="{polish flag image url}" alt="Po Polsku">',
 *               'en' => '<img src="{english flag image url}" alt="In English">') ,
 *   if empty, array returned by fuel_settings('fuel', 'languages') function is used.
 * 
 * @return array of items for building menu with fuel_nav() function
 */
function fuel_lang_items($nav_key = 'home', $items = array())
{
    $CI = &get_instance();
    $ni = fuel_nav_items($nav_key, NULL, 1/* 'main' */, 'default');
    $li = empty($items) ? fuel_settings('fuel', 'languages') : $items; // language items
    $a  = array();
    foreach ($ni as $i)
    {
        $a[$i->location] = array(
            'label' => $li[$i->language],
            'active' => $i->location,
        );
    }
    return $a;
}

/**
 * Returns navigation item(s) in FuelCMS navigation data selected by nav_key (language, nav_group_id - additional selection)
 * (Fuel_navigation_model method shortener) (as fuel navigation library doesn't have what I need or is doing it slowly..)
 * Returned items are sorted in theirs 'precedence' order
 * 
 * @param string $nav_key navigation item key
 * @param string $lang navigation item(s) language string
 * @param int/string $group_id id of navigation group (default:1 ('main' group))
 * @param string $return_as result format: 'default'/'array'/'nav_items',  (see return formats)
 * @return (dependably on $return_as argument:
 *   'default'(or any other string): array of objects - nav item(s) matched input arguments 'default', or
 *   'array': array of item(s) matched input arguments 'array', or
 *   'nav_items': array of navigation items in format to build navigation menu with fuel_nav() function - 'items' array value, or
 *   empty array if requested item(s) are not found in FUEL CMS navigation data, or 
 *   NULL if argument('group_id') is incorrect.
 */
function fuel_nav_items($nav_key = NULL, $lang = NULL, $group_id = 1, $return_as = 'default')
{
    $CI = &get_instance();
    $db = $CI->db;

    // change group name (string) to group id (int) if needed
    if (is_string($group_id))
    {
        $navgt    = $CI->config->config['fuel']['tables']['fuel_navigation_groups'];
        $ntg_id   = $CI->config->config['fuel']['table_cols']['fuel_navigation_groups']['id'];
        $ntg_name = $CI->config->config['fuel']['table_cols']['fuel_navigation_groups']['name'];
        $q        = $db->where(array($ntg_name => $group_id))->get($navgt);
        $r        = $q->row();
        if (empty($r))
        {
            return NULL; // group of name $group_id not found
        } else
        {
            $group_id = $r->$ntg_id;
        }
    }

    // set 'where' conditions
    if (!empty($group_id))
    {
        $nt_group = $CI->config->config['fuel']['table_cols']['fuel_navigation']['group_id'];
        $db->where(array($nt_group => (int) $group_id));
    }
    if (!empty($nav_key))
    {
        $nt_key = $CI->config->config['fuel']['table_cols']['fuel_navigation']['nav_key'];
        $db->where(array($nt_key => $nav_key));
    }
    if (!empty($lang))
    {
        $nt_lang = $CI->config->config['fuel']['table_cols']['fuel_navigation']['language'];
        $db->where(array($nt_lang => $lang));
    }

    // return result
    $navt    = $CI->config->config['fuel']['tables']['fuel_navigation'];
    $nt_prec = $CI->config->config['fuel']['table_cols']['fuel_navigation']['precedence'];
    $db->order_by($nt_prec, 'ASC'); // sort by precedence

    if ($return_as == 'array')
    {
        return $db->get($navt)->result_array();
    } else
    if ($return_as == 'nav_items')
    {
        $n      = array();
        $nt_loc = $CI->config->config['fuel']['table_cols']['fuel_navigation']['location'];
        $nt_lab = $CI->config->config['fuel']['table_cols']['fuel_navigation']['label'];
        //
        foreach ($db->get($navt)->result() as $row)
        {
            $n[$row->location] = array(
                'label'  => $row->$nt_lab,
                'active' => $row->$nt_loc, //'active' => $row->$nt_loc . '$|' . $row->$nt_loc . '/:any',
            );
        }
        return $n;
    } else
    {
        return $db->get($navt)->result();
    }
}

/**
 * Redirects to page with correct language uri, if detected uri for page is incorrect - page doesn't (shouldn't) have variables for that language.
 * Uses 301 redirect if necessary, to redirect page to uri with correct language.
 * (for now, only for 'segment' language mode - appends correct language segment to cleaned page uri) 
 * @param array $page page information array, returned by fuel_page->properties() method
 *      (for speed, as it should be already retrieved for the page in controller or view)
 *      (contains basically record of current page from 'fuel_pages' table in array format)
 * @param string $language currently detected language (already detected value, for speed, otherwise use detect_language() method as argument)
 */
function page_language_redirect($page, $language)
{
    $CI       = &get_instance();
    $pt_langs = $CI->config->config['fuel']['table_cols']['fuel_pages']['langs'];
    $langs    = array_map('trim', explode(',', $page[$pt_langs])); // convert page allowed languages to array (and trim any eventual whitespace in languages identificators)
    if (!in_array($language, $langs))
    {
        //return $CI->fuel->language->default_option(); / debug
        log_message('info', 'Incorrect(laguage) uri was requested "' . uri_string() . '". Redirecting to correct one.');
        if (count($langs) >= 1) // preffered language is first one in the page's languages list
        {
            //$CI->fuel->language->redirect_to_lang($langs[0]); makes 302 redirect wich is incorrect as it should point to (permamently) correct uri, (incorrect(language) uri shouldn't be used)
            $uri = (!is_home()) ? $CI->fuel->language->cleaned_uri() : ''; // part of redirect procedure taken from redirect_to_lang() method
            //$uri = $CI->fuel->language->uri($uri, $langs[0]); // langs[0] is used in redirect()
            //return $uri; //  debug
            redirect($uri, 'location', 301, NULL, $langs[0]);
        } else
        { // ??
        }
    }
}
