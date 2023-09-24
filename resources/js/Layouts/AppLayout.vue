<template>
    <div :class="containerClass" @click="onWrapperClick">
        <AppTopBar @menu-toggle="onMenuToggle" />
        <div class="layout-sidebar" @click="onSidebarClick">
            <AppMenu :model="menuAdmin" @menuitem-click="onMenuItemClick" v-role="'ADMIN'" />
            <AppMenu :model="menuCliente" @menuitem-click="onMenuItemClick" v-role="'CLIENTE'" />
            <AppMenu :model="menuConsultor" @menuitem-click="onMenuItemClick" v-role="'CONSULTOR'" />
        </div>

        <div class="layout-main-container">
            <AppConfig :layoutMode="layoutMode" @layout-change="onLayoutChange" />
            <div class="layout-main">
                <slot />
            </div>
            <AppFooter />
        </div>

        <transition name="layout-mask">
            <div class="layout-mask p-component-overlay" v-if="mobileMenuActive"></div>
        </transition>
        <Toast />
    </div>
</template>

<script>
import AppTopBar from '../Components/AppTopbar.vue';
import AppMenu from '../Components/AppMenu.vue';
import AppFooter from '../Components/AppFooter.vue';
import AppConfig from '../Components/AppConfig.vue';
import Toast from 'primevue/toast';

export default {
    data() {
        return {
            layoutMode: 'static',
            layoutColorMode: 'light',
            staticMenuInactive: false,
            overlayMenuActive: false,
            mobileMenuActive: false,
            menuAdmin: [
                {
                    label: 'Menu',
                    items: [
                        { label: 'Dashboard', icon: 'pi pi-fw pi-home', to: this.route('dashboard'), prefix: 'dashboard' },
                        {
                            label: 'Administrar', icon: 'pi pi-fw pi-users',
                            items: [
                                { label: 'Empresas', icon: 'pi pi-fw pi-briefcase', to: this.route('empresas.index'), },
                                { label: 'Usuarios', icon: 'pi pi-fw pi-users', to: this.route('users.index'), },
                                { label: 'Roles', icon: 'pi pi-fw pi-lock', to: this.route('roles.index'), },
                                { label: 'Categorias', icon: 'pi pi-fw pi-list', to: this.route('categorias.index'), },
                                { label: 'Actividades', icon: 'pi pi-fw pi-list', to: this.route('actividades.index'), },
                            ]
                        },
                        {
                            label: 'Cerrar sesión', icon: 'pi pi-fw pi-sign-out', command: () => {
                                this.$inertia.post(this.route('logout'))
                            },
                        }

                    ]
                },
            ],
            menuConsultor: [
                {
                    label: 'Menu',
                    items: [
                        { label: 'Clientes', icon: 'pi pi-fw pi-users', to: this.route('empresas.index'), },
                        {
                            label: 'Cerrar sesión', icon: 'pi pi-fw pi-sign-out', command: () => {
                                this.$inertia.post(this.route('logout'))
                            },
                        },
                    ]
                },
            ],
            menuCliente: [
                {
                    label: 'Menu',
                    items: [
                        { label: 'Dashboard', icon: 'pi pi-fw pi-home', to: this.route('dashboard'), prefix: 'dashboard' },
                        {
                            label: 'Cerrar sesión', icon: 'pi pi-fw pi-sign-out', command: () => {
                                this.$inertia.post(this.route('logout'))
                            },
                        },
                    ]
                },
            ]
        }
    },
    watch: {
        $route() {
            this.menuActive = false;
            this.$toast.removeAllGroups();
        }
    },
    methods: {
        onWrapperClick() {
            if (!this.menuClick) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }

            this.menuClick = false;
        },
        onMenuToggle() {
            this.menuClick = true;

            if (this.isDesktop()) {
                if (this.layoutMode === 'overlay') {
                    if (this.mobileMenuActive === true) {
                        this.overlayMenuActive = true;
                    }

                    this.overlayMenuActive = !this.overlayMenuActive;
                    this.mobileMenuActive = false;
                } else if (this.layoutMode === 'static') {
                    this.staticMenuInactive = !this.staticMenuInactive;
                }
            } else {
                this.mobileMenuActive = !this.mobileMenuActive;
            }

            event.preventDefault();
        },
        onSidebarClick() {
            this.menuClick = true;
        },
        onMenuItemClick(event) {
            if (event.item && !event.item.items) {
                this.overlayMenuActive = false;
                this.mobileMenuActive = false;
            }
        },
        onLayoutChange(layoutMode) {
            this.layoutMode = layoutMode;
        },
        onLayoutColorChange(layoutColorMode) {
            this.layoutColorMode = layoutColorMode;
        },
        addClass(element, className) {
            if (element.classList)
                element.classList.add(className);
            else
                element.className += ' ' + className;
        },
        removeClass(element, className) {
            if (element.classList)
                element.classList.remove(className);
            else
                element.className = element.className.replace(new RegExp('(^|\\b)' + className.split(' ').join('|') + '(\\b|$)', 'gi'), ' ');
        },
        isDesktop() {
            return window.innerWidth >= 992;
        },
        isSidebarVisible() {
            if (this.isDesktop()) {
                if (this.layoutMode === 'static')
                    return !this.staticMenuInactive;
                else if (this.layoutMode === 'overlay')
                    return this.overlayMenuActive;
            }

            return true;
        }
    },
    computed: {
        containerClass() {
            return ['layout-wrapper', {
                'layout-overlay': this.layoutMode === 'overlay',
                'layout-static': this.layoutMode === 'static',
                'layout-static-sidebar-inactive': this.staticMenuInactive && this.layoutMode === 'static',
                'layout-overlay-sidebar-active': this.overlayMenuActive && this.layoutMode === 'overlay',
                'layout-mobile-sidebar-active': this.mobileMenuActive,
                'p-input-filled': this.$primevue.config.inputStyle === 'filled',
                'p-ripple-disabled': this.$primevue.config.ripple === false,
            }];
        },
        logo() {
            return (this.layoutColorMode === 'dark') ? "public/images/logo-white.svg" : "public/images/logo.svg";
        }
    },
    beforeUpdate() {
        if (this.mobileMenuActive)
            this.addClass(document.body, 'body-overflow-hidden');
        else
            this.removeClass(document.body, 'body-overflow-hidden');
    },
    components: {
        'AppTopBar': AppTopBar,
        'AppMenu': AppMenu,
        'AppFooter': AppFooter,
        'AppConfig': AppConfig,
        Toast,
    }
}
</script>
