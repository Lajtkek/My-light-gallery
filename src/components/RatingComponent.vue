<template>
  <span>
    <v-icon class="clickable" :class="file.rating > 0 ? 'like' : ''" @click="rate(1)">mdi-plus-box</v-icon>
    <v-icon class="clickable" :class="file.rating < 0 ? 'dislike' : ''" @click="rate(-1)">mdi-minus-box</v-icon>
  </span>
</template>

<script>
export default {
  name: "RatingComponent",
  props: ["file"],
  data: function () {
    return {
      // icon: "mdi-content-copy"
    };
  },
  mounted(){
  },
  methods: {
    rate(score){
      console.log(this.file.rating)
      if(this.file.rating == 0){
        this.file.globalRating += score;
        this.file.rating = score;
        return;
      }
      if(this.file.rating == score){
        this.file.globalRating -= score;
        this.file.rating = 0;
        return;
      }
      this.file.globalRating += score * 2;
      this.file.rating = score;
    }
  },
};
</script>

<style lang="less" scoped>
@import "../assets/styles/main.less";
.like{
  color: green;
}
.dislike{
  color: blue;
}
</style>
