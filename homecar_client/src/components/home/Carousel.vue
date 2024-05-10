<template>
  <v-carousel
    cycle
    height="540"
    hide-delimiter-background
    show-arrows="hover"
    hide-delimiters
  >
    <v-carousel-item 
    cover
      v-for="(slide, i) in slides"
      :key="i"
      :src="slide.src"
    >
    </v-carousel-item>
  </v-carousel>
</template>
<script>
  export default {
    data () {
      return {
        slides: [
          {
            src: 'https://cdn-v2.carpla.vn/1920x/filters:format(webp)/cms/banner1-1701769509.212.jpg',
          },
          {
            src: '../../../public/images/sl1.png',
          },
          {
            src: '../../../public/images/sl2.png',
          },
          {
            src: '../../../public/images/sl3.png',
          },
        ],
      }
    },
  }
</script>