<template>
    <LoadingView
        :loading="initialLoading"
    >
        <Heading
            :level="2"
            :class="resourceClasses.Heading"
            class="mb-3 flex items-center">
            <span v-html="headingTitle" />
        </Heading>
        <Card :class="resourceClasses.Card">
            <LoadingView :loading="loading">
                <IndexErrorDialog
                    v-if="resourceResponseError != null"
                    :resource="resource"
                    @click="getResources"
                    :class="resourceClasses.IndexErrorDialog"
                />
                <template v-else>
                    <IndexEmptyDialog
                        v-if="!resources.length"
                        :authorized-to-create="false"
                        :authorized-to-relate="false"
                        :class="resourceClasses.IndexEmptyDialog"
                    />
                    <ResourceTable
                        :authorized-to-relate="false"
                        :resource-name="detailResourceName"
                        :resources="resources"
                        :singular-name="singularName"
                        :sortable="false"
                        class="rounded-t-lg"
                        :class="footerLink.label ? 'border-b border-gray-100 dark:border-gray-700' : 'rounded-b-lg'"
                    />
                    <Component
                        :is="footerLink.externalUrl ? 'a' : 'Link'"
                        :target="footerLink.externalUrl ? '_blank' : null"
                        v-if="footerLink.label"
                        :href="footerLink.externalUrl ? footerLink.externalUrl :footerLink.url"
                        class="px-2 py-2 whitespace-nowrap dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-900 block text-center rounded-b-lg">
                        {{ footerLink.label }}
                    </Component>
                </template>
            </LoadingView>
        </Card>
    </LoadingView>
</template>

<script>
import { CancelToken } from 'axios'
import {
    // HasCards,
    // Paginatable,
    // PerPageable,
    // Deletable,
    // Collapsable,
    // LoadsResources,
    IndexConcerns,
    InteractsWithResourceInformation,
    InteractsWithQueryString,
    // SupportsPolling,
} from '@/mixins'
import { minimum } from '@/util'

export default {
    name: "ResourceCard",
    props: [
        'resourceName',
        'detailResourceName',
        'resourceClasses',
        'footerLink',
    ],
    mixins: [
        IndexConcerns,
        InteractsWithResourceInformation,
    ],
    data() {
        return {
            resource: null,
            canceller: null,
            initialLoading: true,
            loading: true,
            resourceResponse: null,
            resourceResponseError: null,
            resources: [],
            selectAllMatchingResources: false,
            selectedResources: [],
            per_page: 0,
            headingTitle: '&nbsp;'
        }
    },
    mounted() {
        this.getResources()
    },
    methods: {
        getAuthorizationToRelate() {
            return false
        },
        indexURL() {
            return this.url(
                `/resources/${this.resourceName}`,
            )
        },
        getActions() {
            return {}
        },
        getResources() {
            this.loading = true
            this.resourceResponseError = null

            return minimum(
                Nova.request().get('/nova-api/' + this.resourceName, {
                    // params: this.resourceRequestQueryString,
                    params: {},
                    cancelToken: new CancelToken(canceller => {
                        this.canceller = canceller
                    }),
                }),
                300
            ).then(({ data }) => {
                this.resource = data
                this.headingTitle = data.label
                this.resources = data.resources
                this.resourceResponse = data
                this.softDeletes = data.softDeletes
                this.perPage = data.per_page
                this.sortable = data.sortable
                this.loading = false
                this.initialLoading = false

                //
                // this.handleResourcesLoaded()
            })
        }
    }
}
</script>

<style scoped>

</style>
