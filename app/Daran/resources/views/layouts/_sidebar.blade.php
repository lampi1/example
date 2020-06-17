<div id="sidebar" class="w-100 col-2 px-0">
    <div class="row no-gutters p-4">
        <div class="col-12 mb-4">
            <div class="sidebar__user">
                <div class="sidebar__user-img" style="background-image:url(@if (!empty(Auth::user()->picture)) '{{Auth::user()->picture}}' @else '/images/default_avatar.jpg'@endif)">
                </div>
                 <span class="sidebar__user-name">{{Auth::user()->getFullNameAttribute()}}</span>
             </div>
        </div>
        <div class="col-12 mb-4">
            <a class="sidebar__link {{request()->is('*pages*') ? 'active' : '' }}" href="{{route('admin.pages.index')}}" title="@lang('daran::page.pages')">
                <span>@lang('daran::page.pages')</span>
            </a>
            {{-- <a class="sidebar__link {{request()->is('*posts*') ? 'active' : '' }}" href="{{route('admin.posts.index')}}" title="@lang('daran::post.posts')">
                <span>@lang('daran::post.posts')</span>
            </a> --}}
            <a class="sidebar__link {{request()->is('*news*') ? 'active' : '' }}" href="{{route('admin.news.index')}}" title="@lang('daran::news.news')">
                <span>@lang('daran::news.news')</span>
            </a>
            {{-- <a class="sidebar__link {{request()->is('*services*') ? 'active' : '' }}" href="{{route('admin.services.index')}}" title="@lang('daran::service.services')">
                <span>@lang('daran::service.services')</span>
            </a> --}}
            {{-- <a class="sidebar__link {{request()->is('*events*') ? 'active' : '' }}" href="{{route('admin.events.index')}}" title="@lang('daran::event.events')">
                <span>@lang('daran::event.events')</span>
            </a> --}}
            <a class="sidebar__link {{request()->is('*projects*') ? 'active' : '' }}" href="{{route('admin.projects.index')}}" title="Progetti">
                <span>Progetti</span>
            </a>
            <a class="sidebar__link {{request()->is('*sliders*') ? 'active' : '' }}" href="{{route('admin.sliders.index')}}" title="@lang('daran::slider.sliders')">
                <span>@lang('daran::slider.sliders')</span>
            </a>
            <a class="sidebar__link {{request()->is('*galleries*') ? 'active' : '' }}" href="{{route('admin.galleries.index')}}" title="@lang('daran::gallery.galleries')">
                <span>@lang('daran::gallery.galleries')</span>
            </a>
            {{-- <a class="sidebar__link {{request()->is('*landing-pages*') ? 'active' : '' }}" href="{{route('admin.landing-pages.index')}}" title="@lang('daran::landing_page.pages')">
                <span>@lang('daran::landing_page.pages')</span>
            </a>
            <a class="sidebar__link {{request()->is('*forms*') ? 'active' : '' }}" href="{{route('admin.forms.index')}}" title="@lang('daran::form.forms')">
                <span>@lang('daran::form.forms')</span>
            </a>
            <a class="sidebar__link {{request()->is('*menus*') ? 'active' : '' }}" href="{{route('admin.menus.index')}}" title="@lang('daran::menu.menus')">
                <span>@lang('daran::menu.menus')</span>
            </a> --}}
            {{-- <a class="sidebar__link {{request()->is('*users*') ? 'active' : '' }}" href="{{route('admin.users.index')}}" title="@lang('daran::user.users')">
                <span>@lang('daran::user.users')</span>
            </a>
            <a class="sidebar__link {{request()->is('*pricelists*') ? 'active' : '' }}" href="{{route('admin.pricelists.index')}}" title="@lang('daran::pricelist.pricelists')">
                <span>@lang('daran::pricelist.pricelists')</span>
            </a> --}}
            @if(config('daran.ecommerce.enable'))
                <a class="sidebar__link {{request()->is('*items*') ? 'active' : '' }}" href="{{route('admin.items.index')}}" title="@lang('daran::item.products')">
                    <span>@lang('daran::item.products')</span>
                </a>
                <a class="sidebar__link {{request()->is('*items*') ? 'active' : '' }}" href="{{route('admin.items.index')}}" title="@lang('daran::item.products')">
                    <span>@lang('daran::item.products')</span>
                </a>
                <a class="sidebar__link {{request()->is('*coupons*') ? 'active' : '' }}" href="{{route('admin.coupons.index')}}" title="@lang('daran::coupon.coupons')">
                    <span>@lang('daran::coupon.coupons')</span>
                </a>
                <a class="sidebar__link {{request()->is('*orders*') ? 'active' : '' }}" href="{{route('admin.orders.index')}}" title="@lang('daran::order.orders')">
                    <span>@lang('daran::order.orders')</span>
                </a>
            @endif
            <a class="sidebar__link {{request()->is('*settings*') ? 'active' : '' }}" href="{{route('admin.settings.edit')}}" title="@lang('daran::setting.settings')">
                <span>@lang('daran::setting.settings')</span>
            </a>

        </div>

    </div>
</div>
