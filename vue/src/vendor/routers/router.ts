import { createRouter, createWebHistory } from 'vue-router'
import { beforeEach, afterEach } from '@/vendor/routers/request'

const routes = [

    { name: 'pg',               path: '/:get_0', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_1',             path: '/:get_0/:get_1', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_2',             path: '/:get_0/:get_1/:get_2', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_3',             path: '/:get_0/:get_1/:get_2/:get_3', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_4',             path: '/:get_0/:get_1/:get_2/:get_3/:get_4', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_5',             path: '/:get_0/:get_1/:get_2/:get_3/:get_4/:get_5', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_6',             path: '/:get_0/:get_1/:get_2/:get_3/:get_4/:get_5/:get_6', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_7',             path: '/:get_0/:get_1/:get_2/:get_3/:get_4/:get_5/:get_6/:get_7', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_8',             path: '/:get_0/:get_1/:get_2/:get_3/:get_4/:get_5/:get_6/:get_7/:get_8', component: () => import('@/vendor/layouts/index.vue') },
    { name: 'pg_9',             path: '/:get_0/:get_1/:get_2/:get_3/:get_4/:get_5/:get_6/:get_7/:get_8/:get_9', component: () => import('@/vendor/layouts/index.vue') },

    // APP
        { name: 'home',         path: '/index.html', component: () => import('@/vendor/layouts/index.vue') },
        { name: 'home',         path: '/app/www/index.html', component: () => import('@/vendor/layouts/index.vue') },
    // APP

    { name: 'home',             path: '/', component: () => import('@/vendor/layouts/index.vue') },

]

const router = createRouter({
    history: createWebHistory($_GLOBAL?.DIR ?? '/'),
    routes,
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 }
    },
})

router.beforeEach(beforeEach);
router.afterEach(afterEach);

export default router
