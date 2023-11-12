<template>
  <div class="grid mt-5">
    <div class="col-6">
      <span class="text-xl font-semibold">School Teachers</span>
    </div>
    <div class="col-6 text-right">
      <prime-button
        icon="pi pi-arrow-left"
        label="Previous"
        severity="secondary"
        :disabled="!pagination.previous"
        @click="togglePage(-1)"
      />
      <prime-button
        icon="pi pi-arrow-right"
        class="ml-2"
        label="Next"
        severity="secondary"
        :disabled="!pagination.next"
        @click="togglePage(1)"
      />
    </div>
    <div class="col-12">
      <DataTable
        ref="dt"
        data-key="id"
        :loading="loading"
        :value="teachers"
        striped-rows
        show-gridlines
        scrollable
        scroll-height="75vh"
        :table-props="{ style: { minWidth: '800px' } }"
      >
        <template #empty>
          <div class="text-center text-lg">
            No Teachers for this school
          </div>
        </template>
        <Column
          field="title"
          header="Title"
        />
        <Column
          field="forename"
          header="Forename"
        />
        <Column
          field="surname"
          header="Surname"
        />
        <Column
          header="No. of Classes"
        >
          <template #body="slotProps">
            {{ slotProps.data.classes_count }}
          </template>
        </Column>
        <Column
          style="width:20px"
          header=""
        >
          <template #body="slotProps">
            <prime-button
              icon="pi pi-eye"
              severity="success"
              rounded
              text
              @click="openEmployee(slotProps.data.id)"
            />
          </template>
        </Column>
      </DataTable>
    </div>
  </div>
</template>
<script setup>
import { ref, onMounted } from 'vue';
import { useToast } from 'primevue/usetoast';
import { useRouter } from 'vue-router';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import PrimeButton from 'primevue/button';

const loading = ref(true);
const teachers = ref([]);
const pagination = ref({
  currentPage: 1,
  next: null,
  previous: null,
  perPage: 50
});
const togglePage = (page) => {
  pagination.value.currentPage = pagination.value.currentPage + page;
  getSchoolTeachers();
};
const toastMessage = useToast();

onMounted(() => (getSchoolTeachers()));

function getSchoolTeachers() {
  loading.value = true;
  axios.get(`/school/teachers?page=${pagination.value.currentPage}`)
    .then((response) => {
      if (response.data.teachers) {
        teachers.value = response.data.teachers;
      }
      if (response.data.pagination) {
        pagination.value = response.data.pagination;
      }
    })
    .catch(() => {
      toastMessage.add({
        severity: 'error',
        summary: 'Error',
        detail: 'Unable to load school teacher details.',
        life: 3000,
      });
    })
    .finally(() => {
      loading.value = false;
    });
}

const router = useRouter();
const openEmployee = (id) => {
  router.push({ name: 'TeacherProfile', params: { id } });
};
</script>
