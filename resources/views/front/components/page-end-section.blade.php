<section class="bg--white">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 orizzontal-offset py-5">
                <div class="row">
                    <div class="col-12 mb-5">
                        <p data-anim="fadeInUp" class="text--xl fc--black fw--bold">
                            {!!__('page.discover-works')!!}
                        </p>
                    </div>
                    @foreach ($projects as $project)
                        <div class="col-lg-6">
                            @include('front.components.preview-project.preview-project',compact('project'))
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
