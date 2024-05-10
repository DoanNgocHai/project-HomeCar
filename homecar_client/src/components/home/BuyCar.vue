<template>
  <div class="card card-compact w-2/3 bg-base-100 shadow-xl card-bordered	 mr-4 buy-car">
    <div class="card-body">
      <div class="mb-4">
        <h3 class="card-title">Mua xe</h3>
        <div class="w-full mb-2">
          <input v-model="q" type="text" placeholder="Tìm kiếm xe..." class="input border input-bordered w-full" />
        </div>
        <div class="w-full flex justify-space-between">
          <button class="btn">Dưới 300tr</button>
          <button class="btn">Từ 300tr - 500tr</button>
          <button class="btn">Từ 500tr - 700tr</button>
          <button class="btn">Trên 700tr</button>
          <button class="btn btn-warning" @click="handleSearchCar()">Giúp tôi chọn xe</button>
        </div>
      </div>
      <div class="">
        <h3 class="card-title mb-2">Xe theo hãng / kiểu dáng</h3>
        <div class="w-full">
          <div class="list-brands">
            <div class="_1brand cursor-pointer" v-for="(item, index) in brands" :key="index" @click="handleSelectBrand(item['id'])">
              <img class="mt-6" :src="`http://127.0.0.1:8000${item['logo']}`" :alt="item['logo']">
              <h6>{{ item['name'] }}</h6>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { getBrands } from '../../apis/taxonomy';

export default {
  data() {
    return {
      q: '',
      brands: [],
    }
  },

  created() {
    this.getListBrands()
  },

  methods: {
    async getListBrands() {
      const brands = await getBrands();
      this.brands = brands.data;
    },

    async handleSearchCar() {
      this.$router.push(`/list-car?q=${this.q}`)
    },

    async handleSelectBrand(brandId: number) {
      this.$router.push(`/list-car?brand_id=${brandId}`)
    }
  }

}
</script>

<style lang="scss" scoped>
.list-brands {
  display: flex;
  flex-wrap: wrap;
  margin: 20px 0px;

  ._1brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: calc(100% / 7);
    text-align: center;
    cursor: pointer;

    h6 {
      text-align: center;
    }

    img {
      display: block;
      width: 55px;
      height: 40px;
      object-fit: contain;
    }
  }
}
</style>
