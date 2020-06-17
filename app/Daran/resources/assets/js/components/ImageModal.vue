<template>

    <div class="modal image-modal" tabindex="-1" role="dialog" v-if="show">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="control-label">Caricacamento immagine</label>
                </div>
                <div class="modal-body">
                    <input type="file" @change="fileChange" ref="file" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" @click="insertImage" :disabled="!validImage">Aggiungi</button>
                    <button type="button" class="btn btn-secondary" @click="closeModal">Annulla</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
  components: {
  },
  data() {
    return {
        imagesrc:null,
        command: null,
        show: false,
    };
  },
  computed: {
      validImage() {
        return (
          this.imagesrc !== null
        );
      }

  },
  methods: {
      showModal(command) {
        this.command = command;
        this.show = true;
      },
      fileChange(e) {
        const reader = new FileReader();
            var value = null;
             reader.onload = function (e) {
                this.imagesrc = e.target.result;
             }.bind(this);
            reader.readAsDataURL(this.$refs.file.files[0]);

      },
      insertImage() {
      const data = {
        command: this.command,
        data: {
          src: this.imagesrc
        }
      };

      this.$emit("onConfirm", data);
      this.closeModal();
    },
    closeModal() {
      this.show = false;
      this.imagesrc = "";
    }
  }
};
</script>

<style scoped>
.image-modal {
  display: flex;
  align-items: center;
  justify-content: center;
  position: fixed;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  background-color: rgba(0, 0, 0, 0.5);
}
</style>
