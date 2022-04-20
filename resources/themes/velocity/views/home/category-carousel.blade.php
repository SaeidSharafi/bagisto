<div class="w-100 category-carousel {{$direction}} py-3">
    <div class="row">
        @foreach($front_categories as $category)
            @php
            $base = "/storage/";
            $image_path = $category->image ?$base .$category->image : '/images/category-base.png';
            @endphp
            <div class="col-lg-2 col-md-3">
                    <a href="/{{$category->slug}}"
                       class="d-block">
                        <div class="category-item">
                            <img src="{{$image_path}}" class="w-100">
                            <p class="category-text d-block text-center">   {{$category->name}}</p>

                        </div>
                    </a>
            </div>


        @endforeach
    </div>
</div>
