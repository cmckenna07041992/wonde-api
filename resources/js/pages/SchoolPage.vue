<template>
  <div class="grid h-100">
    <div class="col is-12">
      <loading-spinner v-if="loading" />
      <card
        v-else
        class="m-5"
      >
        <template #title>
          {{ school.name }}
        </template>
        <template #subtitle>
          {{ formatAddress(school.address) }}
        </template>
        <template #content>
          <div
            v-if="counts"
            class="surface-ground px-4 py-5 md:px-6 lg:px-8"
          >
            <div class="grid">
              <div
                v-for="key in Object.keys(counts)"
                :key="key"
                class="col-12 md:col-6 lg:col-3"
              >
                <div class="surface-card shadow-2 p-3 border-round">
                  <div class="flex justify-content-between mb-3">
                    <div>
                      <span class="block text-500 font-medium mb-3 capitalize">
                        Total {{ key }}
                      </span>
                    </div>
                    <div
                      class="flex align-items-center justify-content-center bg-blue-100 border-round text-900 font-medium text-xl"
                    >
                      <span class="p-1">{{ counts[key].data.count }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <teacher-list />
        </template>
      </card>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useCommon } from '@/composables/useCommon.js';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Card from 'primevue/card';
import TeacherList from '@/components/TeacherList.vue';

const loading = ref(true);
const school = ref(null);
const counts = ref(null);
const toastMessage = useToast();
const { formatAddress } = useCommon();

onMounted(() => (getSchool()));

function getSchool() {
  axios.get('/school')
    .then((response) => {
      if (response.data.school) {
        school.value = response.data.school;
      }
      if (response.data.counts) {
        counts.value = response.data.counts;
      }
    })
    .catch(() => {
      toastMessage.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Unable to load school details.',
        life: 3000,
      });
    })
    .finally(() => {
      loading.value = false;
    });
}
</script>