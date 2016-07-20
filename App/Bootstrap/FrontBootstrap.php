<?php

namespace LeadpagesWP\Bootstrap;

use Leadpages\Pages\LeadpagesPages;
use LeadpagesWP\Admin\CustomPostTypes\LeadpagesPostType;
use LeadpagesWP\Admin\Factories\CustomPostType;
use LeadpagesWP\Front\Controllers\LeadboxController;
use LeadpagesWP\Front\Controllers\LeadpageController;
use LeadpagesWP\ServiceProviders\WordPressLeadpagesAuth;

class FrontBootstrap
{

    /**
     * @var \LeadpagesWP\Bootstrap\WordPressLeadpagesAuth
     */
    private $login;
    /**
     * @var \LeadpagesWP\Front\Controllers\LeadpageController
     */
    private $leadpageController;
    /**
     * @var \Leadpages\Pages\LeadpagesPages
     */
    private $pagesApi;
    /**
     * @var \LeadpagesWP\Front\Controllers\LeadboxController
     */
    private $leadboxController;

    /**
     * FrontBootstrap constructor.
     *
     * @param \LeadpagesWP\ServiceProviders\WordPressLeadpagesAuth $login
     * @param \LeadpagesWP\Front\Controllers\LeadpageController $leadpageController
     * @param \Leadpages\Pages\LeadpagesPages $pagesApi
     * @param \LeadpagesWP\Front\Controllers\LeadboxController $leadboxController
     */
    public function __construct(
      WordPressLeadpagesAuth $login,
      LeadpageController $leadpageController,
      LeadpagesPages $pagesApi,
      LeadboxController $leadboxController
    )
    {
        $this->login              = $login;
        $this->pagesApi           = $pagesApi;
        $this->leadpageController = $leadpageController;
        $this->leadboxController  = $leadboxController;


        if(!$this->login->isLoggedIn()){
            return;
        }
        $this->setupLeadpages();
        add_filter('post_type_link', array(&$this, 'leadpages_permalink'), 99, 2);
        add_filter('the_posts', array($this, 'displayLeadpage'), 1);
        add_filter('the_posts', array($this->leadpageController, 'displayWelcomeGate'));
        add_action('template_redirect', array($this->leadpageController, 'displayNFPage'));
        add_action('wp', array($this->leadpageController, 'isFrontPage'));
        add_action('wp', array($this, 'displayLeadboxes'));


    }

    public function setupLeadpages()
    {
        CustomPostType::create(LeadpagesPostType::getName());

    }

    public function displayLeadpage($posts)
    {
        if (!empty($posts)) {
            if ($posts[0]->post_type != 'leadpages_post') {
                return $posts;
            }
        }

        $result = $this->leadpageController->normalPage();
        if ($result == false) {
            return $posts;
        }
    }

    public function displayLeadboxes()
    {
        if(!is_404()){
            add_action('get_footer', array($this->leadboxController, 'initLeadboxes'));
        }else{
            add_action('get_footer', array($this->leadboxController, 'initLeadboxes404'));
        }
    }


    public function leadpages_permalink($url, $post)
    {
        if ('leadpages_post' == get_post_type($post)) {
            $path = esc_html(get_post_meta($post->ID, 'leadpages_slug', true));
            if ($path != '') {
                return site_url() . '/' . $path;
            } else {
                return '';
            }
        }

        return $url;
    }

}