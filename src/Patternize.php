<?php

namespace Salmanbe\Patternize;

use Illuminate\Support\Str;
use Config;
use File;

trait Patternize {

    /**
     * Configuration file
     * @var array
     */
    private $config;

    /**
     * Table config pattern
     * @var array
     */
    private $pattern;

    /**
     * Table name
     * @var string
     */
    private $table;

    /**
     * All Fields
     * @var array
     */
    private $attributes;

    /**
     * Field name
     * @var string
     */
    private $attribute;

    /**
     * Field value
     * @var string
     */
    private $value;

    /**
     * Function patternizes a string
     * @param object $model
     * @param string $action
     * @return object $model
     */
    protected function patternize($model, $action) {

        if (!Config::get('patternize')) {
            return $model;
        }

        $this->config = Config::get('patternize');
        
        if(!isset($this->config[$model->getTable()][$action])){
            return;
        }
        
        $this->pattern = $this->config[$model->getTable()][$action];

        $this->attributes = $model->getAttributes();

        foreach ($this->attributes as $attribute => $value) {

            if (!isset($this->pattern[$attribute])) {
                continue;
            }

            $this->attribute = $attribute;
            $this->value = $value;

            $this->setIfNull();
            $this->setDefault();
            $this->authUserId();
            $this->trim();
            $this->singleSpace();
            $this->stripHtml();
            $this->lower();
            $this->upper();
            $this->title();
            $this->ucfirst();
            $this->alphaNum();
            $this->slugify();
            $this->leftTrim();
            $this->rightTrim();
            $this->replace();
            $this->startWith();
            $this->endWith();

            $model->{$this->attribute} = $this->value ? $this->value : null;
        }

        if (isset($this->pattern['functions'])) {

            foreach ($this->pattern['functions'] as $function) {
                call_user_func($function, $model, $action);
            }
        }

        if (isset($this->pattern['delete'])) {

            foreach ($this->pattern['delete'] as $field => $paths) {

                foreach ($paths as $path) {
                    File::delete($path . $model->{$field});
                }
            }
        }

        return $model;
    }

    /**
     * Function trims a string
     * @return void
     */
    private function trim() {

        if ($this->config['trim'] || $this->hasKey($this->attribute, 'trim')) {
            $this->value = Str::of($this->value)->trim();
        }
    }

    /**
     * Function sets default value from other field if null
     * @return void
     */
    private function setIfNull() {

        if (!$this->value && $this->hasKey('set_if_null')) {
            $this->value = $this->attributes[$this->hasKey('set_if_null')];
        }
    }

    /**
     * Function sets default value if null
     * @return void
     */
    private function setDefault() {

        if (!$this->value && $this->hasKey('default')) {
            $this->value = $this->hasKey('default');
        }
    }

    /**
     * Function sets default value if null
     * @return void
     */
    private function authUserId() {

        if ($this->hasKey('auth_user_id')) {
            $this->value = auth()->id();
        }
    }

    /**
     * Function keeps one space between words
     * @return void
     */
    private function singleSpace() {

        if ($this->config['single_space'] || $this->hasKey('single_space')) {
            $this->value = preg_replace('!\s+!', ' ', $this->value);
        }
    }

    /**
     * Function removes html tags from string
     * @return void
     */
    private function stripHtml() {
        
        if($this->hasKey('allow_html')){
            return;
        }

        if ($this->config['strip_html'] || $this->hasKey('strip_html')) {
            $this->value = strip_tags($this->value);
        }
    }

    /**
     * Function converts string to lower case
     * @return void
     */
    private function lower() {

        if ($this->hasKey('lower')) {
            $this->value = Str::lower($this->value);
        }
    }

    /**
     * Function converts string to upper case
     * @return void
     */
    private function upper() {

        if ($this->hasKey('upper')) {
            $this->value = Str::upper($this->value);
        }
    }

    /**
     * Function converts string to upper case
     * @return void
     */
    private function ucfirst() {

        if ($this->hasKey('ucfirst')) {
            $this->value = ucfirst($this->value);
        }
    }

    /**
     * Function converts string to title case
     * @return void
     */
    private function title() {

        if ($this->hasKey('title')) {
            $this->value = Str::title($this->value);
        }
    }

    /**
     * Function converts string alpha numeric with space
     * @return void
     */
    private function alphaNum() {

        if ($this->hasKey('alpha_num')) {
            $this->value = preg_replace('/[^0-9a-zA-Z ]/m', '', $this->value);
        }
    }

    /**
     * Function slugify a string
     * @return void
     */
    private function slugify() {

        if ($this->hasKey('slugify')) {
            $this->value = Str::slug($this->value);
        }
    }

    /**
     * Function trims from left
     * @return void
     */
    private function leftTrim() {

        if ($this->hasKey('left_trim')) {
            $this->value = ltrim($this->value, $this->hasKey('left_trim'));
        }
    }

    /**
     * Function trims from right
     * @return void
     */
    private function rightTrim() {

        if ($this->hasKey('right_trim')) {
            $this->value = rtrim($this->value, $this->hasKey('right_trim'));
        }
    }

    /**
     * Function replaces a string
     * @return void
     */
    private function replace() {

        if ($this->hasKey('replace')) {

            foreach ($this->hasKey('replace') as $key => $value) {
                $this->value = str_replace($key, $value, $this->value);
            }
        }
    }

    /**
     * Function add string at start
     * @return void
     */
    private function startWith() {

        if ($this->hasKey('start_with') && !Str::startsWith($this->value, $this->hasKey('start_with'))) {
            $this->value = $this->hasKey('start_with') . $this->value;
        }
    }

    /**
     * Function add string at end
     * @return void
     */
    private function endWith() {

        if ($this->hasKey('end_with') && !Str::startsWith($this->value, $this->hasKey('end_with'))) {
            $this->value .= $this->hasKey('end_with');
        }
    }

    /**
     * Function checks key in global configuration
     * @param string $option
     * @return string;
     */
    private function hasKey($option) {
        return isset($this->pattern[$this->attribute][$option]) ? $this->pattern[$this->attribute][$option] : null;
    }

}
