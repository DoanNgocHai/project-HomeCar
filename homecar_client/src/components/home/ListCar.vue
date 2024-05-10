<template>
    <div class="list-cars">
      <v-row class="px-8">
        <v-col cols="12" class="pt-10">
          <v-card-title><h1 class="title-size"><span></span>Danh sách xe đang bán</h1></v-card-title>
        </v-col>
        <v-col cols="12" class="pb-0">
          <input v-model="q" type="text" placeholder="Nhập tên xe" class="input-search input w-full" />
        </v-col>
        <v-col cols="12" class="pb-10">
          <div class="dropdown mr-4">
            <div tabindex="0" role="button" class="btn">Hãng xe</div>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
              <li v-for="(item, index) in brands" :key="index" @click="onSelectBrand(item.id)">
                <a :class="{ active: item.id == selectedBrandId }">{{ item.name }}</a>
              </li>
            </ul>
          </div>
          <div class="dropdown mr-4">
            <div tabindex="0" role="button" class="btn">Kiểu dáng</div>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
              <li v-for="(item, index) in figures" :key="index" @click="onSelectFigure(item.id)">
                <a :class="{ active: item.id == selectedFigureId }">{{ item.name }}</a>
              </li>
            </ul>
          </div>
          <div class="dropdown mr-4">
            <div tabindex="0" role="button" class="btn">Hộp số</div>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
              <li v-for="(item, index) in gears" :key="index" @click="onSelectGear(item.id)">
                <a :class="{ active: item.id == selectedGearId }">{{ item.name }}</a>
              </li>
            </ul>
          </div>
          <div class="dropdown mr-4">
            <div tabindex="0" role="button" class="btn">Màu sắc</div>
            <ul tabindex="0" class="dropdown-content z-[1] menu p-2 shadow bg-base-100 rounded-box w-52">
              <li v-for="(item, index) in colors" :key="index" @click="onSelectColor(item.id)">
                <a :class="{ active: item.id == selectedColorId }">{{ item.name }}</a>
              </li>
            </ul>
          </div>
        </v-col>
        <v-col class="py" cols="4" v-for="(item, index) in data" :key="index">
          <v-card
          :loading="loading"
          max-width="500"
        >
          <template v-slot:loader="{ isActive }">
            <v-progress-linear
              :active="isActive"
              color="deep-purple"
              height="4"
              indeterminate
            ></v-progress-linear>
          </template>
          <a @click="getCarInfo(item?.id)" >
            <v-img
              v-if="!item.thumbnail"
              src="../../../public/images/logoCar.png"
              cover
              height="250"
            ></v-img>
            <v-img
              cover
              height="250"
              v-else :src="path + item.thumbnail"
            ></v-img>
          </a>
          <v-card-item>
            <v-row>
              <v-col><v-btn size="small" class="button" color="#f5ec42">Chứng nhận Car</v-btn></v-col>
              <v-col class="d-flex justify-end">
                <img
                  class="icon-score"
                  src="../../../public/images/logo_icon.png"
                />
                <span class="score">{{item['verify']['score']}}/100</span>
              </v-col>
            </v-row>
            
            <v-card-title><h2 class="title-size">{{formatNumber(item['price'])}} <span class="font-size">Chỉ từ 2.9 triệu/tháng</span></h2></v-card-title>

            <div>
              <v-row >
                <v-col cols="10" class="container-content">
                  <span class="content-style">{{ item.title }}</span>
                </v-col>
                <v-col cols="2">
                  <span class="me-1">
                    <v-icon
                      @click="toggleFavorite(item)"
                      :color="isFavorite(item) ? 'red' : '#000000'"
                      :icon="isFavorite(item) ? 'mdi-heart' : 'mdi-heart-outline'"
                      size="large"
                    >
                    </v-icon>
                  </span>
                </v-col>
              </v-row>
            </div>
          </v-card-item>

          <v-row class="card">
              <v-col cols="12">
                <div class="box">
                  <v-row>
                    <v-col style="padding-bottom: 5px;">
                      <div class="odo">
                      <span class="icon">
                        <v-icon
                          color="#081f4d"
                          icon="mdi-car-speed-limiter"
                          size="small"
                        ></v-icon>
                      </span>
                      <span>{{ item.odo }} km</span>
                    </div>
                    </v-col>
                    <v-col style="padding-bottom: 5px;">
                      <div class="odo">
                        <span class="icon">
                          <v-icon
                            color="#081f4d"
                            icon="mdi-car-brake-abs"
                            size="small"
                          ></v-icon>
                        </span>
                        <span>{{ item.brand?.name }}</span>
                      </div>
                      
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col style="padding-bottom: 5px; padding-top: 5px;">
                      <div class="odo">
                      <span class="icon">
                        <v-icon
                          color="#081f4d"
                          icon="mdi-tune-vertical"
                          size="small"
                        ></v-icon>
                      </span>
                      <span>{{ item.gear?.name }}</span>
                    </div>
                    </v-col>
                    <v-col style="padding-bottom: 5px; padding-top: 5px;">
                      <div class="odo">
                      <span class="icon">
                        <v-icon
                          color="#081f4d"
                          icon="mdi-water-boiler"
                          size="small"
                        ></v-icon>
                      </span>
                      <span>Xe {{ item.engine }}</span>
                    </div>
                    </v-col>
                  </v-row>
                  <v-row>
                    <v-col style="padding-bottom: 5px; padding-top: 5px;">
                      <div class="odo">
                        <span class="icon">
                          <v-icon
                            color="#081f4d"
                            icon="mdi-car-back"
                            size="small"
                          ></v-icon>
                        </span>
                        <span>{{ item.seat }} chỗ</span>
                      </div>
                    </v-col>
                    <v-col style="padding-bottom: 5px; padding-top: 5px;">
                      <div class="odo">
                      <span class="icon">
                        <v-icon
                          color="#081f4d"
                          icon="mdi-alpha-y-box-outline"
                          size="small"
                        ></v-icon>
                      </span>
                      <span>Năm {{ item.year }}</span>
                    </div>
                    </v-col>
                  </v-row>
                </div>
              </v-col>
            </v-row>
        </v-card>
        </v-col>
      </v-row>
      <div class="d-flex justify-space-around pt-10 ">
        <v-btn style="color: #1c2c5e; font-weight: 500; font-size: 16px;" color="yellow" class="text-none" v-if="isLastPage" @click="loadMore" rounded="xl" >Xem thêm</v-btn>
      </div>
      
    </div>
