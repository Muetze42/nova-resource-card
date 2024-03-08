<template>
  <div
    class="dashboard-resource overflow-hidden overflow-x-auto"
    :class="[card.resourceName + '-dashboard-resource', card.cardClasses]"
  >
    <ResourceCard
      :resource-name='card.resourceName'
      :singular-name='card.singularName'
      :resource-classes='card.resourceClasses'
      :detail-resource-name='card.detailResourceName'
      :footer-link='card.footerLink'
    />
  </div>
</template>

<script>
import ResourceCard from './ResourceCard'

export default {
  props: ['card'],
  components: {
    ResourceCard
  }
}
</script>
