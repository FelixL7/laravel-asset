<?php

namespace FelixL7\Cdn;

use FelixL7\Cdn\Exceptions\MissingCdnException;
use FelixL7\Cdn\Exceptions\MissingResourceVersionException;
use FelixL7\Cdn\Interfaces\ICdn;

abstract class AbstractLib
{
    protected $min = false;
    protected $loadingAttribute = null;
    protected $version = null;
    protected $cache = true;
    protected $cacheConfigVersion = false;
    protected $cdn = null;
    protected $has = [];


    /**
     * ##############################################################################
     * ########################     RESOURCES    ####################################
     * ##############################################################################
     */

    /**
     * Gibt die Einbindung der CSS Ressource zurück
     */
    public function css() : string {
        if($this->hasCss()) {
            return '<link href="'.$this->cssUrl().'" rel="stylesheet">';
        }

        return '';
    }

    /**
     * Gibt die Einbindung der JS Ressource zurück
     */
    public function js() : string {
        if($this->hasCss()) {
            return '<script src="'.$this->jsUrl().'"'.$this->withLoadingAttribute().'></script>';
        }

        return '';
    }


    /**
     * ##############################################################################
     * ########################     SETTINGS    #####################################
     * ##############################################################################
     */

    /**
     * Führt dazu, dass minifizierte Ressourcen zurückgegeben werden
     */
    public function min() {
        $this->setMin(true);

        return $this;
    }

    /**
     * Führt dazu, dass nicht minifizierte Ressourcen zurückgegeben werden
     */
    public function readable() {
        $this->setMin(false);

        return $this;
    }

    /**
     * Führt dazu, dass Ressourcen in der gegebenen Version zurückgegeben werden
     */
    public function version(string $version) {
        //TODO: Validation
        $this->version = $version;

        return $this;
    }

    /**
     * Führt dazu, dass die Ressourcen in der von der Konfig gegebenen Version zurückgegeben werden
     */
    public function configVersion() {
        $version = config('laravel-cdn.libs.'.$this->getLibName().'.version');

        if(is_null($version)) {
            throw new MissingResourceVersionException('Missing resource version for resource '.$this->getLibName());
        }

        $this->version = $version;

        return $this;
    }

    /**
     * Load Resource asynchronous
     */
    public function async() {
        $this->loadingAttribute = 'async';

        return $this;
    }

    /**
     * Load Resource defer
     */
    public function defer() {
        $this->loadingAttribute = 'defer';

        return $this;
    }

    /**
     * Disable caching of the Resource
     */
    public function disableCache() {
        $this->cache = false;

        return $this;
    }

    /**
     * Disable caching partially with config
     */
    public function disableCacheWithConfigVersion() {
        $this->cache = false;
        $this->cacheConfigVersion = true;

        return $this;
    }

    /**
     * Enable caching
     */
    public function enableCache() {
        $this->cache = true;
        $this->cacheConfigVersion = false;

        return $this;
    }

    public function cdn(string $cdn) {
        $this->cdn = $cdn;

        return $this;
    }

    public function configCdn() {
        $cdn = $this->configFromLibOrGlobal('cdn');

        if(is_null($cdn) || !in_array(ICdn::class, class_implements($cdn))) {
            throw new MissingCdnException('Missing CDN for resource '.$this->getLibName());
        }

        $this->cdn = $cdn;

        return $this;
    }

    /**
     * TODO
     */
    public function integrity(string $integrity) {

    }

    /**
     * TODO
     */
    public function crossorigin(string $crossorigin) {

    }


    /**
     * ##############################################################################
     * ########################     CONFIGURATION    ################################
     * ##############################################################################
     */

    /**
     * Gibt den Bibliotheksnamen zurück
     *
     * @return string
     */
    public function getLibName() : string {
        return $this->libName;
    }

    /**
     *
     * @return bool
     */
    public function hasCss() : bool {
        if(isset($this->has['css'])) {
            return $this->has['css'];
        }

        return false;
    }

    /**
     *
     * @return bool
     */
    public function hasJs() : bool {
        if(isset($this->has['js'])) {
            return $this->has['js'];
        }

        return false;
    }


    /**
     * ##############################################################################
     * ########################     AGGREGATES    ###################################
     * ##############################################################################
     */

    /**
     * Gibt die URL der CSS Ressource zurück
     */
    public function cssUrl() : string {
        if($this->hasCss()) {
            $url = $this->getCssFilePathWithoutExtension();

            if($this->getMin()) {
                $url .= '.min';
            }

            $url .= '.css';

            if(!$this->getCache()) {
                $url = $this->withNoCache($url);
            }

            return $url;
        }

        return '';
    }

