<template>
  <v-container>
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
                  src="../../../public/images/avatarDF.png"
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
                  :rules="nameRules"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" md="6">
                <label>Email Personal</label>
                <v-text-field
                  dense
                  v-model="profile.email"
                  label="Email"
                  :rules="emailRules"
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
                  :rules="phoneRules"
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
                  :rules="addressRules"
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
                <v-btn :loading="loading" color="primary" @click="Update()">
                  <v-icon>mdi-content-save</v-icon>
                  Update
                </v-btn>
              </v-col>
            </v-row>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { useStore,mapGetters } from 'vuex';
import { getProfile, updateProfile } from '../../apis/profile/profile';
import { useToast } from "vue-toastification";
import { uploadFile } from '../../apis/common/upload-file'
export default {
  data() {
    const toast = useToast();
    const store = useStore();
    return {
      dialogDatePicker: false,
      date: null,
      image: null,
      path: "http://127.0.0.1:8000",
      avatar: [],
      password: "",
      profile: {
        // _method: "PUT",
        name: "",
        email: "",
        avatar: "",
        date_of_birth: "",
        address: "",
        phone: "",
        password: "",
      },
      nameRules: [
        v => !!v || 'Name is required',
        v => (v && v.length <= 20) || 'Name must be less than 20 characters',
      ],
      emailRules: [
        v => !!v || 'E-mail is required',
        v => /.+@.+\..+/.test(v) || 'E-mail must be valid',
      ],
      phoneRules: [
        v => !!v || 'Phone is required',
        v => (v && v.length <= 10) || 'Phone must be less than 10 characters',
      ],
      addressRules: [
        v => !!v || 'Address is required',
        v => (v && v.length <= 100) || 'Phone must be less than 100 characters',
      ],
      birthdayRules: [
        v => !!v || 'Birthday is required',
      ],
      citizenRules: [
        v => !!v || 'Citizen Identity is required',
        v => (v && v.length <= 12 ) || 'Citizen Identity must be less than 12 characters',
      ],
      visible: false,
      toast,
      store,
      loading: false,
      formattedDateOfBirth: ''
    };
  },
  watch: {

  },
  created() {
    this.userProfile();
  },
  computed: {
    maxDate() {
      let now = new Date();
      return now.toISOString().slice(0, 10);
    },
    ...mapGetters(['user']),

  },
  methods: {
    showDatePicker() {
      this.dialogDatePicker = !this.dialogDatePicker;
     },
    getDatePicker(e) {
      if (this.date) {
        let formatdate = this.date?.toLocaleDateString('vi-VN').split('/');
        this.profile.date_of_birth = formatdate[2] + "-" + formatdate[1].padStart(2, '0') + "-" + formatdate[0].padStart(2, '0') ;
      }

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
    async userProfile() {
      try {
        const response = await getProfile();
        this.profile = response;
        this.store.dispatch('userAction', response);
      } catch (error) {
        console.error('Error fetching brands:', error);
      }
    },
    async Update() {
      this.loading = true;
      let param = { "image": this.avatar[0] };
      let dataRs = '';
      let password = this.profile.password;
      if (password == null) {
        password = null
      }
      if (param.image) {
        const data = await uploadFile(param);
        dataRs = {
          name: this.profile.name,
          date_of_birth: this.profile.date_of_birth,
          phone: this.profile.phone,
          address: this.profile.address,
          avatar: data.path,
          password: password,
        };
      } else {
        dataRs = {
          name: this.profile.name,
          date_of_birth: this.profile.date_of_birth,
          phone: this.profile.phone,
          address: this.profile.address,
          password: password,
        };
      }
      console.log(dataRs);
      try {
        const data = await updateProfile(dataRs);
        this.userProfile();
        if (data) {
          this.toast.success("Cập nhật hồ sơ thành công!!");
        }
      } catch (error) {
        // Xử lý lỗi một cách thích hợp, ví dụ in ra console
        this.toast.error("Cập nhật hồ sơ thất bại!!");
      }
      this.loading = false;
    },
    onAvatarClick() {
      document.getElementById("avatar-input").click();
    }
  },
};
</script>

<style scoped lang="scss">
.v-avatar img:hover {
  cursor: -webkit-grab;
}
label {
  font-weight: 450;
  position: relative;
  top: -10px
}
.col-md-6 {
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
.field-birthday{
  padding: 15px;
    padding-left: 35px;
    border: 1px solid #a3a1a1 !important;
}
</style>