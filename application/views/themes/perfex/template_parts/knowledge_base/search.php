<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="jumbotron kb-search-jumbotron">
    <div class="kb-search">
        <div class="container">
            <div class="collapse navbar-collapse" id="theme-navbar-collapse">
                            <ul class="nav navbar-nav navbar-right">
                                <li class="customers-nav-item-knowledge-base active">
                                     <a href="<?php echo site_url('knowledge-base'); ?>">
                            <i class="fa-regular fa-eye"></i> Company Policies</a>
                                </li>
                                <li>
                                    <a href="<?php echo site_url('admin'); ?>">
                                    <i class="fa-regular fa-arrow-left"></i> Back  </a>
                             </li>
                            </ul>
                        </div>
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="text-center">
                        
                        <h2 class="mbot30 kb-search-heading tw-font-semibold">
                            <?php echo _l('kb_search_articles'); ?>
                        </h2>
                        <?php echo form_open(site_url('knowledge-base/search'), ['method' => 'GET', 'id' => 'kb-search-form']); ?>
                        <div class="form-group has-feedback has-feedback-left">
                            <div class="input-group">
                                <input type="search" name="q" placeholder="<?php echo _l('have_a_question'); ?>"
                                    class="form-control kb-search-input" value="<?php echo $this->input->get('q'); ?>">
                                <span class="input-group-btn">
                                    <button type="submit"
                                        class="btn btn-success kb-search-button"><?php echo _l('kb_search'); ?></button>
                                </span>
                                <i class="fa-solid fa-magnifying-glass form-control-feedback kb-search-icon"></i>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>