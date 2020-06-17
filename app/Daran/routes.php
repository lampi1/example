<?php

Route::get('/home',  ['as'=>'dashboard', 'uses' => 'HomeController@index']);
Route::get('/home/{lang}',  ['as'=>'language', 'uses' => 'HomeController@setLang']);
//CRUD PAGE CATEGORY
Route::resource('page-categories', 'PageCategoryController', ['except' => ['show','delete']]);

//CRUD PAGE
Route::resource('pages', 'PageController', ['except' => ['show','delete']]);
Route::get('/pages/{id}/clone', ['as'=>'pages.clone', 'uses' => 'PageController@clone']);

//CRUD POST CATEGORY
Route::resource('post-categories', 'PostCategoryController', ['except' => ['show','delete']]);

//CRUD POST
Route::resource('posts', 'PostController', ['except' => ['show','delete']]);
Route::get('/posts/{id}/clone', ['as'=>'posts.clone', 'uses' => 'PostController@clone']);
Route::get('/posts/{id}/related', ['as'=>'posts.edit_related', 'uses' => 'PostController@editRelated']);

//CRUD EVENT CATEGORY
Route::resource('event-categories', 'EventCategoryController', ['except' => ['show','delete']]);

//CRUD EVENT
Route::resource('events', 'EventController', ['except' => ['show','delete']]);
Route::get('/events/{id}/clone', ['as'=>'events.clone', 'uses' => 'EventController@clone']);

//CRUD NEWS CATEGORY
Route::get('/news-categories/{category}/edit', ['as'=>'news-categories.edit', 'uses' => 'NewsCategoryController@edit']);
Route::resource('news-categories', 'NewsCategoryController', ['except' => ['show','delete','edit']]);
Route::get('/news-category/{id}/clone', ['as'=>'news-categories.clone', 'uses' => 'NewsCategoryController@clone']);

//CRUD NEWS
Route::resource('news', 'NewsController', ['except' => ['show','delete']]);
Route::get('/news/{id}/clone', ['as'=>'news.clone', 'uses' => 'NewsController@clone']);

//CRUD FAQ CATEGORY
Route::resource('faq-categories', 'FaqCategoryController', ['except' => ['show','delete']]);

//CRUD FAQ
Route::resource('faqs', 'FaqController', ['except' => ['show','delete']]);
Route::get('/faqs/{id}/clone', ['as'=>'faqs.clone', 'uses' => 'FaqController@clone']);


//CRUD SLIDER
Route::resource('sliders', 'SliderController', ['except' => ['show','delete','update']]);
Route::get('/sliders/{id}/clone', ['as'=>'sliders.clone', 'uses' => 'SliderController@clone']);

Route::get('/sliders/{id}/add-slide/{type}', ['as'=>'slides.create', 'uses' => 'SliderController@addSlide']);
Route::post('/sliders/add-slide', ['as'=>'slides.store', 'uses' => 'SliderController@storeSlide']);
Route::get('/slides/{id}/clone', ['as'=>'slides.clone', 'uses' => 'SliderController@cloneSlide']);
Route::get('/slides/{id}/edit', ['as'=>'slides.edit', 'uses' => 'SliderController@editSlide']);
Route::put('/slides/{id}', ['as'=>'slides.update', 'uses' => 'SliderController@updateSlide']);

//CRUD GALLERY CATEGORY
Route::get('/gallery-categories/{category}/edit', ['as'=>'gallery-categories.edit', 'uses' => 'GalleryCategoryController@edit']);
Route::resource('gallery-categories', 'GalleryCategoryController', ['except' => ['show','delete','edit']]);
Route::get('/gallery-category/{id}/clone', ['as'=>'gallery-categories.clone', 'uses' => 'GalleryCategoryController@clone']);


//CRUD GALLERY
Route::resource('galleries', 'GalleryController', ['except' => ['show','delete']]);
Route::get('/galleries/{id}/clone', ['as'=>'galleries.clone', 'uses' => 'GalleryController@clone']);

Route::get('/gallery-media/{id}/add-media/{type}', ['as'=>'medias.create', 'uses' => 'GalleryController@addMedia']);
Route::post('/gallery-media/add-media', ['as'=>'medias.store', 'uses' => 'GalleryController@storeMedia']);
Route::get('/gallery-media/{id}/clone', ['as'=>'medias.clone', 'uses' => 'GalleryController@cloneMedia']);
Route::get('/gallery-media/{id}/edit', ['as'=>'medias.edit', 'uses' => 'GalleryController@editMedia']);
Route::put('/gallery-media/{id}', ['as'=>'medias.update', 'uses' => 'GalleryController@updateMedia']);

//CRUD FORM
Route::resource('forms', 'FormController', ['except' => ['show','delete']]);
Route::get('/forms/{id}/clone', ['as'=>'forms.clone', 'uses' => 'FormController@clone']);

//CRUD LANDING PAGE
Route::resource('landing-pages', 'LandingPageController', ['except' => ['show','delete']]);
Route::get('/landing-pages/{id}/clone', ['as'=>'landing-pages.clone', 'uses' => 'LandingPageController@clone']);

//CRUD MENU
Route::resource('menus', 'MenuBuilderController', ['only' => ['index','edit','update']]);

