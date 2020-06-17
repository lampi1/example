<?xml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

    @foreach($locales as $locale)
        <url>
            <loc>{{config('app.url').'/'.$locale}}</loc>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>

        @if($posts->count() > 0)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('blogs.index'))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endif

        @if($events->count() > 0)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('events.index'))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endif

        @if($news->count() > 0)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('news.index'))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endif

        @if($faqs->count() > 0)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('faqs.index'))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endif

        @if($galleries->count() > 0)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('galleries.index'))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endif

    @endforeach

    @if($posts->count() > 0)
        @foreach ($postcategories as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('blogs.category',['permalink'=>$t->slug]))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach

        @foreach ($posts as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('blogs.view',['permalink'=>$t->slug]))}}</loc>
                <lastmod>{{ $t->published_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach
    @endif

    @if($events->count() > 0)
        @foreach ($eventcategories as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('events.category',['permalink'=>$t->slug]))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach

        @foreach ($events as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('events.view',['permalink'=>$t->slug]))}}</loc>
                <lastmod>{{ $t->published_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach
    @endif

    @if($news->count() > 0)
        @foreach ($newscategories as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('news.category',['permalink'=>$t->slug]))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach

        @foreach ($news as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('news.view',['permalink'=>$t->slug]))}}</loc>
                <lastmod>{{ $t->published_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach
    @endif

    @if($faqs->count() > 0)
        @foreach ($faqs as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('faqs.view',['permalink'=>$t->slug]))}}</loc>
                <lastmod>{{ $t->published_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach
    @endif

    @if($galleries->count() > 0)
        @foreach ($galleries as $t)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('galleries.view',['permalink'=>$t->slug]))}}</loc>
                <lastmod>{{ $t->published_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach
    @endif

    @foreach ($pages as $t)
        <url>
            <loc>{{ LaravelLocalization::getLocalizedURL($t->locale, route('pages.view',['permalink'=>$t->slug]))}}</loc>
            <lastmod>{{ $t->published_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>weekly</changefreq>
            <priority>0.6</priority>
        </url>
    @endforeach

    @foreach ($families as $f)
        @foreach($locales as $locale)

            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('collection.view',['family'=>$f->translate($locale)->slug]))}}</loc>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>

            @foreach($categories as $c)
                <url>
                    <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('collection.view',['family'=>$f->translate($locale)->slug,'category'=>$c->translate($locale)->slug]))}}</loc>
                    <changefreq>weekly</changefreq>
                    <priority>0.6</priority>
                </url>
            @endforeach

        @endforeach
    @endforeach

    @foreach ($items as $t)
        @foreach($locales as $locale)
            <url>
                <loc>{{ LaravelLocalization::getLocalizedURL($locale, route('products.view',['permalink'=>$t->translate($locale)->slug]))}}</loc>
                <lastmod>{{ $t->updated_at->tz('UTC')->toAtomString() }}</lastmod>
                <changefreq>weekly</changefreq>
                <priority>0.6</priority>
            </url>
        @endforeach
    @endforeach

</urlset>
