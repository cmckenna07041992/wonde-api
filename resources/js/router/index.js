import { createRouter, createWebHistory } from 'vue-router';
import SchoolPage from '@/pages/SchoolPage.vue';
import TeacherProfile from '@/pages/TeacherProfile.vue';

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'SchoolPage',
      component: SchoolPage,
    },
    {
      path: '/teacher/:id',
      name: 'TeacherProfile',
      component: TeacherProfile,
    }
  ]
});

export default router;