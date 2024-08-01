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
        }

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
                    @if (isset($page))
                        {!! DbView::make($page)->field('html_content')->render() !!}
                    @else
                        <p>
                            مرکز آموزش های تخصصی کوتاه مدت جهاد دانشگاهی قزوین در راستای ارتقا سطح دانش‌، نگرش و مهارت نیروهای تخصصی برای توسعه استان ضمن برخورداری از نیروهای متخصص،
                            متعهد و نوآور با بهره‌گیری از توان علمی داخلی و خارجی، در سطح استانی و منطقه‌ای فعالیت می‌نماید. در این مرکز دوره‌های آموزشی در رشته‌های گوناگون، بنا به
                            درخواست و نیاز فراگیران و براساس انجام نیازسنجی‌های متعدد در سطح جامعه و سازمان‌های متقاضی طراحی، تدوین و اجرا می گردد. این مرکز در زمینه نیازسنجی آموزشی،
                            طراحی، برنامه‌ریزی، اجراء و برگزاری دوره‌های آموزشی و آزمون تخصصی بصورت علمی و استاندارد فعالیت دارد.
                        </p>
                        <p>
                            مرکز آموزش های تخصصی کوتاه مدت جهاددانشگاهی قزوین با بالغ بر1200متر مربع بنای آموزشی و 14 کلاس آموزشی مجهز به لوازم صوتی و تصویری با ظرفیت آموزش همزمان در
                            روز1200 نفر، شش سایت رایانه مجهز با ظرفیت آموزش همزمان در روز۵۰۰ نفر، کارگاه و آتلیه آموزشی (جهت دوره های عملی)، داروخانه مجازی (آموزش دوره های تکنسین
                            داروخانه)، پراتیک (آموزش فوریت های پزشکی و کمک پرستاری)، کارگاه خیاطی ، کارگاه های طلاسازی،سنگ تراشی،چوب و رزین و سالن کنفرانس و جلسات، امکانات آموزشی
                            مورد نیاز را برای برگزاری دوره های آموزشی با کیفیت فراهم نموده است.
                        </p>
                        <div>
                            <p>گروه های آموزشی</p>
                            <ol>
                                <li> فنی و مهندسی</li>
                                <li> علوم انسانی</li>
                                <li> زبان های خارجه</li>
                                <li> فرهنگ و هنر</li>
                                <li> علوم رایانه</li>
                                <li> آموزش های عالی آزاد</li>
                                <li> علوم پزشکی</li>
                                <li> آموزش های سازمانی</li>
                                <li> آموزش مجازی</li>
                                <li> اشتغال و کارآفرینی</li>
                                <li> کشاورزی</li>
                            </ol>
                        </div>
                        <div>
                            <p>مدرسان</p>
                            <ol>
                                <li>توانایی بالای علمی ، تجربی و دانشگاهی</li>
                                <li>سابقه تدریس در مراکز آموزشی و دانشگاهی</li>
                                <li>رشته آموزشی مرتبط</li>
                                <li>تعهد، تدین و تخصص</li>
                            </ol>
                        </div>
                        <p>

                            مرکز شماره 1:
                            <br>
                            آدرس: قزوین ، چهارراه ولیعصر،جنب بانک سپه
                            <br>
                            شماره تماس:
                            <a class="unset" href="tel:02833376797"> <span class="ltr d-inline-block"> 02833376797-9</span></a>
                        </p>
                        <p>
                            مرکز شماره 2:
                            <br>
                            آدرس: قزوین، سه راه خیام، جنب پاساژ علوی
                            <br>
                            تلفن:
                            <a class="unset" href="tel:02833242624"> 02833242624</a>
                        </p>
                    @endif

                </div>

            </div>
            <div class="about-us-bottom"></div>
        </div>

    </div>
@endsection