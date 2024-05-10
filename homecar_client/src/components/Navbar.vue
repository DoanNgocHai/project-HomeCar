<template>
  <div class="header bg-base-100">
    <div class="header-content w-full mx-auto flex justify-between items-center">
      <div class="logo">
        <a href="/">
          <img src="../../public/images/logoCar-ok1.png" alt="Logo" class="h-20 w-auto" />
        </a>
      </div>
      <div class="menu-header whitespace-nowrap text-neutral">
        <ul class="flex">
          <li><router-link to="/">Trang chủ</router-link></li>
          <li><router-link to="/list-car">Mua xe</router-link></li>
          <li><router-link to="/sale-car">Bán xe</router-link></li>
          <li><router-link to="/sale-car">Dịch vụ</router-link></li>
          <li><router-link to="/sale-car">Giới thiệu</router-link></li>
        </ul>
      </div>
      <div v-if="user == null" data-id="account" class="h-[40px] rounded-full border-gray-200 border px-4 py-2 hidden lg:flex items-center nav-hover-item cursor-pointer group font-bold text-ellipsis overflow-hidden whitespace-nowrap mr-3 max-w-[200px]">
        <div >
          <img src="../../public/images/account_icon.png" width="24" height="24" alt="Tài khoản" class="inline-flex">
          <span title=" " class="text-neutral hidden lg:inline ml-2 mr-2 text-ellipsis overflow-hidden whitespace-nowrap">Tài khoản</span>
        </div>
        <!-- Dropdown content -->
        <transition name="fade">
          <div class="menu-dropdown-nav py-0 top-14 w-40 group-hover:block drop-shadow-lg bg-white dropdown-menu absolute hidden h-auto rounded-md z-50">
            <div class="menu-dropdown-nav py-4 bg-white">
              <ul class="list-none top-2 text-gray-700 pt-1 mx-4">
                <li>
                  <router-link to="/auth/register" class="w-full py-2 px-4 hover:bg-slate-200 block whitespace-no-wrap rounded-md navbar-item-link mb-1" rel="nofollow">Đăng ký</router-link>
                </li>
                <li>
                  <router-link to="/auth/login" class="w-full py-2 px-4 hover:bg-slate-200 block whitespace-no-wrap rounded-md navbar-item-link" rel="nofollow">Đăng nhập</router-link>
                </li>
              </ul>
            </div>
          </div>
        </transition> 
      </div>
      
      <div v-else data-id="account" class="h-[40px] rounded-full px-4 py-2 hidden lg:flex items-center nav-hover-item cursor-pointer group font-bold text-ellipsis overflow-hidden whitespace-nowrap mr-3 max-w-[200px]">
        <div class="d-flex">
          <img v-if="user?.avatar == null" src="../../public/images/account_icon.png" width="40" height="40" alt="Tài khoản" class="inline-flex">
          <img v-else :src="path + user?.avatar" alt="Tài khoản" class="inline-flex avatar_user">
          <span title=" " class="name_user text-neutral hidden lg:inline ml-2 mr-2 text-ellipsis overflow-hidden whitespace-nowrap">{{user?.name}}</span>
          <img width="11" src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMyIgaGVpZ2h0PSI5IiB2aWV3Qm94PSIwIDAgMTMgOSIgZmlsbD0ibm9uZSI+CiAgPHBhdGggZD0iTTcuNTE1NzkgOC4wODgxMUM3LjExNjE2IDguNTYzOTkgNi4zODM4NCA4LjU2Mzk5IDUuOTg0MjEgOC4wODgxMUwxLjIwMTY0IDIuMzkzMUMwLjY1NTI1NiAxLjc0MjQ3IDEuMTE3ODEgMC43NSAxLjk2NzQyIDAuNzVMMTEuNTMyNiAwLjc1MDAwMUMxMi4zODIyIDAuNzUwMDAxIDEyLjg0NDcgMS43NDI0NyAxMi4yOTg0IDIuMzkzMUw3LjUxNTc5IDguMDg4MTFaIiBmaWxsPSIjMDgxRjREIi8+CiAgPGRlZnM+CiAgICA8bGluZWFyR3JhZGllbnQgaWQ9InBhaW50MF9saW5lYXJfOTExMl8zOTA4MCIgeDE9IjE2LjQzMjEiIHkxPSIxMy4xMDI1IiB4Mj0iMTAuODk3IiB5Mj0iLTIuOTU4MTEiIGdyYWRpZW50VW5pdHM9InVzZXJTcGFjZU9uVXNlIj4KICAgICAgPHN0b3Agc3RvcC1jb2xvcj0iIzBBRkRCMSIvPgogICAgICA8c3RvcCBvZmZzZXQ9IjAuNTUyMDgzIiBzdG9wLWNvbG9yPSIjMDJCNDY5Ii8+CiAgICAgIDxzdG9wIG9mZnNldD0iMSIgc3RvcC1jb2xvcj0iIzAyOUY1NCIvPgogICAgPC9saW5lYXJHcmFkaWVudD4KICA8L2RlZnM+Cjwvc3ZnPgo=">
        
        </div>
        <!-- Dropdown content -->
        <transition name="fade">
          <div class="menu-dropdown-nav py-0 top-14 w-40 group-hover:block drop-shadow-lg bg-white dropdown-menu absolute hidden h-auto rounded-md z-50">
            <div class="menu-dropdown-nav py-4 bg-white">
              <ul class="list-none top-2 text-gray-700 pt-1 mx-4">
                <li>
                  <router-link to="/account/profile" class="w-full py-2 px-4 hover:bg-slate-200 block whitespace-no-wrap rounded-md navbar-item-link" rel="nofollow">Quản lý</router-link>
                </li>
                <li>
                  <a @click="onSubmit()" class="w-full py-2 px-4 hover:bg-slate-200 block whitespace-no-wrap rounded-md navbar-item-link" rel="nofollow">Đăng xuất</a>
                </li>
              </ul>
            </div>
          </div>
        </transition> 
      </div>
      <div class="car-like px-2 items-center hidden lg:flex" bis_skin_checked="1">
        <div class="cursor-pointer tooltip w-[42px] h-[42px] flex justify-center items-center rounded-full border-gray-200 border" bis_skin_checked="1">
          <router-link to="/favorites-car" class="w-full h-full flex justify-center items-center" rel="nofollow" aria-label="favorite-cars">
            <img src="../../public/images/heart.png" width="24" alt="xe yêu thích" height="24"> 
            <img src="../../public/images/heart_active_button.png" alt="xe yêu thích" width="24" height="24" style="display: none;">
          </router-link> 
        </div>
      </div>
      <span class="pr-2 cursor-pointer">
        <span class="text-sm block leading-[1rem]">Hotline CSKH</span>
        <a class="font-bold text-tcSecondary text-lg leading-[1rem]">0396337628</a>
      </span>
    </div>
  </div>