    /**
     * Gibt die URL der JS Ressource zurück
     */
    public function jsUrl() : string {
        if($this->hasJs()) {
            $url = $this->getJsFilePathWithoutExtension();

            if($this->getMin()) {
                $url .= '.min';
            }

            $url .= '.js';

            if(!$this->getCache()) {
                $url = $this->withNoCache($url);
            }

            return $url;
        }

        return '';
    }

    /**
     *
     * @return string
     */
    protected function getJsFilePathWithoutExtension() : string {
        $cdn = $this->getCdnInstance();

        return $cdn->getFilePathWithoutExtension($this->cdnJsFilePathAfterVersion());
    }

    /**
     *
     * @return string
     */
    protected function getCssFilePathWithoutExtension() : string {
        $cdn = $this->getCdnInstance();

        return $cdn->getFilePathWithoutExtension($this->cdnCssFilePathAfterVersion());
    }

    /**
     * z.B. defer, async
     *
     * @return string|null
     */
    protected function withLoadingAttribute() {
        $loadingAttribute = $this->getLoadingAttribute();

        if(!is_null($loadingAttribute)) {
            return ' '.$loadingAttribute;
        }

        return null;
    }

    /**
     *
     * @return string
     */
    protected function withNoCache(string $url) : string {
        if(!$this->getCache()) {
            if($this->getCacheConfigVersion()) {
                return $url.'?v='.$this->configFromLibOrGlobal('cache_version', '1');
            } else {
                return $url.'?v='.time();
            }
        }

        return $url;
    }

    /**
     *
     * @return string|null
     */
    protected function configFromLibOrGlobal(string $attribute, $default = null) {
        return config('laravel-cdn.libs.'.$this->getLibName().'.'.$attribute, config('laravel-cdn.'.$attribute, $default));
    }

    /**
     *
     * @return ICdn
     */
    protected function getCdnInstance() : ICdn {
        $cdnClass = $this->getCdn();

        return new $cdnClass($this->getLibNamePathSegment(), $this->getVersion(), $this->getJsFileName());
    }

    /**
     *
     * @return string
     */
    protected function getLibNamePathSegment() : string {
        return $this->getLibName();
    }

    /**
     *
     * @return string
     */
    public function getName() : string {
        return $this->getLibName();
    }

    /**
     *
     * @return string
     */
    protected function getCssFileName() : string {
        return $this->getLibName();
    }

    /**
     *
     * @return string
     */
    protected function getJsFileName() : string {
        return $this->getLibName();
    }

    /**
     *
     * @return string
     */
    protected function cdnCssFilePathAfterVersion() : string {
        return "/";
    }

    /**
     *
     * @return string
     */
    protected function cdnJsFilePathAfterVersion() : string {
        return "/";
    }


    /**
     * ##########################################################
     * #############    GETTER&SETTER   #########################
     * ##########################################################
     */

    /**
     * Get the value of min
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set the value of min
     *
     * @return  self
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get the value of loadingAttribute
     */
    public function getLoadingAttribute()
    {
        return $this->loadingAttribute;
    }

    /**
     * Set the value of loadingAttribute
     *
     * @return  self
     */
    public function setLoadingAttribute($loadingAttribute)
    {
        $this->loadingAttribute = $loadingAttribute;

        return $this;
    }

    /**
     * Get the value of version
     */
    public function getVersion()
    {
        if(is_null($this->version)) {
            $this->configVersion();
        }

        return $this->version;
    }

    /**
     * Set the value of version
     *
     * @return  self
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get the value of cache
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * Set the value of cache
     *
     * @return  self
     */
    public function setCache($cache)
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * Get the value of cacheConfigVersion
     */
    public function getCacheConfigVersion()
    {
        return $this->cacheConfigVersion;
    }

    /**
     * Set the value of cacheConfigVersion
     *
     * @return  self
     */
    public function setCacheConfigVersion($cacheConfigVersion)
    {
        $this->cacheConfigVersion = $cacheConfigVersion;

        return $this;
    }

    /**
     * Get the value of cdn
     */
    public function getCdn()
    {
        if(is_null($this->cdn)) {
            $this->configCdn();
        }

        return $this->cdn;
    }

    /**
     * Set the value of cdn
     *
     * @return  self
     */
    public function setCdn($cdn)
    {
        $this->cdn = $cdn;

        return $this;
    }
}