</template>

<script lang="ts">
import { useToast } from "vue-toastification";
import { getBrands, getColors, getFigures, getGears } from '../../apis/taxonomy';
import { listCar } from '../../apis/user/car';
import { SaveFavoritesDto, deleteCarFavorites, saveCarFavorites } from '../../apis/user/favorites';
import { useRoute } from "vue-router";

export default {
  data() {
    const toast = useToast();
    return {
      path: "http://127.0.0.1:8000",
      loading: false,
      selection: 1,
      data: [],
      favoriteVehicles: [], 
      perPage: 1,
      isLastPage: false,
      toast,
      id_fav: '',
      brands: [],
      figures: [],
      gears: [],
      colors: [],

      q: '',

      selectedBrandId: '',
      selectedFigureId: '',
      selectedGearId: '',
      selectedColorId: '',
    };
  },
  setup() {
    const route = useRoute();

    return {
      route,
    }
  },
  created(){
    this.getTaxonomyData();

    if (this.route.query.q) {
      this.q = this.route.query.q as string;
    }

    if (this.route.query.brand_id) {
      this.selectedBrandId = this.route.query.brand_id as string;
    }

    this.getData();
    this.$store.commit('initFavoriteCars');
  },

  watch: {
    selectedBrandId() {
      this.getData()
    },
    selectedFigureId() {
      this.getData()
    },
    selectedGearId() {
      this.getData()
    },
    selectedColorId() {
      this.getData()
    },
    q() {
      console.log('q = ', this.q)
      this.getData()
    }
  },

  methods: {
    async getTaxonomyData() {
      const brands = await getBrands();
      this.brands = brands.data;

      const figures = await getFigures();
      this.figures = figures.data;

      const gears = await getGears();
      this.gears = gears.data;

      const colors = await getColors();
      this.colors = colors.data;
    },

    async onSelectBrand(brandId: number) {
      this.selectedBrandId = `${brandId}`
    },

    async onSelectFigure(figureId: number) {
      this.selectedFigureId = `${figureId}`
    },

    async onSelectGear(gearId: number) {
      this.selectedGearId = `${gearId}`
    },

    async onSelectColor(colorId: number) {
      this.selectedColorId = `${colorId}`
    },

    async getData() {
      const data = await listCar(
        this.perPage,
        this.q,
        this.selectedBrandId,
        this.selectedFigureId,
        this.selectedGearId,
        this.selectedColorId,
      );
      if (data) {
        this.isLastPage = this.perPage < data.last_page;
        this.data = data?.data;
      }
    },
    async loadMore() {
      this.perPage++;
      const data = await listCar(this.perPage);
      if (data && this.isLastPage) {
        this.isLastPage = this.perPage < data.last_page;
        data?.data.forEach((item:any) => {
          this.data.push(item);
        });
      }
    },
    getCarInfo(id:string) {
      this.$router.push({ path: '/car-info/' + id });
    },
    formatNumber(number:any) {
      if (number >= 1000000 && number < 1000000000) {
        const millions = (number / 1000000).toFixed(0);
        return `${millions}~ Triệu`;
      }
      else if (number >= 1000000000) {
        const billions = (number / 1000000000 * 10).toFixed(0) / 10;
        return `${billions}~ Tỉ`;
      } 
      else {
        return number;
      }
    },
    isFavorite(car: any) {
      // kiểm tra xe đã đc thêm vào yêu thích hay chưa return true or false
      return this.$store.state.favoriteCars.includes(car.id);
    },
    async toggleFavorite(car:any) {
      try {
        if (this.isFavorite(car)) {
          await deleteCarFavorites(car.id); 
          console.log(car.id);
          
          this.$store.commit('removeFavoriteCar', car.id);
          this.toast.success("Đã Xóa!!");

        } else {
          const saveFavoritesDto: SaveFavoritesDto = {
            car_id: car.id || '',
          };
          await saveCarFavorites(saveFavoritesDto);
          this.$store.commit('addFavoriteCar', car.id);
          this.toast.success("Đã Thêm vào xe yêu thích!!");
        }
      } catch (error) {
        console.error('Lỗi khi gọi API', error);
      }
    }
  },
}
</script>

