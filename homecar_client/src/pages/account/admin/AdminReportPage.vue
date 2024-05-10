<template>
  <v-card flat>
    <v-card-title class="d-flex align-center pe-2">
      <v-icon icon="mdi-video-input-component"></v-icon> &nbsp;
      Manager Report

      <v-spacer></v-spacer>

      <v-text-field
        v-model="search"
        prepend-inner-icon="mdi-magnify"
        density="compact"
        label="Search"
        single-line
        flat
        hide-details
        variant="solo-filled"
      ></v-text-field>
    </v-card-title>
    <v-divider></v-divider>

    <v-data-table v-model:search="search" :items="items" :headers="headers" class="pa-5">
      <template v-slot:header.id>
        <div class="header_title">ID</div>
      </template>
      <template v-slot:[`item.id`]="{ index }">
        <div>
          {{ index + 1 }}
        </div>
      </template>
      <template v-slot:header.user.name>
        <div class="header_title">User name</div>
      </template>
      <template v-slot:header.car.title>
        <div class="header_title">Car name</div>
      </template>
      <template v-slot:header.reason>
        <div class="header_title">Reason</div>
      </template>
      <template v-slot:header.description>
        <div class="header_title">Description</div>
      </template>
<!-- 
      <template v-slot:[`item.actions`]="{ item } " >
        <v-icon
          small
          class="mr-2" 
        >
          mdi-file-document-edit-outline
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item.id)"
        >
          mdi-delete
        </v-icon>
                
      </template>     -->
    </v-data-table>
  </v-card>
</template>
<script>
import { listReport } from '../../../apis/user/report';
import { useToast } from "vue-toastification";
import { useStore,mapGetters } from 'vuex';
export default {
    data () {
      const toast = useToast();
    return {
        loading: false,
        toast,
        data: [],
        search: '',
        items: [],
        headers: [
          {
            text: 'No',
            value:'id'

          },
          {
            text: 'User name',
            value:'user.name'

          },
          {
            text: 'Car name',
            value:'car.title'

          }, 
          {
            text: 'Reason',
            value:'reason'

          }, 
          {
            text: 'Description',
            value:'description'
          }, 
          { text: 'Action', value: 'actions', sortable: false },
        ],
        logo: [],
        idBrand:'',

        form: {
            name:'',
            year_establishment:'',
            logo: [],
        }
      }
    },
    watch: {
      dialogUpdateUser(newVal) {
        if (!newVal) {
          this.image = null
        }
      }
    },
    created(){
      this.getData();
    },
    methods: {
      async getData() {
          const data = await listReport();
          if (data) {
            this.items = data;
          }
      },
   
    },
    
  }
</script>
<style scoped>
.header_title{
  font-size: 18px;
  font-weight: 600;
}
.avatar_user{
  border-radius: 50%;
    border: 1px solid;
}
.v-avatar img{
  border: solid 3px #4caf50;
}
.v-avatar{
  margin-top: 50px;
  cursor: pointer;
  border: solid 3px #4caf50;
}
.icon-camera{
  padding-top: 120px;
  padding-left: 25px;
  cursor: pointer;
}
</style>