</template>
<script lang="ts">
import { useToast } from "vue-toastification";
import { mapGetters, useStore } from 'vuex';

export default {
  data() {
    const toast = useToast();
    const store = useStore();

    return {
      isVisible: false,
      toast,
      path: "http://127.0.0.1:8000",
      store
    };
  },
  methods: {
    async onSubmit() {
      localStorage.removeItem('access_token');
      localStorage.removeItem('user');
      window.location.href = '/auth/login';
    },
  },
  mounted() {
    const interval = setInterval(() => {
      if (localStorage.getItem('isLogin')) {
        this.toast.success("Đăng nhập thành công");
      }
      clearInterval(interval);
      localStorage.removeItem('isLogin');
    }, 500); 
  },
  computed: {
    ...mapGetters(['user']),
  }
};
</script>
<style scoped>
/* Thêm các kiểu CSS tùy chỉnh cho header */

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to {
  opacity: 0;
}
.header {
  background-color: #ffffff;
  color: rgb(0, 0, 0);
  
}

.header-content {
  position: fixed;
  z-index: 1;
  background: #ffff;
  padding: 0px 5rem;
}

.logo {
  flex: 1; /* Logo chiếm phần lớn của header */
}

.menu-header {
  color: #081f4d!important;
  font-family: inherit;
  font-weight: 600!important;
  margin-right: 29px;
  font-size: 16px;
}
.menu-header ul li {
  margin-right: 33px;
}
.car-like{
  margin-right: 20px;

}
.avatar_user{
  width: 40px;
  height: 40px;
  max-width: 40px;
  max-height: 40px;
  min-width: 40px;
  min-height: 40px;
  border-radius:50% ;
}
.name_user{
  font-size: 20px;
  position: relative;
  top: 5px;
  left: 5px;
  max-width: 125px;
}

</style>
