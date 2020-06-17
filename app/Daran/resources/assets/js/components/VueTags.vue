<template>
  <div>
    <vue-tags-input
      v-model="tag"
      :tags="tags"
      :autocomplete-items="availableTags"
      :placeholder="placeholder"
      @tags-changed="newTags => tags = newTags"
    />
    <input type="text" name="tags_string" :value="JSON.stringify(tags)" hidden/>
  </div>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input';

export default {
  components: {
    VueTagsInput,
  },
  props:{
      ownTags: Array,
      allTags: Array,
  },
  mounted: function () {
      this.tags = this.ownTags;
      this.availableTags = this.allTags;
  },
  data() {
    return {
      tag: '',
      tags: [],
      availableTags: [],
      placeholder:'Inserisci un tag e premi invio',
    };
  }
};
</script>
