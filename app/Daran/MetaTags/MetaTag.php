<?php

namespace App\Daran\MetaTags;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use App\Daran\Models\Settings\Seo;
use App\Daran\Models\Settings\Branding;
use App\Daran\Models\Settings\Contact;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MetaTag
{
    /**
     * Instance of request
     *
     * @var \Illuminate\Http\Request
     */
    private $request;

    /**
     * @var array
     */
    private $config = [];

    /**
     * Locale default for app.
     *
     * @var string
     */
    private $defaultLocale = '';

    /**
     * @var array
     */
    private $metas = [];

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $canonical;

    /**
     * @var array
     */
    private $alternates = [];

    /**
     * OpenGraph elements
     *
     * @var array
     */
    private $og = [
        'og_title', 'og_description', 'type', 'image', 'url', 'audio',
        'determiner', 'locale', 'site_name', 'video'
    ];

    /**
     * Twitter card elements
     *
     * @var array
     */
    private $twitter = [
        'card', 'site', 'title', 'description',
        'creator', 'image:src', 'domain'
    ];

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  array $config
     * @param  string $defaultLocale
     */
    public function __construct(Request $request, array $config = [], $defaultLocale)
    {
        $this->request = $request;
        $this->config = $config;

        // Set defaults
        // $this->set('title', $this->config['title']);
        // $this->set('url', $this->request->url());
        $seo_default = Seo::where('type','=','default')->first();
        $seo_def = Branding::where('lang','=',LaravelLocalization::getCurrentLocale())->first(['site_title','site_description','logo','site_name']);
        if($seo_def){
            $this->set('title', $seo_def->site_title);
            $this->set('description', $seo_def->site_description);
            $this->set('site_name', $seo_def->site_name);
            $this->set('image', $seo_def->logo);
            $this->set('url', $this->request->url());
            if($this->request->fullUrl() != $this->request->url()){
                $this->set('canonical',$this->request->url());
            }
        }else{
            $this->set('title', config('app.name'));
            $this->set('description', '');
            $this->set('site_name', config('app.name'));
            $this->set('image', '');
            $this->set('url', config('app.url'));
            if($this->request->fullUrl() != $this->request->url()){
                $this->set('canonical',$this->request->url());
            }
        }

        // Set default locale
        $this->defaultLocale = config('app.locale');

        // Is locales a callback
        if (is_callable(config('app.available_translations'))) {
            $this->setLocales(call_user_func(config('app.available_translations')));
        }

        if ($seo_default && $seo_default->gtag_manager) {
            $gtag_manager = $seo_default->gtag_manager;
            MetaTag::set('gtag_manager', $gtag_manager);
        }

        if ($seo_default && $seo_default->facebook_pixel) {
            $facebook_pixel = $seo_default->facebook_pixel;
            MetaTag::set('facebook_pixel', $facebook_pixel);
        }

        if ($seo_default && $seo_default->g_analytics) {
            $g_analytics = $seo_default->g_analytics;
            MetaTag::set('g_analytics', $g_analytics);
        }

        $this->set('robots', 'all');
    }

    /**
     * @param  string $key
     * @param  string $default
     * @return string
     */
    public function get($key, $default = null)
    {
        return Arr::get($this->metas, $key, $default);
    }

    /**
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function set($key, $value = null)
    {
        $method = 'set'.$key;

        if (method_exists($this, $method)) {
            return $this->$method($value);
        }

        $value = $this->fix($value);



        return $this->metas[$key] = self::cut($value, $key);
    }

    /**
     * Create a tag based on the given key
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    public function tag($key, $value = '')
    {
        return $this->createTag([
            'name' => $key,
            'content' => $value ?: Arr::get($this->metas, $key, ''),
        ]);
    }

    /**
     * Create canonical tags
     *
     * @return string
     */
    public function canonical()
    {
        if(!$this->canonical){
            return '';
        }

        $html = $this->createLinkTag([
            'rel' => 'canonical',
            'href' => $this->canonical
        ]);

        foreach ($this->alternates as $key=>$value)
        {
            $html .= $this->createLinkTag([
                'rel' => 'alternate',
                'hreflang' => $key,
                'href' => $value
            ]);
        }

        return $html;
    }

    /**
     * Create open graph tags
     *
     * @return string
     */
    public function openGraph()
    {
        $html = [
            'url' => $this->createTag([
                'property' => 'og:url',
                'content' => $this->request->url()
            ])
        ];

        foreach ($this->og as $tag)
        {
            // Get value for tag, default to dynamically set value
            $value = Arr::get($this->config['open_graph'], $tag, $this->get($tag));

            $tag = str_replace('og_','',$tag);
            if ($value) {
                $html[$tag] = $this->createTag([
                    'property' => "og:{$tag}",
                    'content' => $value
                ]);
            }
        }

        return implode('', $html);
    }

    /**
     * Create twitter card tags
     *
     * @return string
     */
    public function twitterCard()
    {
        $html = [];

        foreach ($this->twitter as $tag)
        {
            // Get value for tag, default to dynamically set value
            $value = Arr::get($this->config['twitter'], $tag, $this->get($tag));

            if ($value && !isset($html[$tag])) {
                $html[$tag] = $this->createTag([
                    'property' => "twitter:{$tag}",
                    'content' => $value
                ]);
            }
        }

        // Set image
        if (empty($html['image:src']) && $this->get('image')) {
            $html['image:src'] = $this->createTag([
                'property' => "twitter:image:src",
                'content' => $this->get('image')
            ]);
        }

        // Set domain
        if (empty($html['domain'])) {
            $html['domain'] = $this->createTag([
                'property' => "twitter:domain",
                'content' => $this->request->getHttpHost()
            ]);
        }

        return implode('', $html);
    }

    /**
     * @param  string $value
     * @return string
     */
    private function setTitle($value)
    {
        $title = $this->title;

        if ($title && $this->config['title_limit']) {
            $title = ' - '.$title;
            $limit = $this->config['title_limit'] - strlen($title);
        }
        else {
            $limit = 'title';
        }

        return $this->metas['title'] = self::cut($value, $limit).$title;
    }

    /**
     * @param  string $value
     * @return string
     */
    private function setCanonical($value)
    {
         $this->canonical = $value;
    }

    /**
     * @param  array $values
     * @return array
     */
    private function setAlternate($values)
    {
        // foreach($values as $lang) {
        //     $this->alternates[$lang] = LaravelLocalization::getLocalizedURL($lang,$this->canonical);
        // }
    }

    /**
     * Create meta tag from attributes
     *
     * @param  array $values
     * @return string
     */
    private function createTag(array $values)
    {
        $attributes = array_map(function($key) use ($values) {
            $value = $this->fix($values[$key]);
            return "{$key}=\"{$value}\"";
        }, array_keys($values));

        $attributes = implode(' ', $attributes);

        return "<meta {$attributes}>\n    ";
    }

    private function createLinkTag(array $values)
    {
        $attributes = array_map(function($key) use ($values) {
            $value = $this->fix($values[$key]);
            return "{$key}=\"{$value}\"";
        }, array_keys($values));

        $attributes = implode(' ', $attributes);

        return "<link {$attributes}>\n    ";
    }

    /**
     * @param  string $text
     * @return string
     */
    private function fix($text)
    {
        $text = preg_replace('/<[^>]+>/', ' ', $text);
        $text = preg_replace('/[\r\n\s]+/', ' ', $text);

        return trim(str_replace('"', '&quot;', $text));
    }

    /**
     * @param  string $text
     * @param  string $key
     * @return string
     */
    private function cut($text, $key)
    {
        if (is_string($key) && isset($this->config[$key.'_limit'])) {
            $limit = $this->config[$key.'_limit'];
        }
        else if (is_integer($key)) {
            $limit = $key;
        }
        else {
            return $text;
        }

        $length = strlen($text);

        if ($length <= (int) $limit) {
            return $text;
        }

        $text = substr($text, 0, ($limit -= 3));

        if ($space = strrpos($text, ' ')) {
            $text = substr($text, 0, $space);
        }

        return $text.'...';
    }

    /**
     * Returns an URL adapted to locale
     *
     * @param  string $locale
     * @return string
     */
    private function localizedURL($locale)
    {
        // Default language doesn't get a special subdomain
        $locale = ($locale !== $this->defaultLocale) ? strtolower($locale).'.' : '';

        // URL elements
        $uri = $this->request->getRequestUri();
        $scheme = $this->request->getScheme();

        // Get host
        $array = explode('.', $this->request->getHttpHost());
        $host = (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : '').'.'.$array[count($array) - 1];

        // Create URL from template
        $url = str_replace(
            ['[scheme]', '[locale]', '[host]', '[uri]'],
            [$scheme, $locale, $host, $uri],
            $this->config['locale_url']
        );

        return url($url);
    }

    /**
     * Creates all the tags
     *
     * @param  Model $locale
     */
     public function createFromModel(Model $model)
     {
         $meta_title = $model->meta_title;
         $meta_description = $model->meta_description;
         $og_title = $model->meta_title;
         $og_description = $model->meta_description;

         $seo_cat = Seo::where('type','=',$model->getTable())->first();
         $seo_def = Branding::where('lang','=',LaravelLocalization::getCurrentLocale())->first(['site_title','site_description','logo','site_name']);
         if($seo_def){
             $this->set('title', $seo_def->site_title);
             $this->set('description', $seo_def->site_description);
             $this->set('site_name', $seo_def->site_name);
             $this->set('image', config('app.url').$seo_def->logo);
             $this->set('url', $this->request->url());
             if($this->request->fullUrl() != $this->request->url()){
                 $this->set('canonical',$this->request->url());
             }
         }else{
             $this->set('title', config('app.name'));
             $this->set('description', '');
             $this->set('site_name', config('app.name'));
             $this->set('image', '');
             $this->set('url', config('app.url'));
             if($this->request->fullUrl() != $this->request->url()){
                 $this->set('canonical',$this->request->url());
             }
         }
         // take default seo info
         $seo_default = Seo::where('type','=','default')->first();

         if (!$meta_title) {
             if ($seo_cat && $seo_cat->title) {
                 $meta_title = $seo_cat->title;
                 $lastPos = 0;
                 $i = 0;
                 $positions = array();

                 while (($lastPos = strpos($meta_title, '%', $lastPos)) !== false) {
                     $positions[] = $lastPos;
                     $lastPos = $lastPos + 1;
                 }

                 while ($i<count($positions)) {
                     $key = substr($meta_title,$positions[$i],$positions[$i+1]+1);
                     $field = substr($key,1,strlen($key)-2);
                     $value = $model->$field;
                     $meta_title = str_replace($key,$value,$meta_title);
                     $i+=2;
                 }

             }
         }

         if (!$meta_description) {
             if ($seo_cat && $seo_cat->description) {
                 $meta_description = $seo_cat->description;
                 $lastPos = 0;
                 $i = 0;
                 $positions = array();

                 while (($lastPos = strpos($meta_description, '%', $lastPos)) !== false) {
                     $positions[] = $lastPos;
                     $lastPos = $lastPos + 1;
                 }

                 while ($i<count($positions)) {
                     $key = substr($meta_description,$positions[$i],$positions[$i+1]+1);
                     $field = substr($key,1,strlen($key)-2);
                     $value = $model->$field;
                     $meta_description = str_replace($key,$value,$meta_description);
                     $i+=2;
                 }

             }
         }

         if (!$og_title) {
             if ($seo_cat && $seo_cat->og_title) {
                 $og_title = $seo_cat->og_title;
                 $lastPos = 0;
                 $i = 0;
                 $positions = array();

                 while (($lastPos = strpos($og_title, '%', $lastPos)) !== false) {
                     $positions[] = $lastPos;
                     $lastPos = $lastPos + 1;
                 }

                 while ($i<count($positions)) {
                     $key = substr($og_title,$positions[$i],$positions[$i+1]+1);
                     $field = substr($key,1,strlen($key)-2);
                     $value = $model->$field;
                     $og_title = str_replace($key,$value,$og_title);
                     $i+=2;
                 }

             }
         }

         if (!$og_description) {
             if ($seo_cat && $seo_cat->og_description) {
                 $og_description = $seo_cat->og_description;
                 $lastPos = 0;
                 $i = 0;
                 $positions = array();

                 while (($lastPos = strpos($og_description, '%', $lastPos)) !== false) {
                     $positions[] = $lastPos;
                     $lastPos = $lastPos + 1;
                 }

                 while ($i<count($positions)) {
                     $key = substr($og_description,$positions[$i],$positions[$i+1]+1);
                     $field = substr($key,1,strlen($key)-2);
                     $value = $model->$field;
                     $og_description = str_replace($key,$value,$og_description);
                     $i+=2;
                 }

             }
         }

         if ($seo_cat && $seo_cat->og_author) {
             $author = $seo_cat->og_author;
             $lastPos = 0;
             $i = 0;
             $positions = array();

             while (($lastPos = strpos($author, '%', $lastPos)) !== false) {
                 $positions[] = $lastPos;
                 $lastPos = $lastPos + 1;
             }

             while ($i<count($positions)) {
                 $key = substr($author,$positions[$i],$positions[$i+1]+1);
                 $field = substr($key,1,strlen($key)-2);

                 if($field == 'user') {
                     $value = $model->admin ? $model->user->full_name : '';
                 }else{
                     $value = $model->$field;
                 }
                 $author = str_replace($key,$value,$author);
                 $i+=2;
             }
         }
         MetaTag::set('title', $meta_title);
         MetaTag::set('description', $meta_description);
         MetaTag::set('og_title', $og_title);
         MetaTag::set('og_description', $og_description);
         MetaTag::set('type',($seo_cat && $seo_cat->og_type ? $seo_cat->og_type : 'website'));
         MetaTag::set('locale',LaravelLocalization::getCurrentLocale());
         if($seo_cat && $seo_cat->og_image){
            MetaTag::set('image', $model->image);
         }

         if($model->translations){
            MetaTag::set('alternate',$model->translations->pluck('locale')->all());
         }
         MetaTag::set('author', $author);

         if ($model->seo) {
             MetaTag::set('robots', 'all');
         } else {
             MetaTag::set('robots', 'noindex, nofollow');
         }
     }

     /**
      * Creates all the tags
      *
      * @param  HasMeta $locale
      */
      public function createFromMetable(HasMeta $model)
      {
          $meta_title = $model->getMetaTitle();
          $meta_description = $model->getMetaDescription();
          $og_title = $model->getOgTitle();
          $og_description = $model->getOgDescription();
          $og_image = $model->getOgImage();

          if($model->getTranslations()){
             MetaTag::set('alternate',$model->getTranslations());
          }

          $seo_cat = Seo::where('type','=',$model->getTable())->first();

          $seo_def = Branding::where('lang','=',LaravelLocalization::getCurrentLocale())->first(['site_title','site_description','logo','site_name']);
          if($seo_def){
              $this->set('title', $seo_def->site_title);
              $this->set('description', $seo_def->site_description);
              $this->set('site_name', $seo_def->site_name);
              $this->set('image', $seo_def->logo);
              $this->set('url', $this->request->url());
              if($this->request->fullUrl() != $this->request->url()){
                  $this->set('canonical',$this->request->url());
              }
          }else{
              $this->set('title', config('app.name'));
              $this->set('description', '');
              $this->set('site_name', config('app.name'));
              $this->set('image', '');
              $this->set('url', config('app.url'));
              if($this->request->fullUrl() != $this->request->url()){
                  $this->set('canonical',$this->request->url());
              }
          }
          // take default seo info
          $seo_default = Seo::where('type','=','default')->first();

          if ($meta_title) {
              MetaTag::set('title', $meta_title);
          }

          if ($meta_description) {
              MetaTag::set('description', $meta_description);
          }

          if ($og_title) {
              MetaTag::set('og_title', $og_title);
          }

          if($og_image) {
              MetaTag::set('image', $og_image);
          }

          if ($og_description) {
              MetaTag::set('og_description', $og_description);
          }

          if($model->isSeoIndexable()){
              MetaTag::set('robots', 'all');
          } else {
              MetaTag::set('robots', 'noindex, nofollow');
          }

          MetaTag::set('type', 'website');
          MetaTag::set('locale',LaravelLocalization::getCurrentLocale());
      }
}
