<template>
  <div class="w-screen max-w-screen-md">
    <div class="flex flex-col">
      <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-lg">
        <form class="">

          <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">

            <div class="flex flex-col">
                <label for="manufacturer" class="text-sm font-medium text-stone-600">Tipologia Evento</label>

                <select id="gtrack-type-event" class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                  <option value="VIEWS">Views</option>
                  <option value="CLICK">Click</option>
                  <option value="CLICK_LINK">Click Link</option>
                </select>
            </div>

            <div class="flex flex-col">
              <label for="manufacturer" class="text-sm font-medium text-stone-600">Che cosa</label>

              <select id="gtrack-what" class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
              </select>
            </div>

            <div class="flex flex-col">
                <label for="manufacturer" class="text-sm font-medium text-stone-600">Di cosa</label>

                <select id="gtrack-of-what" class="mt-2 block w-full rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                  
                </select>
            </div>

            <div class="flex flex-col">
              <label for="date" class="text-sm font-medium text-stone-600">Data da</label>
              <input type="date" id="gtrack-date-from" class="mt-2 block w-full cursor-pointer rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex flex-col">
                <label for="date" class="text-sm font-medium text-stone-600">Data a</label>
                <input type="date" id="gtrack-date-to" class="mt-2 block w-full cursor-pointer rounded-md border border-gray-100 bg-gray-100 px-2 py-2 shadow-sm outline-none focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50" />
            </div>

            <div class="flex flex-col">
              <div class="w-fit space-y-2">
                <label for="returnPdf" class="text-sm font-medium text-stone-600">Desideri un pdf?</label>
                <div class="relative flex w-56 items-center rounded bg-gray-50 py-3 px-4 pl-14 font-medium text-gray-700" id="inputPdfContainer">
                  <input class="peer hidden" type="checkbox" name="returnPdf" id="returnPdf" />
                  <label class="absolute left-0 top-0 h-full w-full cursor-pointer rounded border peer-checked:border-blue-600 peer-checked:bg-blue-100" for="returnPdf"> </label>
                  <div class="absolute left-4 h-5 w-5 rounded border-2 border-gray-300 bg-gray-200 ring-blue-600 ring-offset-2 peer-checked:border-transparent peer-checked:bg-blue-600 peer-checked:ring-2" id="inputPdfFake"></div>
                  <span class="pointer-events-none z-10">Si</span>
                </div>
              </div>
            </div>

          </div>

          <div class="mt-6 grid w-full grid-cols-2 justify-end space-x-4 md:flex">
            <button class="rounded-lg bg-gray-200 px-8 py-2 font-medium text-gray-700 outline-none hover:opacity-80 focus:ring">Reset</button>
            <button class="rounded-lg bg-blue-600 px-8 py-2 font-medium text-white outline-none hover:opacity-80 focus:ring" id="applySearch" type="button" @click="faiRichiesta">Applica ricerca</button>
          </div>
        </form>
      </div>
    </div>
    <div id="gtrack-table" class="pt-5">
      <GTrackTable v-if="datiDisponibili" :dati="dati" />
      <h1 v-if="dati && dati.length == 0">Non ci sono risultati</h1>
    </div>
  </div>
</template>

<script>
import GTrackTable from './GTrackTable.vue';

