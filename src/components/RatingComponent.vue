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
      rating: false,
    };
  },
  mounted(){
  },
  methods: {
    async rate(score){
      if(this.rating) return;
      this.rating = true;
      
      let result = await this.post("files/rateFile", {
        idFile: this.file.idFile,
        rating: this.file.rating == score ? 0 : score
      });

      if(!result.error){
        if(this.file.rating == 0){
          this.file.globalRating += score;
          this.file.rating = score;
        }
        else if(this.file.rating == score){
          this.file.globalRating -= score;
          this.file.rating = 0;
        }else{
          this.file.globalRating += score * 2;
          this.file.rating = score;
        }
      }else{
        this.showErrorTooltip("Rating was not successfull");
      }

      this.rating = false;
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