//SETTINGS
Route::get('/settings/edit', ['as'=>'settings.edit', 'uses' => 'SettingsController@editGeneralSetting']);
Route::put('/settings/update/{branding_id}/{seo_id}', ['as'=>'settings.update', 'uses' => 'SettingsController@updateGeneralSetting']);

//SETTINGS CONTACTS
Route::get('/contact-settings/edit', ['as'=>'contact-settings.edit', 'uses' => 'SettingsController@editContact']);
Route::put('/contact-settings/{id}', ['as'=>'contact-settings.update', 'uses' => 'SettingsController@updateContact']);

//SETTINGS E-COMMERCE
Route::get('/ecommerce-settings/edit', ['as'=>'ecommerce-settings.edit', 'uses' => 'SettingsController@editEcommerce']);
Route::post('/ecommerce-settings/update', ['as'=>'ecommerce-settings.update', 'uses' => 'SettingsController@updateEcommerce']);
Route::post('/ecommerce-countries/update', ['as'=>'ecommerce-countries.update', 'uses' => 'SettingsController@updateCountry']);

//CRUD REDIRECTIONS
Route::resource('redirections', 'RedirectionController', ['except' => ['show','delete']]);

//CRUD FAMILIES
Route::resource('families', 'FamilyController', ['except' => ['show','delete']]);
Route::get('/families/{id}/clone', ['as'=>'families.clone', 'uses' => 'FamilyController@clone']);

//CRUD ITEM CATEGORIES
Route::resource('categories', 'CategoryController', ['except' => ['show','delete']]);
Route::get('/categories/{id}/clone', ['as'=>'item-categories.clone', 'uses' => 'CategoryController@clone']);

//CRUD ITEM SUBCATEGORIES
Route::resource('subcategories', 'SubcategoryController', ['except' => ['show','delete']]);
Route::get('/subcategories/{id}/clone', ['as'=>'subcategories.clone', 'uses' => 'SubcategoryController@clone']);

//CRUD ITEMS
Route::resource('items', 'ItemController', ['except' => ['delete']]);
Route::get('/items/{id}/clone', ['as'=>'items.clone', 'uses' => 'ItemController@clone']);
Route::get('/items/{id}/related', ['as'=>'items.edit_related', 'uses' => 'ItemController@editRelated']);
Route::get('/items/{id}/images', ['as'=>'items.edit_images', 'uses' => 'ItemController@editImages']);
Route::get('/discount', ['as'=>'items.discount', 'uses' => 'ItemController@discount']);
Route::post('/discount', ['as'=>'items.save-discount', 'uses' => 'ItemController@saveDiscount']);
Route::get('/items/{id}/colors', ['as'=>'items.edit_colors', 'uses' => 'ItemController@editColors']);

//CRUD COUPON
Route::resource('coupons', 'CouponController', ['except' => ['show','delete']]);
Route::get('/coupons/{id}/clone', ['as'=>'coupons.clone', 'uses' => 'CouponController@clone']);

//CRUD ORDERS
Route::resource('orders', 'OrderController', ['only' => ['show','index']]);
Route::get('/order/{id}/{field}/{value}', ['as'=>'order.update-state', 'uses' => 'OrderController@updateState']);

//CRUD USERS
Route::resource('users', 'UserController', ['except' => ['show','delete']]);

//CRUD PRICELIST
Route::resource('pricelists', 'PricelistController', ['except' => ['delete']]);

//CRUD SERVICE CATEGORY
Route::get('/service-categories/{category}/edit', ['as'=>'service-categories.edit', 'uses' => 'ServiceCategoryController@edit']);
Route::resource('service-categories', 'ServiceCategoryController', ['except' => ['show','delete','edit']]);
Route::get('/service-category/{id}/clone', ['as'=>'service-categories.clone', 'uses' => 'ServiceCategoryController@clone']);

//CRUD SERVICES
Route::resource('services', 'ServiceController', ['except' => ['show','delete']]);
Route::get('/services/{id}/clone', ['as'=>'services.clone', 'uses' => 'ServiceController@clone']);

//CRUD PROJECT CATEGORY
Route::resource('project-categories', 'ProjectCategoryController', ['except' => ['show','delete']]);
Route::get('/project-categories/{id}/clone', ['as'=>'project-categories.clone', 'uses' => 'ProjectCategoryController@clone']);
//CRUD PROGETTI
Route::resource('projects', 'ProjectController', ['except' => ['show','delete']]);
Route::get('/projects/{id}/clone', ['as'=>'projects.clone', 'uses' => 'ProjectController@clone']);
Route::get('/projects/{id}/add-component', ['as'=>'projects.add-component', 'uses' => 'ProjectController@addComponent']);
Route::post('/projects/add-component', ['as'=>'projects.store-component', 'uses' => 'ProjectController@storeComponent']);
Route::get('/components/{id}/clone', ['as'=>'components.clone', 'uses' => 'ProjectController@cloneComponent']);
Route::get('/components/{id}/edit', ['as'=>'components.edit', 'uses' => 'ProjectController@editComponent']);
Route::put('/components/{id}', ['as'=>'components.update', 'uses' => 'ProjectController@updateComponent']);
Route::get('/components/{id}/images', ['as'=>'components.edit_images', 'uses' => 'ProjectController@editImages']);
