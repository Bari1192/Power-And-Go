<template>
  <BaseHeader />
  <main
    class="min-h-screen flex flex-col bg-gradient-to-b from-[#09283a] via-[#1e4d65] to-[#0b2a3d] text-cyan-50"
  >
    <slot class="glex-grow" />
  </main>
  <BaseFooter />
</template>
<script>
import BaseHeader from "@components/layout/BaseHeader.vue";
import BaseFooter from "@components/layout/BaseFooter.vue";

export default {
  components: {
    BaseHeader,
    BaseFooter,
  },
};
</script>
