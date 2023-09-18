<template>
    <div class="layout-topbar">
        <Link href="/" class="layout-topbar-logo">
            <img src="/images/logo-horizontal-completo.png" alt="">
        </Link>
        <button class="p-link layout-menu-button layout-topbar-button" @click="onMenuToggle">
            <i class="pi pi-bars"></i>
        </button>

        <button class="p-link layout-topbar-menu-button layout-topbar-button"
                :class="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein',
			leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true}">
            <i class="pi pi-ellipsis-v"></i>
        </button>
        <ul class="layout-topbar-menu hidden lg:flex origin-top">
            <!--
            <li>
                <button class="p-link layout-topbar-button">
                    <i class="pi pi-calendar"></i>
                    <span>Events</span>
                </button>
            </li>
             <li>
                <Button
                    class="p-button-link  p-link layout-topbar-button"
                    @click="goToSettings"
                >
                    <i class="pi pi-cog"></i>
                    <span>Settings</span>
                </Button>
            </li> -->
            <li>
                <Button
                    class="p-button-link p-link layout-topbar-button"
                    @click="goToSettings"
                >
                    <i class="pi pi-user"></i>
                    <span>Profile</span>
                </Button>
            </li>
        </ul>
    </div>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3';
import Button from "primevue/button";

export default {
    components: {
        Link,
        Button
    },
    methods: {
        onMenuToggle(event) {
            this.$emit('menu-toggle', event);
        },
        onTopbarMenuToggle(event) {
            this.$emit('topbar-menu-toggle', event);
        },
        goToSettings(){
            this.$inertia.get(this.route('profile.show'));
        }
    },
}
</script>
