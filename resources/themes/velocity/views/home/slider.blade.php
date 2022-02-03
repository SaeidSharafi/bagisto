@if ($velocityMetaData && $velocityMetaData->slider)
    <div class="slideshow-wrapper ltr">
        <div class="slideshow">
            <div class="slideshow-inner">
                <div class="slides">
                    @forelse($sliderData as $slide)
                        <div class="slide {{($loop->first) ? 'is-active' : ''}} ">
                            @if ($slide['show_content'])
                                <div class="slide-content">
                                    <div class="caption">
                                        <div class="title">{{$slide['title']}}</div>
                                        @if($slide['description'])
                                            <div class="text">
                                                <p>{{$slide['description']}}</p>
                                            </div>
                                        @endif
                                        @if($slide['slider_path'])
                                            <a href="{{$slide['slider_path']}}" class="btn">
                                                <span class="btn-inner">{{$slide['description'] ?: 'مشاهده مطلب'}}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="slide-content">
                                    <div class="caption">
                                        <div class="title"></div>
                                    </div>
                                </div>
                            @endif

                            <div class="image-container {{$slide['show_content'] ? 'has-description' : ''}}">
                                <img loading="lazy" src="{{asset('/storage/'.$slide['path'])}}" alt="" class="image">
                            </div>
                        </div>


                    @empty
                        <div class="slide is-active">
                            <div class="slide-content">
                                <div class="caption">
                                    <div class="title">Slide title 1</div>
                                    <div class="text">
                                        <p>Slide description 1</p>
                                    </div>
                                    <a href="#" class="btn">
                                        <span class="btn-inner">Learn More</span>
                                    </a>
                                </div>
                            </div>
                            <div class="image-container">
                                <img src="{{ asset('/themes/velocity/assets/images/banner.webp') }}" alt="" class="image">
                            </div>
                        </div>
                    @endforelse

                </div>
                <div class="pagination d-none">
                    @forelse($sliderData as $slide)
                        <div class="item {{($loop->first) ? 'is-active' : ''}}">
                            <span class="icon">{{$loop->iteration}}</span>
                        </div>
                    @empty
                        <div class="item is-active">
                            <span class="icon">1</span>
                        </div>
                    @endforelse
                </div>
                <div class="arrows">
                    <div class="arrow prev">
          <span class="svg svg-arrow-left">
            <svg version="1.1" id="svg4-Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="26px"
                 viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path
                    d="M13,26c-0.256,0-0.512-0.098-0.707-0.293l-12-12c-0.391-0.391-0.391-1.023,0-1.414l12-12c0.391-0.391,1.023-0.391,1.414,0s0.391,1.023,0,1.414L2.414,13l11.293,11.293c0.391,0.391,0.391,1.023,0,1.414C13.512,25.902,13.256,26,13,26z"/> </svg>
            <span class="alt sr-only"></span>
          </span>
                    </div>
                    <div class="arrow next">
          <span class="svg svg-arrow-right">
            <svg version="1.1" id="svg5-Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="26px"
                 viewBox="0 0 14 26" enable-background="new 0 0 14 26" xml:space="preserve"> <path
                    d="M1,0c0.256,0,0.512,0.098,0.707,0.293l12,12c0.391,0.391,0.391,1.023,0,1.414l-12,12c-0.391,0.391-1.023,0.391-1.414,0s-0.391-1.023,0-1.414L11.586,13L0.293,1.707c-0.391-0.391-0.391-1.023,0-1.414C0.488,0.098,0.744,0,1,0z"/> </svg>
            <span class="alt sr-only"></span>
          </span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    {{--    <div class="splide ltr">--}}
    {{--        <div class="splide__track">--}}
    {{--            <ul class="splide__list">--}}
    {{--                @forelse($sliderData as $slide)--}}
    {{--                    <li class="splide__slide">--}}
    {{--                        <img src="{{asset('/storage/'.$slide['path'])}}">--}}
    {{--                    </li>--}}
    {{--                @empty--}}
    {{--                    <img class="col-12 no-padding banner-icon" src="{{ asset('/themes/velocity/assets/images/banner.webp') }}" alt=""/>--}}
    {{--                @endforelse--}}
    {{--            </ul>--}}
    {{--        </div>--}}
    {{--    </div>--}}



@endif
