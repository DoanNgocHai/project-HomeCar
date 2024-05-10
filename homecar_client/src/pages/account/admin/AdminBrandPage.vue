<template>
  <v-card flat>
    <v-card-title class="d-flex align-center pe-2">
      <v-icon icon="mdi-video-input-component"></v-icon> &nbsp;
      Manager Brands

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
    <div class="d-flex justify-end pr-10 pt-5">
      <v-btn @click="createBrand(item)" color="primary" size="x-large" density="compact" icon="mdi-plus"></v-btn>
    </div>
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
      <template v-slot:header.name>
        <div class="header_title">Name</div>
      </template>
      <template v-slot:header.logo>
        <div class="header_title">Logo</div>
      </template>
      <template v-slot:header.year_establishment>
        <div class="header_title">Year</div>
      </template>
      <template v-slot:header.actions>
        <div class="header_title">Action</div>
      </template>
      <template v-slot:item.logo="{ item }">
          <v-img
            class="avatar_user"
            v-if="item.logo"
            :src="`http://127.0.0.1:8000${item.logo}`"
            height="40"
            width="40"
          ></v-img>
          <v-img
            v-else
            src="../../../../public/images/avatarDF.png"
            height="40"
            width="40"
          ></v-img>
      </template>

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
                
      </template>    
    </v-data-table>
  </v-card>
  <v-dialog v-model="dialogUpdateUser" >
    <v-card title="Create brand" >
      <v-row no-gutters>
        <v-col cols="12" sm="12">

          <v-card outlined>
            <v-card-text>
              <v-row>
                <v-col cols="12" md="12">
                  <label>Name</label>
                  <v-text-field
                    dense
                    v-model="form.name"
                    label="Name"
                    variant="outlined"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <label>Năm sản xuất</label>
                  <v-text-field
                    dense
                    v-model="form.year_establishment"
                    label="Year"
                    required
                    variant="outlined"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="12">
                  <label>Logo</label>
                    <v-file-input
                        @change="onFileChange"
                        v-model="form.logo"
                        color="deep-purple-accent-4"
                        counter
                        label="File input"
                        prepend-icon=""
                        multiple
                        placeholder="Select your files"
                        variant="outlined"
                        :show-size="1000"
                    >
                        <template v-slot:selection="{ fileNames }">
                        <template v-for="(fileName, index) in fileNames" :key="fileName">
                            <v-chip
                            v-if="index < 2"
                            color="deep-purple-accent-4"
                            label
                            size="small"
                            class="me-2"
                            >
                            {{ fileName }}
                            </v-chip>

                            <span
                            v-else-if="index === 2"
                            class="text-overline text-grey-darken-3 mx-2"
                            >
                            + file_name.length - 2  File(s)
                            </span>
                        </template>
                        </template>
                    </v-file-input>
                </v-col>
                <v-col cols="12">
                  <v-btn :loading="loading" color="primary" @click="submitCreateBrand()">
                    <v-icon>mdi-content-save</v-icon>
                    Create
                  </v-btn>
                </v-col>
              </v-row>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-card>
  </v-dialog>
  <v-dialog width="500" v-model="dialogDelete">
      <v-card title="Cảnh Báo">
        <v-card-text>
          Hãy cân nhắc trước khi Xoá!!
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="blue darken-1" text @click="dialogDelete = !dialogDelete">Cancel</v-btn>
          <v-btn color="blue darken-1" text @click="deleteBrandConfirm(this.idUser)">OK</v-btn>
        </v-card-actions>
      </v-card>
  </v-dialog>
</template>
<script>
import { getBrands, adminCreateBrand, deleteBrand } from '../../../apis/admin/manage';
import { useToast } from "vue-toastification";
import { useStore,mapGetters } from 'vuex';
import { uploadFile } from '../../../apis/common/upload-file'
export default {
    data () {
      const toast = useToast();
    return {
        path: "http://127.0.0.1:8000",
        loading: false,
        toast,
        data: [],
        search: '',
        dialogUpdateUser: false,
        dialogDelete: false,
        items: [],
        headers: [
          {
            text: 'No',
            value:'id'

          }, 
          {
            text: 'Name',
            value:'name'

          }, 
          {
            text: 'Logo',
            value:'logo'
          }, 
          {
            text: 'Year',
            value: 'year_establishment',
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
    onFileChange(file) {
      if (!file) {
        return;
      }
      const reader = new FileReader();
      reader.onload = () => {
        this.form.logo = file;
      };
    },
    async getData() {
        const data = await getBrands();
        console.log(data);
        if (data) {
          this.items = data;
        }
    },
    async submitCreateBrand() {
        
        let param = { "image": this.form.logo[0] };
        const data = await uploadFile(param);
      const { name, year_establishment } = this.form;
      const createBrandDto = {
        name: name,
        year_establishment: year_establishment,
        logo: data.path,
      };
      try {
        const data = await adminCreateBrand(createBrandDto);
        if (data) {
            this.dialogUpdateUser = !this.dialogUpdateUser;
            this.getData();
            this.toast.success("Thêm mới Hãng thành công!!");
        }
      } catch (error) {
        this.toast.error("Thêm mới brand thất bại!!");
        console.error('Error submitting car info:', error);
      }
    },

        
    createBrand(item) {
        this.dialogUpdateUser = !this.dialogUpdateUser;
    },
        deleteItem(idBrand) {
        this.dialogDelete = !this.dialogDelete
        this.idBrand = idBrand;
      },
      async deleteBrandConfirm() {
        try {
          const data = await deleteBrand(this.idBrand);
          if (data) {
            this.getData();
            this.dialogDelete = false
            this.toast.success("Xoá thành công");
            
          }
        } catch (error) {
          this.toast.error("Xóa thất bại!!");
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