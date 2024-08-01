<div class="footer-address">
    <div class="block-title"><strong>اطلاعات تماس</strong></div>
    <div class="block-content">
        @if($velocityMetaData->subscription_bar_content)
            {!! $velocityMetaData->subscription_bar_content !!}
        @else
            <ul class="contact-info">
                <li>
                    <div class="info-line">
                        <i class="fa fa-map-marker-alt"></i>
                        <div>
                            <span class="font-weight-bold">آدرس مرکز یک: </span>
                            قزوین، چهارراه ولیعصر(عج)، جنب بانک سپه
                        </div>
                    </div>

                </li>
                <li>
                    <div class="info-line">
                        <i class="fa fa-map-marker-alt"></i>
                        <div>
                            <span class="font-weight-bold">آدرس مرکز دو: </span>
                            قزوین، خیابان دانشگاه، بلوار شهید بابایی، کوچه خاکعلی، پ ۱۵
                        </div>
                    </div>
                </li>
                <li>
                    <div class="info-line">

                        <i class="fa fa-phone"></i>
                        <div>
                            <span class="font-weight-bold">تلفن مرکز یک:<br></span>
                            <a href="tel:02833376797">33376797-9(028)</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="info-line">

                        <i class="fa fa-phone"></i>
                        <div>
                            <span class="font-weight-bold">تلفن مرکز دو:</span><br>
                            <a href="tel:02833667001">33667001(028)</a>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="info-line">
                        <i class="fa fa-envelope"></i>
                        <div>
                            <span class="font-weight-bold">ایمیل:<br></span>
                            <a href="mailto:jedu@jdqazvin.ir">jedu@jdqazvin.ir</a>
                        </div>
                    </div>
                </li>
            </ul>
        @endif
    </div>
</div>