<style lang="scss" scoped>
.list-cars {
  .v-card-title {
    border-bottom: 1px solid #e3e3e3;
  }
  .input-search {
    border: 1px solid gray;
  }
}
.font-size {
  font-size: .875rem;
  color: #2e54a5;
  font-weight: 400;
  font-size: 1rem;
}

.title-size {
  color: #1c2c5e;
  font-weight: 700;
  font-size: 30px;
  margin-bottom: 7px;
}

.container-content {
  overflow: hidden;
  text-overflow: ellipsis;
}

.content-style {
  width: 10%;
  font-size: 25px;
  font-weight: 500;
  letter-spacing: 2px;
  white-space: nowrap;
  text-overflow: ellipsis;
  color: #1c2c5e !important;
  padding: 0px !important;
}

.button {
  font-weight: 550 !important;
  margin: 10px 0px 10px 3px;
  box-shadow: none !important;
}

.box {
  border-radius: 15px;
  background-color: #f2f4fa;
  margin: 0px 15px 0px 15px;
  height: 110px;
  padding: 10px 10px 20px 20px;
  color: #081f4d;
}
.icon {
  position: relative;
  top: -3px;
  margin-right: 5px;
}

.card {
  padding-bottom: 30px;
}
.icon-score{
  width: 43px;
  height: 33px;
  padding-right: 10px;
}
.score{
  position: relative;
  top: 6px;
  color: #1c2c5e;
  font-weight: 600;
}
</style>
