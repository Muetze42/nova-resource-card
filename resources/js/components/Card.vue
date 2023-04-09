<template>
    <div
        class="dashboard-resource"
        :class="[card.resourceName + '-dashboard-resource', card.cardClasses]"
    >
        <ResourceCard
            :resource-name="card.resourceName"
            :singular-name="card.singularName"
            :resource-classes="card.resourceClasses"
            :model-resource-name="card.modelResourceName"
        />
    </div>
</template>

<script>
import ResourceCard from "./ResourceCard";

export default {
    props: ['card'],
    components: {
        ResourceCard,
    }
}
</script>
