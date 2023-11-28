<template>
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;1,600&display=swap" rel="stylesheet" />
  <div class="w-screen max-w-screen-md">
      <div class="flex flex-col">
          <div class="mx-auto max-w-screen-xl">
            <div class="mt-6 overflow-hidden rounded-xl bg-white px-6 shadow lg:px-4">
              <table class="min-w-full border-collapse border-spacing-y-2 border-spacing-x-2">
                <thead class="hidden border-b lg:table-header-group">
                  <tr class="">
                    <td class="whitespace-normal py-4 text-sm font-medium text-gray-500 sm:px-3" v-for="(value, key) in dati[0]" :key="key">{{ key }}</td>
                  </tr>
                </thead>

                <tbody class="bg-white lg:border-gray-300">
                  <tr class="" v-for="(item, index) in dati" :key="index">
                    <!-- Itera sulle chiavi e mostra i valori corrispondenti -->
                    <td class="whitespace-no-wrap py-4 text-left text-sm text-gray-600 sm:px-3 lg:text-left" v-for="(value, key) in item" :key="key">
                      {{ value }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
      </div>
  </div>

</template>

<script>
export default {
  props: [
    'card',
    'dati'

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  mounted() {
    
  }
}
</script>
