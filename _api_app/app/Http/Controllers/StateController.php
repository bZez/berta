<?php

namespace App\Http\Controllers;

use App\Sites;
use App\SiteSettings;
use App\TemplateSettings;
use App\Sections;
use App\Entries;
use App\Tags;

class StateController extends Controller
{
    public function get($site) {
        $sites = new Sites();
        $siteSettings = new SiteSettings();
        $templateSettings = new TemplateSettings();

        $state = $sites->get();
        $state['site_settings'] = array();
        $state['sections'] = array();
        $state['entries'] = array();
        $state['tags'] = array();

        foreach($state['site'] as $_site) {
            $site_name = $_site['name'] ? $_site['name'] : 0;
            $sections = new Sections($site_name);
            $state['site_settings'][$site_name] = $siteSettings->getSettingsBySite($site_name);
            $state['sections'][$site_name] = $sections->get();

            if (!empty($state['sections'][$site_name])) {
                foreach($state['sections'][$site_name]['section'] as $section) {
                    $section_name = $section['name'];
                    $entries = new Entries($site_name, $section_name);
                    $state['entries'][$site_name][$section_name] = $entries->get();
                    unset($entries);
                }
            }

            $tags = new Tags($site_name);
            $state['tags'][$site_name] = $tags->get();
            unset($sections);
            unset($tags);
        }

        $lang = $state['site_settings'][$site]['language']['language'];
        $state['template_settings'] = $templateSettings->get($lang);
        unset($sites);

        return response()->json($state);
    }
}
