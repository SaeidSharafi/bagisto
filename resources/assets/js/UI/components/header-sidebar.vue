<template>
    <div
        id="main-category"
        :class="
            `main-category unselectable ${
                $root.sharedRootCategories.length > 0
                    ? 'cursor-pointer'
                    : 'cursor-not-allowed'
            }`
        "
        @mouseout="toggleSidebar('0', $event, 'mouseout')"
        @mouseover="toggleSidebar('0', $event, 'mouseover')"
    >
        <i class="rango-view-list text-down-4 align-vertical-top fs18"> </i>

        <span
            class="pr5"
            v-text="heading"
            @mouseover="toggleSidebar('0', $event, 'mouseover')"
        >
        </span>
    </div>
</template>

<script>
export default {
    props: ['heading']
};
</script>