export default {
  components: {
    GTrackTable, // Registra il componente figlio nell'oggetto components
  },
  props: [
    'card',

    // The following props are only available on resource detail cards...
    // 'resource',
    // 'resourceId',
    // 'resourceName',
  ],

  data() {
    return {
      dati: null,  // Inizializza i dati a null
      datiDisponibili: false,  // Flag che indica se i dati sono disponibili
    };
  },

  methods: {
    async search(){
      const event_type = document.querySelector('#gtrack-type-event').value;
      const resource_what = document.querySelector('#gtrack-what').value;
      const resource_id = document.querySelector('#gtrack-of-what').value;
      let what = '';
      let link_api = '';

      if (resource_what == 'core_page') {
        what = 'page';
      } else if (resource_what == 'core_banner') {
        what = 'banner';
      } else if (resource_what == 'magazine_news') {
        what = 'news';
      }

      if (event_type == 'VIEWS') {
        link_api = `api/statistics/${what}/${resource_id}/views`;
      } else if (event_type == 'CLICK') {
        link_api = `api/statistics/${what}/${resource_id}/clicks`;
      } else if (event_type == 'CLICK_LINK') {
        link_api = `api/statistics/${what}/${resource_id}/link-clicks`;
      }

      const dateFrom = document.querySelector('#gtrack-date-from').value;
      const dateTo = document.querySelector('#gtrack-date-to').value;

      const returnPdf = document.querySelector('#returnPdf');
      let gtrackConfig = await this.getGtrackConfig();
      gtrackConfig = gtrackConfig.data;

      const website_code = gtrackConfig.website_code;
      const gtrack_url = gtrackConfig.url;

      const request = fetch(`${gtrack_url}/${link_api}?returnPdf=${returnPdf.checked}&nova=true&website_code=${website_code}&dateFrom=${dateFrom}&dateTo=${dateTo}`, { method: 'GET' });
      if (returnPdf.checked) {
        request.then(response => {
          return response.blob();
        })
          .then(blob => {
            // Verifica se il browser supporta window.open() con il MIME type del PDF
            const isSupported = window.open('', '_blank');

            if (isSupported) {
              // Apri il PDF in un nuovo tab
              const objectUrl = URL.createObjectURL(blob);
              window.open(objectUrl, '_blank');
            } else {
              // Se window.open() non è supportato, avvia il download
              const a = document.createElement('a');
              const objectUrl = URL.createObjectURL(blob);
              a.href = objectUrl;
              a.download = 'gtrack.pdf';  // Sostituisci con il nome desiderato del file
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
            }
          })
          .catch(error => console.error('Errore durante il recupero del PDF:', error));
      } else {
        request.then(response => response.json())
          .then(result => {
            if (result.result) {
              console.log('result', result.data);
              this.dati = result.data;
              this.datiDisponibili = true;
            }
          })
          .catch(error => console.log('error', error));
      }
    },

    async getGtrackConfig() {
      return await fetch(`/nova-vendor/gtrack/config`, { method: 'GET' })
        .then(response => response.json())
        .then(result => result)
        .catch(error => console.log('error', error));
    }
  },

  mounted() {
    fetch("/nova-vendor/gtrack/what", {method: 'GET'})
      .then(response => response.json())
      .then(result => {
        if(result.result){
           const gtrackWhat = document.querySelector('#gtrack-what');
            let options = '';
            for (const [table, nameTable] of Object.entries(result.data)) {
              options += `<option value="${table}">${nameTable}</option>`;
            }

            gtrackWhat.innerHTML = options;
            loadResourceWhat(gtrackWhat);
        }
      })
      .catch(error => console.log('error', error));

    document.querySelector('#gtrack-what').addEventListener('change', function(){
      const gtrackWhat = document.querySelector('#gtrack-what');
      loadResourceWhat(gtrackWhat);
    });

    function loadResourceWhat(elementHTML){
      fetch(`/nova-vendor/gtrack/of-what?resource_what=${elementHTML.value}`, { method: 'GET' })
        .then(response => response.json())
        .then(result => {
          if (result.result) {
            const gtrackOfWhat = document.querySelector('#gtrack-of-what');
            let options = '';
            result.data.forEach(element => {
              options += `<option value="${element.id}">${element.name}</option>`;
            });

            gtrackOfWhat.innerHTML = options;
          }
        })
        .catch(error => console.log('error', error));
    }

    document.querySelector('#applySearch').addEventListener('click', async () => {
      await this.search();
    });

    document.querySelector('#returnPdf').addEventListener('change', function(){
      const inputPdfContainer = document.querySelector('#inputPdfContainer');
      const inputPdfFake = document.querySelector('#inputPdfFake');

      if(this.checked){
        inputPdfContainer.style.background = '#dbe9fe';
        inputPdfFake.style.background = '#2463eb';
      }else{
        inputPdfContainer.style.background = '';
        inputPdfFake.style.background = '';
      }
      
    });
  },
}
</script>
