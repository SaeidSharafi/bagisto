<div class="contracts bg-white px-0 py-4">
        <div class="contracts-carousel">
            <h4 class="text-center font-weight-bold">
                {{$carousel['title']}}
            </h4>
            <contracts
                :items="@js($carousel['items'])"
                locale-direction="{{$direction}}">
            </contracts>
        </div>
</div>

