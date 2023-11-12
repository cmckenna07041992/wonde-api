<template>
  <div class="grid h-100">
    <div class="col is-12">
      <loading-spinner v-if="loading" />
      <card
        v-else
        class="m-5"
      >
        <template #title>
          <div class="flex justify-content-between">
            <span class="text-500 font-medium mb-3 capitalize">
              {{ formatName(teacher.title, teacher.forename, teacher.surname) }}
            </span>
            <prime-button
              icon="pi pi-arrow-left"
              class="ml-2"
              label="Back"
              severity="secondary"
              @click="backToList()"
            />
          </div>
        </template>
        <template #content>
          <DataView
            :value="lessons"
            paginator
            :rows="5"
          >
            <template #list="slotProps">
              <div class="col-12">
                <Panel
                  toggleable
                  collapsed
                >
                  <template #header>
                    <div class="flex align-items-center gap-2">
                      <Avatar
                        size="large"
                        shape="circle"
                        :label="`${slotProps.data.student_count}`"
                        class="mr-2"
                      />
                      <span class="font-bold">
                        {{ slotProps.data.class_name }}: {{ slotProps.data.class_subject }}
                      </span>
                      <span>{{ slotProps.data.start_at_date }} {{ slotProps.data.time_from_to }}</span>
                    </div>
                  </template>
                  <div class="grid">
                    <div
                      v-for="student in slotProps.data.students"
                      :key="student.id"
                      class="col-4"
                    >
                      <div class="flex align-items-center gap-3">
                        <Avatar
                          :label="student.initials"
                          class="mr-2"
                          shape="circle"
                        />
                        <span class="flex align-items-center gap-2">
                          <span class="font-semibold">
                            {{ student.forename }}
                            {{ student.surname }}
                          </span>
                        </span>
                      </div>
                    </div>
                  </div>
                </Panel>
              </div>
            </template>
          </DataView>
        </template>
      </card>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useRoute, useRouter } from 'vue-router';
import { useCommon } from '@/composables/useCommon.js';
import LoadingSpinner from '@/components/LoadingSpinner.vue';
import Card from 'primevue/card';
import DataView from 'primevue/dataview';
import PrimeButton from 'primevue/button';
import Panel from 'primevue/panel';
import Avatar from 'primevue/avatar';

const loading = ref(true);
const teacher = ref(null);
const lessons = ref([]);
const toastMessage = useToast();
const route = useRoute();

onMounted(() => (getTeacher()));

const { formatName } = useCommon();

const router = useRouter();
const backToList = () => {
  router.push({ name: 'SchoolPage' });
};

function getTeacher() {
  axios.get(`/school/teachers/${route.params.id}`)
    .then((response) => {
      if (response.data.teacher) {
        teacher.value = response.data.teacher;
      }
      if (response.data.lessons) {
        lessons.value = response.data.lessons.sort((a, b) => {
          if (a.start_at_date < b.start_at_date) return -1;
          if (a.start_at_date > b.start_at_date) return 1;
          return 0;
        });
      }
    })
    .catch(() => {
      toastMessage.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Unable to load teacher profile.',
        life: 3000,
      });
    })
    .finally(() => {
      loading.value = false;
    });
}
</script>