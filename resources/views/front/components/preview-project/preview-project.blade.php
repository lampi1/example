<a href="{{route('projects.view',['permalink'=>$project->slug])}}" class="js--animated js--box-news preview-project">
    <div class="img-box mb-3">
        <img class="img" src="{{$project->image_sm}}">
        <div class="mask">
        </div>
    </div>
    <div class="content px-3">
        <p class="title text--md fw--bold fc--black mb-2">{{$project->title}}</p>
        <p class="text--sm fw--light fc--black">{{$project->subtitle}}</p>
    </div>
</a>
