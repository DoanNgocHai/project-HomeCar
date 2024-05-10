<template>
  <v-card flat>
    <v-card-title class="d-flex align-center pe-2">
      <v-icon icon="mdi-video-input-component"></v-icon> &nbsp;
      History Sale Car

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
      <template v-slot:header.title>
        <div class="header_title">Name</div>
      </template>
      <template v-slot:header.thumbnail>
        <div class="header_title">Thumbnail</div>
      </template>
      <template v-slot:header.brand.name>
        <div class="header_title">Brands</div>
      </template>
      <template v-slot:header.price>
        <div class="header_title">Price</div>
      </template>
      <template v-slot:header.verify>
        <div class="header_title">Verify</div>
      </template>
      <template v-slot:header.status>
        <div class="header_title">Status</div>
      </template>
      <template v-slot:header.actions>
        <div class="header_title">Action</div>
      </template>
      <template v-slot:item.thumbnail="{ item }">
        <v-card
          width="100"
          height="56"
          class="ma-2"
        >

          <v-img
            v-if="item.thumbnail"
            :src="`http://127.0.0.1:8000${item.thumbnail}`"
            height="65"
            width="100"
          ></v-img>
          <v-img
            src="../../../../public/images/logoCar.png"
            height="65"
            width="100"
          ></v-img>
        </v-card>
      </template>

      <template v-slot:item.verify="{ item }">
        <div>
          <v-chip
            :color="item.verify ? 'green' : 'red'"
            :text="item.verify ? 'Đã duyệt' : 'Đang đợi duyệt'"
            class="text-uppercase"
            label
            size="small"
          ></v-chip>
        </div>
      </template>
      <template v-slot:item.status="{ item }">
        <div>
          <v-chip :color="getChipColorTran(item.status)" :text="getChipTextTran(item.status)" class="text-uppercase" label
            size="small"></v-chip>
        </div>
      </template>
      <template v-slot:[`item.actions`]="{ item } " >
        <v-icon
          small
          class="mr-2"
          @click="dialogVerify(item)"
          
        >
          mdi-file-document-edit-outline
        </v-icon>
        <v-icon
          small
          @click="dialogDelete(item.id)"
        >
          mdi-delete
        </v-icon>
                
      </template>    
    </v-data-table>
  </v-card>
  <v-dialog width="1500" v-model="dialogVerifyCar" >
    <v-card title="Verify Car" >
      <div class="pa-10">
        <div class="d-flex mb-1">
          <div class="form-control w-full mb-2 pr-5">
            <label class="label">
              <span class="label-text">Thân vỏ</span>
            </label>
            <v-text-field type="number" :rules="numberRules" v-model="form.body_condition" label="Nhập điểm số thân vỏ" variant="outlined"></v-text-field>
          </div>
          <div class="form-control w-full mb-2 ">
            <label class="label">
              <span class="label-text">Khung gầm</span>
            </label>
            <v-text-field type="number" :rules="numberRules" v-model="form.chassis_condition" label="Nhập điểm số khung gầm" variant="outlined"></v-text-field>
          </div>
        </div>
        
        <div class="d-flex mb-1">
          <div class="form-control w-full mb-2 pr-5">
            <label class="label">
              <span class="label-text">Động cơ</span>
            </label>
            <v-text-field type="number" :rules="numberRules" v-model="form.engine_condition" label="Nhập điểm số động cơ" variant="outlined"></v-text-field>
          </div> 
          <div class="form-control w-full mb-2 ">
            <label class="label">
              <span class="label-text">Nội thất</span>
            </label>
            <v-text-field type="number" :rules="numberRules" v-model="form.interior_condition" label="Nhập điểm số nội thất" variant="outlined"></v-text-field>
          </div> 
        </div>
        <div class="d-flex mb-1">
          <div class="form-control w-full mb-2 pr-5">
            <label class="label">
              <span class="label-text">Lái thử</span>
            </label>
            <v-text-field type="number" :rules="numberRules" v-model="form.test_drive" label="Nhập điểm số lái thử" variant="outlined"></v-text-field>
          </div> 
          <div class="form-control w-full mb-2 ">
            <label class="label">
              <span class="label-text">Tổng điểm</span>
            </label>
            <v-text-field type="number" :rules="numberRules" v-model="form.score" label="Nhập tổng điểm" variant="outlined"></v-text-field>
          </div>
        </div>
        <div class="form-control w-full mb-2 ">
          <label class="label">
            <span class="label-text">Thông tin thêm</span>
          </label>
          <v-textarea v-model="form.note" label="Nhập thông tin thêm" variant="outlined"></v-textarea>
        </div>
        <div class="form-control w-full mt-2">
          <button @click.prevent="verifyCar()" class="btn">Verify</button>
        </div>
      </div>
    </v-card>
  </v-dialog>
  <v-dialog width="500" v-model="dialogDeleteCar">
      <v-card title="Cảnh Báo">
        <v-card-text>
          Hãy cân nhắc trước khi Xoá!!
        </v-card-text>

        <v-card-actions>
          <v-spacer></v-spacer>

          <v-btn color="blue darken-1" @click="dialogDeleteCar = !dialogDeleteCar">Cancel</v-btn>
          <v-btn color="blue darken-1" @click="deleteItemConfirm(idCar)">OK</v-btn>
        </v-card-actions>
      </v-card>
  </v-dialog>
