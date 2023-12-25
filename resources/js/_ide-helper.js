import Card from './../../../../vendor/laravel/nova/resources/js/components/Card.vue'
import Heading from './../../../../vendor/laravel/nova/resources/js/components/Heading.vue'
import IndexEmptyDialog from './../../../../vendor/laravel/nova/resources/js/components/IndexEmptyDialog.vue'
import IndexErrorDialog from './../../../../vendor/laravel/nova/resources/js/components/IndexErrorDialog.vue'
import LoadingView from './../../../../vendor/laravel/nova/resources/js/components/LoadingView.vue'
import ResourceTable from './../../../../vendor/laravel/nova/resources/js/components/ResourceTable.vue'

window.__ = function (key, replacements) {}

Vue.component('Card', Card)
Vue.component('Heading', Heading)
Vue.component('IndexEmptyDialog', IndexEmptyDialog)
Vue.component('IndexErrorDialog', IndexErrorDialog)
Vue.component('LoadingView', LoadingView)
Vue.component('ResourceTable', ResourceTable)
Vue.mixin({
    methods: {
        __(key, replacements) {
            return __(key, replacements)
        },
        logout() {},
        stopImpersonating() {}
    },
    data() {
        return {
            currentUser: {
                avatar: String,
                email: String,
                impersonating: Boolean
            }
        }
    }
})

window.appConfig = Object

window.Nova = new class {
    config(key) {
        return appConfig[key]
    }

    redirectToLogin() {
    }
}
