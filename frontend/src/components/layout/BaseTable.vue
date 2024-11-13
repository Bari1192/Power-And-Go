<template>
  <div
    class="w-3/4 mx-auto mb-12 inline-block border-4 border-lime-600 rounded-3xl overflow-hidden"
  >
    <!-- Rendszám keresés -->
    <form class="ps-4 my-8">
      <div class="flex flex-col">
        <label for="rendszam" class="text-xl py-2">Rendszám keresése: </label>
        <input
          type="text"
          name="rendszam"
          v-model="rendszamSzures"
          placeholder="Ide írjon..."
          class="px-4 py-2 text-sky-700 rounded mb-4 w-1/5"
        />
      </div>
    </form>

    <table class="w-full">
      <thead>
        <tr class="text-white bg-sky-500">
          <th v-for="egyadat in columns" :key="egyadat.key" class="py-2 text-center">
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
          <td v-for="egyadat in columns" :key="egyadat.key" class="py-2 text-sky-600">
            {{ row[egyadat.key] }}
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Lapozás -->
    <div class="mx-auto flex justify-center py-8 space-x-4">
      <button
        v-for="page in totalPages"
        :key="page"
        @click="currentPage = page"
        class="px-4 py-2 rounded-md"
        :class="page === currentPage ? 'bg-sky-600 text-white' : 'bg-gray-200'"
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
  },
  data() {
    return {
      rendszamSzures: "",
      currentPage: 1,
    };
  },
  computed: {
    filteredRows() {
      if (this.rendszamSzures) {
        return this.rows.filter((row) =>
          row.Rendszam.toLowerCase().includes(this.rendszamSzures.toLowerCase())
        );
      }
      return this.rows;
    },
    paginatedRows() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredRows.slice(start, end);
    },

    totalPages() {
      return Array.from(
        { length: Math.ceil(this.filteredRows.length / this.itemsPerPage) },
        (_, i) => i + 1
      );
    },
  },
  watch: {
    rendszamSzures() {
      this.currentPage = 1;
    },
  },
};
</script>
