<template>
  <v-card flat>
    <v-card-title class="d-flex align-center pe-2">
      <v-icon icon="mdi-video-input-component"></v-icon> &nbsp;
      Manager User

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
      <template v-slot:header.name>
        <div class="header_title">Name</div>
      </template>
      <template v-slot:header.avatar>
        <div class="header_title">Avatar</div>
      </template>
      <template v-slot:header.email>
        <div class="header_title">Email</div>
      </template>
      <template v-slot:header.phone>
        <div class="header_title">Phone</div>
      </template>
      <template v-slot:header.date_of_birth>
        <div class="header_title">Birthday</div>
      </template>
      <template v-slot:header.actions>
        <div class="header_title">Action</div>
      </template>
      <template v-slot:item.avatar="{ item }">
          <v-img
            class="avatar_user"
            v-if="item.avatar"
            :src="`http://127.0.0.1:8000${item.avatar}`"
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
          @click="editUser(item)"
          
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
    <v-card title="Update User" >
      <v-row no-gutters>
        <v-col cols="12" sm="12">

          <v-card outlined>
            <div class="mx-auto text-center">
              <v-file-input
                id="avatar-input"
                v-model="avatar"
                outlined
                dense
                @change="onFileChange"
                hide-input
                prepend-icon="mdi-camera"
                style="display: none"
              >
              </v-file-input>
              <label>
                <v-avatar size="120" @click="onAvatarClick">
                  <img
                    v-if="!image && !profile.avatar"
                    src="../../../../public/images/avatarDF.png"
                  />
                  <img v-else-if="image" :src="image" />
                  <img v-else :src="path + profile.avatar" />
                </v-avatar>
                <v-icon class="icon-camera" @click="onAvatarClick" color="success" icon="mdi-camera"></v-icon>

              </label>
            </div>
            <v-card-text>
              <v-row>
                <v-col cols="12" md="6">
                  <label>Name</label>
                  <v-text-field
                    dense
                    v-model="profile.name"
                    label="Name"
                    variant="outlined"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <label>Email Personal</label>
                  <v-text-field
                    dense
                    v-model="profile.email"
                    label="Email"
                    required
                    variant="outlined"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <label>Phone</label>
                  <v-text-field
                    dense 
                    v-model="profile.phone"
                    label="Phone"
                    variant="outlined"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <label>Address</label>
                  <v-text-field
                    dense
                    v-model="profile.address"
                    label="Address"
                    variant="outlined"
                    required
                  ></v-text-field>
                </v-col>
                <v-col cols="12" md="6">
                  <label>Birthday</label>
                  <v-text-field
                    dense
                    variant="outlined"
                    v-model="profile.date_of_birth"
                    @click="showDatePicker"
                  ></v-text-field>
                  <v-dialog width="300" v-model="dialogDatePicker">
                    <v-date-picker
                      color="primary"
                      v-model="date"
                      @click="getDatePicker"
                    >
                    </v-date-picker>
                  </v-dialog>
                  <!-- <VueDatePicker class="field_birthday" placeholder="Birth day" v-model="profile.date_of_birth" :enable-time-picker="false" /> -->
                </v-col>
                <v-col cols="12" md="6">
                  <label class="font-weight-medium">Password</label>
                  <v-text-field
                    dense
                    v-model="profile.password"
                    :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                    :type="visible ? 'text' : 'password'"
                    placeholder="Enter your password"
                    prepend-inner-icon="mdi-lock-outline"
                    @click:append-inner="visible = !visible"
                    variant="outlined"
                  ></v-text-field>
                </v-col>


                <v-col cols="12">
                  <v-btn :loading="loading" color="primary" @click="Update(this.idUser)">
                    <v-icon>mdi-content-save</v-icon>
                    Update
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
          <v-btn color="blue darken-1" text @click="deleteUserConfirm(this.idUser)">OK</v-btn>
        </v-card-actions>
      </v-card>
  </v-dialog>
  <!-- {{ brands }} -->
</template>
<script>
import { listUser, updateUser, deleteUser } from '../../../apis/admin/manage';
import { useToast } from "vue-toastification";
import { useStore,mapGetters } from 'vuex';
import { uploadFile } from '../../../apis/common/upload-file'
// import VueDatePicker from '@vuepic/vue-datepicker';
export default {
    // components: { VueDatePicker },
    data () {
      const toast = useToast();
    return {
        dialogDatePicker: false,
        date: null,
        path: "http://127.0.0.1:8000",
        loading: false,
        toast,
        data: [],
        search: '',
        dialogUpdateUser: false,
        dialogDelete: false,
        items: [],
        idCar: '',
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
            text: 'Avatar',
            value:'avatar'
          }, 
          {
            text: 'Email',
            value: 'email',
          },
          { text: 'Phone', value: 'phone' },

          { text: 'Birthday', value: 'date_of_birth' },
        
          { text: 'Action', value: 'actions', sortable: false },
        ],
        visible: false,
        profile: '',
        menu: false,
        image: null,
        avatar: [],
        idUser:'',
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
        const data = await listUser();
        if (data) {
          this.items = data;
        }
      },
      editUser(item) {
        this.dialogUpdateUser = !this.dialogUpdateUser;
        this.profile = Object.assign({}, item);
        this.idUser = item.id;
      },
      createImage(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.image = e.target.result;
          this.avatar = file;
        };
        reader.readAsDataURL(file[0]);
      },
      onFileChange() {
        if (!this.avatar) {
          return;
        }
        this.createImage(this.avatar);
      },
      onAvatarClick() {
        document.getElementById("avatar-input").click();
      },
      async Update(idUser) {
        try {
          let param = { "image": this.avatar[0] };
          let file_not_null = "";
          if(this.avatar[0] && this.avatar[0].length != 0){
            file_not_null = { "image": this.avatar[0] };
            const upload_file = await uploadFile(file_not_null);
            console.log(upload_file.path);
            this.profile.avatar = upload_file.path;
            this.avatar[0] = [];
          }
          const data = await updateUser(idUser, this.profile);
          if (data) {
            this.toast.success("Cập nhật User thành công!!");
          }
          this.getData();
          this.dialogUpdateUser = false;
        } catch (error) {
          this.toast.error("Cập nhật hồ sơ thất bại!!");
        }
      },
      deleteItem(idUser) {
        this.dialogDelete = !this.dialogDelete
        this.idUser = idUser;
      },
      async deleteUserConfirm(idUser) {
        try {
          const data = await deleteUser(idUser);
          if (data) {
            this.getData();
            this.dialogDelete = false
            this.toast.success("Xoá thành công");
            
          }
        } catch (error) {
          this.toast.error("Xóa thất bại!! User đang có giao dịch!!");
        }
      },
      showDatePicker() {
        this.dialogDatePicker = !this.dialogDatePicker;
      },
      getDatePicker(e) {
        if (this.date) {
          let formatdate = this.date?.toLocaleDateString('vi-VN').split('/');
          this.profile.date_of_birth = formatdate[2] + "-" + formatdate[1].padStart(2, '0') + "-" + formatdate[0].padStart(2, '0') ;
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