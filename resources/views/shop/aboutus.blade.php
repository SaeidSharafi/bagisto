@extends('shop::layouts.master')

@section('page_title')
    درباره ما
@stop

@section('seo')
    <meta name="description" content="درباره ما"/>
    <meta name="keywords" content="درباره ما"/>

@stop
@push('css')
    <style>
        .about-us {
            padding: 40px 10px;
            background: #ebebeb;
        }

        .about-us-wrapper {
            border: 1px solid;
            border-radius: 10px;
            margin: 40px auto;
            max-width: 600px;
            padding: 10px;
            z-index: 2;
        }g

        .about-us-wrapper .about-us-content {
            background: #fff;
            border-radius: 10px 10px 0 10px;
            padding: 0;
            position: relative;
            z-index: 6;
            /* overflow: hidden; */
        }

        .about-us-wrapper .content-wrapper {
            padding: 10px;
        }

        .about-us-title {
            position: relative;
        }

        .title-wrapper {
            overflow: hidden;
            padding: 20px 10px 30px 0;
            border-radius: 0 10px 0 0;
        }

        span.title-label {
            background-color: #ffcb05;
            width: 20%;
            height: 22px;
            display: block;
            margin-right: -10px;
            margin-top: -20px;
            border-radius: 0 0 0 10px;
        }

        span.title-text {
            font-size: 2.4rem;
            color: #19499c;
            display: inline-block;
            font-weight: bolder;
            z-index: 3;
            position: relative;
        }

        span.title-text.en {
            color: #c2c2c2;
            font-size: 2rem;
            font-weight: 100;
        }

        .title-particle {
            position: absolute;
            max-width: 95px;
            left: 2%;
            top: -60%;
        }

        @media (min-width: 600px) {
            .title-particle {

                right: 30%;
                left: auto;
            }
        }

        .about-us-bottom {
            background: #00bac6;
            width: 100vw;
            height: 180px;
            transform: translateY(-50%);
            margin-bottom: -100px;
            z-index: 0;
            position: absolute;
            right: 0;
        }
    </style>
@endpush
@section('full-width-content-bot')
    <div class="about-us position-relative">
        <div class="about-us-wrapper">
            <div class="about-us-content">
                <div class="about-us-title">
                    <div class="title-wrapper">
                        <span class="title-label"></span>
                        <span class="title-text">درباره ما</span>
                        <span class="title-text en">About Us</span>
                    </div>
                    <div class="title-particle">
                        <img src="{{asset('images/particles.png')}}" class="w-100">
                    </div>
                </div>
                <div class="content-wrapper">
                    <p>
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم
                        است، و
                        برای
                        شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه
                        و
                        متخصصان
                        را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید
                        داشت که
                        تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود
                        طراحی
                        اساسا
                        مورد استفاده قرار گیرد.
                    </p>
                    <p>
                        لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم
                        است، و
                        برای
                        شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته حال و آینده، شناخت فراوان جامعه
                        و
                        متخصصان
                        را می طلبد، تا با نرم افزارها شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید
                        داشت که
                        تمام و دشواری موجود در ارائه راهکارها، و شرایط سخت تایپ به پایان رسد و زمان مورد نیاز شامل حروفچینی دستاوردهای اصلی، و جوابگوی سوالات پیوسته اهل دنیای موجود
                        طراحی
                        اساسا
                        مورد استفاده قرار گیرد.
                    </p>
                </div>

            </div>
            <div class="about-us-bottom"></div>
        </div>

    </div>
@endsection