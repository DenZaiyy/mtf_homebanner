{*
* 2023-2024 MTFibertech
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
*
* @author    MTFibertech <contact@mtfibertech.com>
* @copyright 2023-2024 MTFibertech
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
{if $enable}
    <div class="mtf-homebanner-container" id="mtf-homebanner">
        {if $display_type == 'grid'}
            <div class="mtf-homebanner-grid cols-{$display_columns}">
                {foreach from=$banners item=banner}
                    <div class="mtf-banner-item">
                        {if $banner.link}
                            <a href="{$banner.link}" class="mtf-banner-link">
                            {/if}
                            <div class="mtf-banner-image">
                                <img src="{$banner_path}{$banner.image}" alt="{$banner.title|escape:'htmlall':'UTF-8'}"
                                    class="img-fluid" loading="lazy">
                                <div class="mtf-banner-content">
                                    <div>
                                        {if $banner.title}
                                            <h3 class="mtf-banner-title">{$banner.title}</h3>
                                        {/if}
                                        {if $banner.caption}
                                            <div class="mtf-banner-caption">{$banner.caption nofilter}</div>
                                        {/if}
                                    </div>
                                    {if $banner.link}
                                        <div class="mtf-banner-button-container">
                                            <span class="mtf-banner-button">
                                                {if $banner.linkTitle}
                                                    {$banner.linkTitle|escape:'htmlall':'UTF-8'}
                                                {else}
                                                    Voir les produits
                                                {/if}
                                                <span class="mtf-banner-button-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                    {/if}
                                </div>
                            </div>
                            {if $banner.link}
                            </a>
                        {/if}
                    </div>
                {/foreach}
            </div>
        {else}
            <div class="mtf-homebanner-slider" data-columns="{$display_columns}">
                {foreach from=$banners item=banner}
                    <div class="mtf-slider-item">
                        {if $banner.link}
                            <a href="{$banner.link}" class="mtf-banner-link">
                            {/if}
                            <div class="mtf-banner-image">
                                <img src="{$banner_path}{$banner.image}" alt="{$banner.title|escape:'htmlall':'UTF-8'}"
                                    loading="lazy">
                                <div class="mtf-banner-content">
                                    <div>
                                        {if $banner.title}
                                            <h3 class="mtf-banner-title">{$banner.title}</h3>
                                        {/if}
                                        {if $banner.caption}
                                            <div class="mtf-banner-caption">{$banner.caption nofilter}</div>
                                        {/if}
                                    </div>
                                    {if $banner.link}
                                        <div class="mtf-banner-button-container">
                                            <span class="mtf-banner-button">
                                                {if $banner.link_title}
                                                    {$banner.link_title}
                                                {else}
                                                    Voir les produits
                                                {/if}
                                                <span class="mtf-banner-button-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20">
                                                        <path fill="none" stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </span>
                                            </span>
                                        </div>
                                    {/if}
                                </div>
                            </div>
                            {if $banner.link}
                            </a>
                        {/if}
                    </div>
                {/foreach}
            </div>
        {/if}
    </div>
{/if}