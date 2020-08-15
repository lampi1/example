<?php

    //PAGES
    Route::get('/pages', ['as'=>'pages.index', 'uses' => 'PageController@getPages']);
    Route::put('/pages/{id}/status', ['as'=>'pages.update-state', 'uses' => 'PageController@changeState']);
    Route::delete('/pages/{id}', ['as'=>'pages.delete', 'uses' => 'PageController@destroy']);
    Route::delete('/page-attachment/{id}', ['as'=>'pages.attachment-delete', 'uses' => 'PageController@destroyAttachment']);
    Route::put('/page-image/{id}', ['as'=>'pages.img-delete', 'uses' => 'PageController@deleteImage']);

    //PAGE CATEGORIES
    Route::get('/page-categories', ['as'=>'page-categories.index', 'uses' => 'PageCategoryController@getCategories']);
    Route::post('/page-categories', ['as'=>'page-categories.store', 'uses' => 'PageCategoryController@store']);
    Route::post('/page-categories/reorder', ['as'=>'page-categories.reorder', 'uses' => 'PageCategoryController@reorder']);
    Route::delete('/page-categories/{id}', ['as'=>'page-categories.delete', 'uses' => 'PageCategoryController@destroy']);

    //POSTS
    Route::get('/posts', ['as'=>'posts.index', 'uses' => 'PostController@getPosts']);
    Route::get('/posts/{id}', ['as'=>'posts.show', 'uses' => 'PostController@getPost']);
    Route::put('/posts/{id}/status', ['as'=>'posts.update-state', 'uses' => 'PostController@changeState']);
    Route::delete('/posts/{id}', ['as'=>'posts.delete', 'uses' => 'PostController@destroy']);
    Route::delete('/post-attachment/{id}', ['as'=>'posts.attachment-delete', 'uses' => 'PostController@destroyAttachment']);
    Route::put('/post-image/{id}', ['as'=>'posts.img-delete', 'uses' => 'PostController@deleteImage']);
    Route::post('/posts/reorder', ['as'=>'posts.reorder', 'uses' => 'PostController@reorder']);
    Route::post('/posts/remove-related/{id}', ['as'=>'posts.remove-related', 'uses' => 'PostController@removeRelated']);
    Route::post('/posts/add-related/{id}', ['as'=>'posts.add-related', 'uses' => 'PostController@addRelated']);

    //POST CATEGORIES
    Route::get('/post-categories', ['as'=>'post-categories.index', 'uses' => 'PostCategoryController@getCategories']);
    Route::post('/post-categories', ['as'=>'post-categories.store', 'uses' => 'PostCategoryController@store']);
    Route::post('/post-categories/reorder', ['as'=>'post-categories.reorder', 'uses' => 'PostCategoryController@reorder']);
    Route::delete('/post-categories/{id}', ['as'=>'post-categories.delete', 'uses' => 'PostCategoryController@destroy']);

    //EVENTS
    Route::get('/events', ['as'=>'events.index', 'uses' => 'EventController@getEvents']);
    Route::put('/events/{id}/status', ['as'=>'events.update-state', 'uses' => 'EventController@changeState']);
    Route::delete('/events/{id}', ['as'=>'events.delete', 'uses' => 'EventController@destroy']);
    Route::delete('/event-attachment/{id}', ['as'=>'events.attachment-delete', 'uses' => 'EventController@destroyAttachment']);
    Route::put('/events-image/{id}', ['as'=>'events.img-delete', 'uses' => 'EventController@deleteImage']);

    //EVENT CATEGORIES
    Route::get('/event-categories', ['as'=>'event-categories.index', 'uses' => 'EventCategoryController@getCategories']);
    Route::post('/event-categories/reorder', ['as'=>'event-categories.reorder', 'uses' => 'EventCategoryController@reorder']);
    Route::delete('/event-categories/{id}', ['as'=>'event-categories.delete', 'uses' => 'EventCategoryController@destroy']);

    //NEWS
    Route::get('/news', ['as'=>'news.index', 'uses' => 'NewsController@getNews']);
    Route::put('/news/{id}/status', ['as'=>'news.update-state', 'uses' => 'NewsController@changeState']);
    Route::delete('/news/{id}', ['as'=>'news.delete', 'uses' => 'NewsController@destroy']);
    Route::delete('/news-attachment/{id}', ['as'=>'news.attachment-delete', 'uses' => 'NewsController@destroyAttachment']);
    Route::put('/news-image/{id}', ['as'=>'news.img-delete', 'uses' => 'NewsController@deleteImage']);

    //NEWS CATEGORIES
    Route::get('/news-categories', ['as'=>'news-categories.index', 'uses' => 'NewsCategoryController@getCategories']);
    Route::post('/news-categories', ['as'=>'news-categories.store', 'uses' => 'NewsCategoryController@store']);
    Route::post('/news-categories/reorder', ['as'=>'news-categories.reorder', 'uses' => 'NewsCategoryController@reorder']);
    Route::delete('/news-categories/{id}', ['as'=>'news-categories.delete', 'uses' => 'NewsCategoryController@destroy']);

    //FAQ
    Route::get('/faqs', ['as'=>'faqs.index', 'uses' => 'FaqController@getFaqs']);
    Route::put('/faqs/{id}/status', ['as'=>'faqs.upadte-state', 'uses' => 'FaqController@changeState']);
    Route::delete('/faqs/{id}', ['as'=>'faqs.delete', 'uses' => 'FaqController@destroy']);

    //FAQ CATEGORIES
    Route::get('/faq-categories', ['as'=>'faq-categories.index', 'uses' => 'FaqCategoryController@getCategories']);
    Route::post('/faq-categories', ['as'=>'faq-categories.store', 'uses' => 'FaqCategoryController@store']);
    Route::post('/faq-categories/reorder', ['as'=>'faq-categories.reorder', 'uses' => 'FaqCategoryController@reorder']);
    Route::delete('/faq-categories/{id}', ['as'=>'faq-categories.delete', 'uses' => 'FaqCategoryController@destroy']);

    //GALLERY CATEGORIES
    Route::get('/gallery-categories', ['as'=>'gallery-categories.index', 'uses' => 'GalleryCategoryController@getCategories']);
    Route::post('/gallery-categories', ['as'=>'gallery-categories.store', 'uses' => 'GalleryCategoryController@store']);
    Route::post('/gallery-categories/reorder', ['as'=>'gallery-categories.reorder', 'uses' => 'GalleryCategoryController@reorder']);
    Route::delete('/gallery-categories/{id}', ['as'=>'gallery-categories.delete', 'uses' => 'GalleryCategoryController@destroy']);
    //GALLERIES
    Route::get('/galleries', ['as'=>'galleries.index', 'uses' => 'GalleryController@getGalleries']);
    Route::put('/galleries/{id}/status', ['as'=>'galleries.update-state', 'uses' => 'GalleryController@changeState']);
    Route::delete('/galleries/{id}', ['as'=>'galleries.delete', 'uses' => 'GalleryController@destroy']);
    Route::put('/galleries-image/{id}', ['as'=>'galleries.img-delete', 'uses' => 'GalleryController@deleteImage']);
    //GALLERIES MEDIAS
    Route::get('/galleries/{gallery_id}/medias', ['as'=>'galleries-medias.index', 'uses' => 'GalleryController@getMedias']);
    Route::post('/galleries/{parent_id}/reorder', ['as'=>'galleries-medias.reorder', 'uses' => 'GalleryController@reorderMedias']);
    Route::delete('/galleries/media/{id}', ['as'=>'galleries-medias.delete', 'uses' => 'GalleryController@destroyMedia']);
    Route::put('/galleries/media-image/{id}', ['as'=>'galleries-medias.img-delete', 'uses' => 'GalleryController@deleteMediaImage']);

    //SLIDERS
    Route::get('/sliders', ['as'=>'sliders.index', 'uses' => 'SliderController@getSliders']);
    Route::post('/sliders/reorder', ['as'=>'sliders.reorder', 'uses' => 'SliderController@reorder']);
    Route::put('/sliders/{id}', ['as'=>'sliders.update', 'uses' => 'SliderController@update']);
    Route::delete('/sliders/{id}', ['as'=>'sliders.delete', 'uses' => 'SliderController@destroy']);
    //SLIDERS SLIDES
    Route::get('/sliders/{slider_id}/slides', ['as'=>'sliders-slides.index', 'uses' => 'SliderController@getSlides']);
    Route::post('/sliders/{parent_id}/reorder', ['as'=>'sliders-slides.reorder', 'uses' => 'SliderController@reorderSlides']);
    Route::delete('/sliders/slide/{id}', ['as'=>'sliders-slides.delete', 'uses' => 'SliderController@destroySlide']);
    Route::put('/slide-image/{id}', ['as'=>'slides.img-delete', 'uses' => 'SliderController@deleteImage']);

    //FORMS
    Route::get('/forms', ['as'=>'forms.index', 'uses' => 'FormController@getForms']);
    Route::delete('/forms/{id}', ['as'=>'forms.delete', 'uses' => 'FormController@destroy']);
    Route::get('/forms/{id}', ['as'=>'forms.show', 'uses' => 'FormController@show']);
    Route::post('/forms', ['as'=>'forms.save', 'uses' => 'FormController@save']);

    //LANDING PAGES
    Route::get('/landing-pages', ['as'=>'landing-pages.index', 'uses' => 'LandingPageController@getPages']);
    Route::put('/landing-pages/{id}/status', ['as'=>'landing-pages.update-state', 'uses' => 'LandingPageController@changeState']);
    Route::delete('/landing-pages/{id}', ['as'=>'landing-pages.delete', 'uses' => 'LandingPageController@destroy']);
    Route::put('/landing-page-image/{id}', ['as'=>'landing-pages.img-delete', 'uses' => 'LandingPageController@deleteImage']);

    //MENUS
    Route::get('/menus/resources', ['as'=>'menus.resources', 'uses' => 'MenuBuilderController@getResources']);
    Route::get('/menus/{menu_id}', ['as'=>'menus.index', 'uses' => 'MenuBuilderController@getMenuItems']);
    Route::delete('/menus/{id}', ['as'=>'menus.delete', 'uses' => 'MenuBuilderController@destroy']);
    Route::post('/menus', ['as'=>'menus.save', 'uses' => 'MenuBuilderController@save']);
    Route::put('/menus/{id}', ['as'=>'menus.update', 'uses' => 'MenuBuilderController@saveItems']);

    //REDIRECTION
    Route::get('/redirections', ['as'=>'redirections.index', 'uses' => 'RedirectionController@getRedirections']);
    Route::delete('/redirections/{id}', ['as'=>'redirections.delete', 'uses' => 'RedirectionController@destroy']);
    Route::post('/redirections/reorder', ['as'=>'redirections.reorder', 'uses' => 'RedirectionController@reorder']);

    //SETTINGS SOCIALS
    Route::post('/social-settings', ['as'=>'social-settings.create', 'uses' => 'SettingsController@storeSocial']);
    Route::delete('/social-settings/{id}', ['as'=>'social-settings.delete', 'uses' => 'SettingsController@destroySocial']);

    //SETTINGS ECOMMERCE
    Route::delete('/ecommerce-settings/{id}', ['as'=>'ecommerce-settings.delete', 'uses' => 'SettingsController@destroyEcommerce']);
    Route::delete('/ecommerce-countries/{id}', ['as'=>'ecommerce-countries.delete', 'uses' => 'SettingsController@destroyCountry']);

    //FAMILIES
    Route::get('/families', ['as'=>'families.index', 'uses' => 'FamilyController@getFamilies']);
    Route::post('/families/reorder', ['as'=>'families.reorder', 'uses' => 'FamilyController@reorder']);
    Route::put('/family-image/{id}', ['as'=>'families.img-delete', 'uses' => 'FamilyController@deleteImage']);
    Route::delete('/families/{id}', ['as'=>'families.delete', 'uses' => 'FamilyController@destroy']);

    //ITEM CATEGORIES
    Route::get('/categories', ['as'=>'categories.index', 'uses' => 'CategoryController@getCategories']);
    Route::post('/categories/reorder', ['as'=>'categories.reorder', 'uses' => 'CategoryController@reorder']);
    Route::put('/category-image/{id}', ['as'=>'categories.img-delete', 'uses' => 'CategoryController@deleteImage']);
    Route::delete('/categories/{id}', ['as'=>'categories.delete', 'uses' => 'CategoryController@destroy']);

    //ITEM SUBCATEGORIES
    Route::get('/subcategories', ['as'=>'subcategories.index', 'uses' => 'SubcategoryController@getCategories']);
    Route::post('/subcategories/reorder', ['as'=>'subcategories.reorder', 'uses' => 'SubcategoryController@reorder']);
    Route::put('/subcategory-image/{id}', ['as'=>'subcategories.img-delete', 'uses' => 'SubcategoryController@deleteImage']);
    Route::delete('/subcategories/{id}', ['as'=>'subcategories.delete', 'uses' => 'SubcategoryController@destroy']);

    //ITEMS
    Route::get('/items', ['as'=>'items.index', 'uses' => 'ItemController@getItems']);
    Route::get('/items/{id}', ['as'=>'items.show', 'uses' => 'ItemController@getItem']);
    Route::post('/items/reorder', ['as'=>'items.reorder', 'uses' => 'ItemController@reorder']);
    Route::delete('/items/{id}', ['as'=>'items.delete', 'uses' => 'ItemController@destroy']);
    Route::put('/items/{id}/status', ['as'=>'items.update-state', 'uses' => 'ItemController@changeState']);
    Route::post('/items/remove-related/{id}', ['as'=>'items.remove-related', 'uses' => 'ItemController@removeRelated']);
    Route::post('/items/add-related/{id}', ['as'=>'items.add-related', 'uses' => 'ItemController@addRelated']);
    Route::post('/items/remove-image/{id}', ['as'=>'items.remove-image', 'uses' => 'ItemController@deleteImage']);
    Route::post('/items/add-image/{id}', ['as'=>'items.add-image', 'uses' => 'ItemController@addImage']);
    Route::post('/items/images-reorder/{id}', ['as'=>'items.images-reorder', 'uses' => 'ItemController@reorderImages']);
    Route::post('/items/add-color/{id}', ['as'=>'items.add-color', 'uses' => 'ItemController@addColor']);
    Route::post('/items/remove-color/{id}', ['as'=>'items.remove-color', 'uses' => 'ItemController@deleteColor']);

    //COUPONS
    Route::get('/coupons', ['as'=>'coupons.index', 'uses' => 'CouponController@getCoupons']);
    Route::delete('/coupons/{id}', ['as'=>'coupons.delete', 'uses' => 'CouponController@destroy']);

    //ORDERS
    Route::get('/orders', ['as'=>'orders.index', 'uses' => 'OrderController@getOrders']);

    //USERS
    Route::get('/users', ['as'=>'users.index', 'uses' => 'UserController@getUsers']);
    Route::delete('/users/{id}', ['as'=>'users.delete', 'uses' => 'UserController@destroy']);
    Route::put('/users/{id}/status', ['as'=>'users.update-state', 'uses' => 'UserController@changeState']);

    //PRICELIST
    Route::delete('/pricelists/{id}', ['as'=>'pricelists.delete', 'uses' => 'PricelistController@destroy']);
    Route::get('/pricelists/{id}', ['as'=>'pricelists.show', 'uses' => 'PricelistController@show']);
    Route::post('/pricelist', ['as'=>'pricelists.save', 'uses' => 'PricelistController@save']);
    Route::get('/pricelist-get-users', ['as'=>'pricelist.users', 'uses' => 'PricelistController@getUsers']);
    Route::get('/pricelist-get-families', ['as'=>'pricelist.families', 'uses' => 'PricelistController@getFamilies']);
    Route::get('/pricelist-get-categories', ['as'=>'pricelist.categories', 'uses' => 'PricelistController@getCategories']);
    Route::get('/pricelist-get-items', ['as'=>'pricelist.item', 'uses' => 'PricelistController@getItems']);

    //SERVICES
    Route::get('/services', ['as'=>'services.index', 'uses' => 'ServiceController@getServices']);
    Route::put('/services/{id}/status', ['as'=>'services.update-state', 'uses' => 'ServiceController@changeState']);
    Route::delete('/services/{id}', ['as'=>'services.delete', 'uses' => 'ServiceController@destroy']);
    Route::delete('/services-attachment/{id}', ['as'=>'services.attachment-delete', 'uses' => 'ServiceController@destroyAttachment']);
    Route::put('/services-image/{id}', ['as'=>'services.img-delete', 'uses' => 'ServiceController@deleteImage']);

    //SERVICE CATEGORIES
    Route::get('/service-categories', ['as'=>'service-categories.index', 'uses' => 'ServiceCategoryController@getCategories']);
    Route::post('/service-categories', ['as'=>'service-categories.store', 'uses' => 'ServiceCategoryController@store']);
    Route::post('/service-categories/reorder', ['as'=>'service-categories.reorder', 'uses' => 'ServiceCategoryController@reorder']);
    Route::delete('/service-categories/{id}', ['as'=>'service-categories.delete', 'uses' => 'ServiceCategoryController@destroy']);
