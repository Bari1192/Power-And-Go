<template>
  <div class="border border-sky-500 rounded-3xl overflow-hidden">
    <table class="w-full">
      <thead>
        <tr class="text-white bg-sky-500">
          <th v-for="egyadat in columns" :key="egyadat.key" class="px-4 py-2 text-center">
            {{ egyadat.ertek }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(row, index) in paginatedRows"
          :key="index"
          class="odd:bg-sky-100 even:bg-white text-center"
        >
          <td
            v-for="egyadat in columns"
            :key="egyadat.key"
            class="px-4 py-2 text-sky-500"
          >
            {{ row[egyadat.key] }}
          </td>
        </tr>
      </tbody>
    </table>

    <!-- LAPOZÁSI RÉSZ!! -->
    <div class="flex justify-center my-4 space-x-2">
      <button
        v-for="page in totalPages"
        :key="page"
        @click="$emit('updatePage', page)"
        class="px-4 py-2 rounded-md"
        :class="page === currentPage ? 'bg-sky-600 text-white' : 'bg-sky-400'"
      >
        {{ page }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    rows: {
      type: Array,
      required: true,
    },
    columns: {
      type: Array,
      required: true,
    },
    itemsPerPage: {
      type: Number,
      default: 50,
    },
    currentPage: {
      type: Number,
      default: 1,
    },
  },
  computed: {
    paginatedRows() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.rows.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.rows.length / this.itemsPerPage);
    },
  },
};
</script>