</template>
<script lang="ts">
import { useToast } from "vue-toastification";
import { adminVerify, adminGetVerify, deleteCar, listCar } from '../../../apis/admin/manage';
  export default {
    data () {
      const toast = useToast();
      return {
        path: "http://127.0.0.1:8000",
        loading: false,
        toast,
        data: [],
        search: '',
        dialogVerifyCar: false,
        dialogDeleteCar: false,
        items: [] as any[],
        idCar:'',
        headers: [
          {
            text: 'No',
            value:'id'

          }, 
          {
            text: 'Name',
            value:'title',
            width: '200'
          }, 
          {
            text: 'Thumbnail',
            value:'thumbnail'
          }, 
          {
            text: 'Brands',
            value:'brand.name'
          }, 
          {
            text: 'Price',
            value: 'price',
          },
          { text: 'Verify', value: 'verify' },
          { text: 'Status', value: 'status' },

          { text: 'Action', value: 'actions', sortable: false },
        ],
        numberRules: [ 
          (v: any) => !!v || "This field is required",
          (v: number) => ( v && v <= 100 ) || "Max score 100",
        ],
        form: {
          body_condition: '',
          chassis_condition:'',
          engine_condition:'',
          interior_condition:'',
          test_drive:'',
          note:'',
          score:'',
        }
      }
    },
    watch: {
      dialogVerifyCar(newVal) {
        if (!newVal) {
          this.form.body_condition = '';
          this.form.chassis_condition = '';
          this.form.engine_condition = '';
          this.form.interior_condition = '';
          this.form.test_drive = '';
          this.form.note = '';
          this.form.score = '';
        }
      }
    },
    created(){
      this.getData();
    },
    methods: {
      async getData() {
        const data = await listCar();
        if (data) {
          this.items = data;
          this.items.forEach(item => {
            item.price = this.currencyVND(item.price);
          });
        }
      },

      async verifyCar() {
        const res = await adminVerify(this.idCar, this.form);
        if (res) {
          console.log(res)
        }
        this.toast.success("Verify thành công");
        this.dialogVerifyCar = !this.dialogVerifyCar;
        this.getData();
      },

      async dialogVerify(item:any) {
        this.idCar = item.id;
        this.dialogVerifyCar = !this.dialogVerifyCar;
        if (item.verify == 1) {
          const res = await adminGetVerify(parseInt(this.idCar));
          if (res) {
            const {
              body_condition,
              chassis_condition,
              engine_condition,
              interior_condition,
              test_drive,
              note,
              score,
            } = res
            this.form = {
              body_condition,
              chassis_condition,
              engine_condition,
              interior_condition,
              test_drive,
              note,
              score,
            };
          } else {
            this.form = {
              body_condition: '',
              chassis_condition:'',
              engine_condition:'',
              interior_condition:'',
              test_drive:'',
              note:'',
              score:'',
            }
          }
        }
      },

      dialogDelete(id:any) {
        this.idCar = id;
        this.dialogDeleteCar = !this.dialogDeleteCar;
      },

      async deleteItemConfirm(idCar:any) {
        try {
          const data = await deleteCar(idCar);
          if (data) {
            this.getData();
            this.toast.success("Xoá thành công");
          }
        } catch (error) {
          this.toast.error("Xóa thất bại!! Xe đang có giao dịch!!");
        }
        this.dialogDeleteCar = false

      },

      currencyVND(value:any) {
        if (value) {
          return new Intl.NumberFormat('vi-VN', { style : 'currency', currency : 'VND'}).format(value);
        }
        return null;
      },

      getChipColorTran(status:any) {
        if (status === 2) {
          return 'grey';
        } else if (status === 3) {
          return 'green';
        } else if (status === 4) {
          return 'red';
        } else {
          return 'grey';
        }
      },

      getChipTextTran(status:any) {
        if (status === 1) {
          return 'Đợi Confirm';
        }
        else if (status === 2) {
          return 'Đang giao dịch';
        }
        else if (status === 3) {
          return 'Giao dịch hoàn tất';
        } else if (status === 4) {
          return 'Đã hủy giao dịch';
        } else {
          return 'Tạo mới';
        }
      },  
    },
    computed: {
    },
  }
</script>
<style scoped>
.header_title{
  font-size: 18px;
  font-weight: 600;
}
</style>
