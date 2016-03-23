<?php

namespace Leadpages\Bootstrap;

use TheLoop\Contracts\HttpClient;
use Leadpages\Admin\Factories\Metaboxes;
use Leadpages\Admin\Providers\AdminAuth;
use Leadpages\Admin\Factories\SettingsPage;
use Leadpages\admin\MetaBoxes\LeadpageSelect;
use Leadpages\Admin\Factories\CustomPostType;
use Leadpages\Admin\Providers\LeadpagesLoginApi;
use Leadpages\admin\SettingsPages\LeadpagesLoginPage;
use Leadpages\Admin\CustomPostTypes\LeadpagesPostType;
use Leadpages\admin\MetaBoxes\LeadpageTypeMetaBox;
use TheLoop\ServiceContainer\ServiceContainerTrait;

class AdminBootstrap
{
    use ServiceContainerTrait;
    /**
     * @var \TheLoop\Contracts\HttpClient
     */
    private $httpClient;
    /**
     * @var \Leadpages\Admin\Providers\LeadpagesLoginApi
     */
    private $leadpagesLoginApi;
    /**
     * @var \Leadpages\Admin\Providers\AdminAuth
     */
    private $auth;

    public function __construct(HttpClient $httpClient, LeadpagesLoginApi $leadpagesLoginApi, AdminAuth $auth)
    {

        $this->httpClient = $httpClient;
        $this->leadpagesLoginApi = $leadpagesLoginApi;
        $this->auth = $auth;
        $this->initAdmin();
    }

    public function initAdmin()
    {

        /**
         * set http time out to 20 seconds as sometimes our request take
         * a couple seconds longer than the default 5 seconds
         */
        apply_filters('http_request_timeout', 20);

        $this->auth->login();

        if(!$this->auth->isLoggedIn()){
            SettingsPage::create(LeadpagesLoginPage::class);
        }else{
            CustomPostType::create(LeadpagesPostType::class);
            Metaboxes::create(LeadpageTypeMetaBox::class);
            Metaboxes::create(LeadpageSelect::class);
        }
    }

    //todo this needs refactored as it probably shouldn't be here plus hard
    //depdency on Model
    protected function saveLeadPage(){

        $LeadpagesModel = new LeadPagesPostTypeModel($this->ioc['pagesApi']);
        $LeadpagesModel->saveMeta();

    }
}